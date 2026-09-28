<?php

namespace Tests\Feature;

use App\Models\Master\LaboratoryProductMethod;
use App\Models\Master\LaboratoryProductMethodParameter;
use App\Models\Master\LaboratoryTestMethod;
use App\Models\Master\LaboratoryTestParameter;
use App\Models\Master\MasterLocation;
use App\Models\Master\MasterProduct;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Laboratory\LaboratoryHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LaboratoryWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_complete_laboratory_workflow_supports_revision_and_multi_step_approval()
    {
        $product = MasterProduct::first();
        $approvalHeader = ApprovalHeader::where([
            'inspection_type' => 1,
            'type' => 1,
            'status' => 1,
        ])->first();

        if (!$product || !$approvalHeader || !MasterLocation::find($approvalHeader->location)) {
            $this->markTestSkipped('Laboratory workflow smoke test requires an existing product, location, and active Lab approval setting.');
        }

        $approvers = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
            ->orderBy('step')
            ->get();

        if ($approvers->isEmpty()) {
            $this->markTestSkipped('Laboratory workflow smoke test requires at least one configured approver.');
        }

        $creator = User::find($approvers->first()->user_id);
        if (!$creator) {
            $this->markTestSkipped('Configured Laboratory approver user was not found.');
        }

        $suffix = strtoupper(substr(sha1(uniqid('', true)), 0, 10));
        $method = LaboratoryTestMethod::create([
            'code' => 'TEST-' . $suffix,
            'name' => 'Automated Laboratory Method',
            'status' => 1,
            'user_id' => $creator->id,
        ]);
        $parameter = LaboratoryTestParameter::create([
            'code' => 'AVG-' . $suffix,
            'name' => 'Automated Average Result',
            'result_type' => 'numeric',
            'default_unit' => 'mm',
            'status' => 1,
            'user_id' => $creator->id,
        ]);
        $productMethod = LaboratoryProductMethod::create([
            'product_id' => $product->id,
            'test_method_id' => $method->id,
            'status' => 1,
            'user_id' => $creator->id,
        ]);
        LaboratoryProductMethodParameter::create([
            'product_method_id' => $productMethod->id,
            'test_parameter_id' => $parameter->id,
            'sequence' => 1,
            'source_type' => 'STATIC',
            'operator' => 'BETWEEN',
            'min_value' => 10,
            'max_value' => 20,
            'unit' => 'mm',
            'measurement_count' => 2,
            'calculation_type' => 'AVG',
            'is_required' => 1,
            'status' => 1,
            'user_id' => $creator->id,
        ]);

        $this->actingAs($creator);
        $this->get(route('master_laboratory'))->assertOk();
        $this->get(route('lab'))->assertOk();

        $createResponse = $this->postJson(route('addProductLaboratory'), [
            'product_id' => $product->id,
            'product_method_id' => $productMethod->id,
            'test_date' => now()->format('Y-m-d'),
            'location_id' => $approvalHeader->location,
            'shift' => $approvalHeader->shift ?: 1,
        ])->assertOk();

        $laboratoryCode = $createResponse->json('data.laboratory_code');
        $laboratory = LaboratoryHeader::where('laboratory_code', $laboratoryCode)->firstOrFail();
        $detail = $laboratory->detailsRelation()->firstOrFail();

        $this->assertEquals(10.0, $detail->standard_snapshot['min_value']);
        $this->assertEquals(20.0, $detail->standard_snapshot['max_value']);

        $this->postJson(route('saveLaboratoryMeasurement'), [
            'detail_id' => $detail->id,
            'measurements' => [10, 20],
            'in_charge_user_id' => $creator->id,
            'checker_user_id' => $creator->id,
        ])->assertOk();

        $detail->refresh();
        $this->assertEquals(15, $detail->result_value);
        $this->assertSame('GOOD', $detail->evaluation);
        $this->assertCount(2, $detail->measurementsRelation);

        $this->postJson(route('lockProductLaboratory'), [
            'laboratory_code' => $laboratoryCode,
        ])->assertOk();

        $laboratory->refresh();
        $this->assertSame(2, (int) $laboratory->status);
        $this->assertTrue((bool) $laboratory->lock);

        $firstApprover = User::findOrFail($approvers->first()->user_id);
        $this->actingAs($firstApprover)->postJson(route('updateLaboratoryApproval'), [
            'laboratory_code' => $laboratoryCode,
            'decision' => 4,
            'remark' => 'Repeat the measurement.',
        ])->assertOk();

        $laboratory->refresh();
        $this->assertSame(4, (int) $laboratory->status);
        $this->assertFalse((bool) $laboratory->lock);

        $this->actingAs($creator)->postJson(route('saveLaboratoryMeasurement'), [
            'detail_id' => $detail->id,
            'measurements' => [12, 18],
        ])->assertOk();
        $this->postJson(route('lockProductLaboratory'), [
            'laboratory_code' => $laboratoryCode,
        ])->assertOk();

        foreach ($approvers as $approver) {
            $this->actingAs(User::findOrFail($approver->user_id))->postJson(route('updateLaboratoryApproval'), [
                'laboratory_code' => $laboratoryCode,
                'decision' => 1,
                'remark' => 'Approved by automated workflow test.',
            ])->assertOk();
        }

        $laboratory->refresh();
        $this->assertSame(3, (int) $laboratory->status);
        $this->assertTrue((bool) $laboratory->lock);
        $this->assertNotNull($laboratory->completed_at);
        $this->assertSame(
            $approvers->count() + 1,
            ApprovalHistory::where('transaction_code', $laboratoryCode)->count()
        );
    }
}
