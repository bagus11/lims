<?php

namespace Tests\Feature;

use App\Models\Master\Material;
use App\Models\Master\Supplier;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\Material\ResinDetail;
use App\Models\Transaction\Material\InspectionMaterial;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Smoke test alur Inspection Material resin (inspection_type = 2):
 * create -> isi properti -> lock -> reject -> perbaiki -> lock ulang ->
 * approve semua step -> Done, plus guard double-lock & edit pasca-lock.
 */
class InspectionMaterialWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_resin_workflow_supports_reject_and_multi_step_approval()
    {
        $approvalHeader = ApprovalHeader::where('inspection_type', 2)->first();
        $supplier = Supplier::first();
        $material = Material::where('status', 1)->first();

        if (!$approvalHeader || !$supplier || !$material) {
            $this->markTestSkipped('Material workflow smoke test requires supplier, active material, and approval setting type 2.');
        }

        $approvers = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
            ->orderBy('step')
            ->get();
        if ($approvers->isEmpty()) {
            $this->markTestSkipped('Material workflow smoke test requires configured approvers.');
        }

        $creator = User::find($approvers->first()->user_id);
        if (!$creator) {
            $this->markTestSkipped('Configured approver user was not found.');
        }

        $creator->kode_kantor = $approvalHeader->location;
        $creator->save();
        $this->actingAs($creator);

        $createResponse = $this->postJson(route('addInspectionMaterial'), [
            'type' => 1,
            'location' => $approvalHeader->location,
            'material' => $material->id,
            'inspector' => $creator->id,
            'supplier' => $supplier->id,
            'brand' => 'Demo Brand',
            'amount' => 60,
            'weight' => 1500,
            'certificate' => 'CERT-DEMO',
            'standard' => 'SNI-DEMO',
            'no_lot' => 'LOT-DEMO-01',
        ])->assertOk();

        $code = $createResponse->json('data.inspection_code');
        $this->assertNotEmpty($code);

        $header = InspectionMaterial::where('inspection_code', $code)->firstOrFail();
        $this->assertSame(0, (int) $header->status);
        // amount 60 -> 8 step bulk density
        $this->assertSame(8, ResinDetail::where('inspection_code', $code)->count());

        // amount <= 0 ditolak
        $this->postJson(route('addInspectionMaterial'), [
            'type' => 1, 'location' => $approvalHeader->location, 'material' => $material->id,
            'inspector' => $creator->id, 'supplier' => $supplier->id, 'brand' => 'X',
            'amount' => 0, 'weight' => 1, 'certificate' => 'X', 'standard' => 'X', 'no_lot' => 'X',
        ])->assertStatus(422);

        $this->postJson(route('updateResinDetail'), [
            'inspection_code' => $code, 'step' => 1, 'value' => 0.65,
        ])->assertOk();

        $this->postJson(route('updateResinProperties'), [
            'inspection_code' => $code,
            'select_vivsible_properties' => 'OK',
            'select_resin_package' => 'Sak',
            'resin_color' => 'White',
            'resin_particle_size' => 'Fine',
            'resin_volatile_matter' => 0.2,
            'resin_amount_per_sak' => 25,
            'resin_weight_per_sak' => 25,
            'resin_pof_number' => 'POF-1',
            'resin_remark' => 'demo',
        ])->assertOk();

        $header->refresh();
        $this->assertSame(1, (int) $header->status);

        $this->postJson(route('lockInspectionMaterial'), ['inspection_code' => $code])->assertOk();
        $header->refresh();
        $this->assertSame(2, (int) $header->status);
        $this->assertSame(1, (int) $header->lock);
        $this->assertSame(1, ApprovalHistory::where('transaction_code', $code)->where('inspection_type', 2)->count());

        // double-lock & edit pasca-lock ditolak
        $this->postJson(route('lockInspectionMaterial'), ['inspection_code' => $code])->assertStatus(400);
        $this->postJson(route('updateResinDetail'), [
            'inspection_code' => $code, 'step' => 2, 'value' => 0.7,
        ])->assertStatus(400);

        // Reject oleh approver step 1 -> Rejected (4) + unlock
        $firstApprover = User::findOrFail($approvers->first()->user_id);
        $this->actingAs($firstApprover)->postJson(route('updateInspectionGoodsApproval'), [
            'inspection_type' => 2, 'type' => 1,
            'transaction_code' => $code,
            'approval_resin' => 2,
            'imr_remark' => 'Mohon ulangi pengukuran.',
        ])->assertOk();

        $header->refresh();
        $this->assertSame(4, (int) $header->status);
        $this->assertSame(0, (int) $header->lock);

        // perbaiki lalu lock ulang
        $this->actingAs($creator)->postJson(route('updateResinDetail'), [
            'inspection_code' => $code, 'step' => 1, 'value' => 0.66,
        ])->assertOk();
        $this->postJson(route('lockInspectionMaterial'), ['inspection_code' => $code])->assertOk();
        $header->refresh();
        $this->assertSame(2, (int) $header->status);

        // approve semua step sampai Done
        foreach ($approvers as $approver) {
            $this->actingAs(User::findOrFail($approver->user_id))->postJson(route('updateInspectionGoodsApproval'), [
                'inspection_type' => 2, 'type' => 1,
                'transaction_code' => $code,
                'approval_resin' => 1,
                'imr_remark' => 'Approved by automated workflow test.',
            ])->assertOk();
        }

        $header->refresh();
        $this->assertSame(3, (int) $header->status);

        // sudah Done: approval lanjutan ditolak
        $this->actingAs($firstApprover)->postJson(route('updateInspectionGoodsApproval'), [
            'inspection_type' => 2, 'type' => 1,
            'transaction_code' => $code,
            'approval_resin' => 1,
            'imr_remark' => 'Should fail.',
        ])->assertStatus(422);
    }
}
