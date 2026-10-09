<?php

namespace Tests\Feature;

use App\Models\Master\Machine;
use App\Models\Master\Material;
use App\Models\Master\MasterProduct;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Goods\WallThickness;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\InspectionGoodsDetail;
use App\Models\Transaction\Inspection\InspectionGoodsSubDetail;
use App\Models\Transaction\Insspection\InspectionGood;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Smoke test alur Inspection Goods per shift (inspection_type = 3):
 * create header (3 shift) -> isi progress shift 1 -> lock -> reject ->
 * lock ulang -> approve semua step -> shift Complete, plus guard
 * double-lock & edit wall thickness pasca-lock.
 */
class InspectionGoodsWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_goods_shift_workflow_supports_reject_and_multi_step_approval()
    {
        $approvalHeader = ApprovalHeader::where('inspection_type', 3)->where('shift', 1)->first();
        $product = MasterProduct::first();
        $material = Material::where('status', 1)->first();
        $machine = Machine::where('status', 1)->first();

        if (!$approvalHeader || !$product || !$material || !$machine) {
            $this->markTestSkipped('Goods workflow smoke test requires product, material, machine, and approval setting type 3 shift 1.');
        }

        $approvers = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
            ->orderBy('step')
            ->get();
        if ($approvers->isEmpty()) {
            $this->markTestSkipped('Goods workflow smoke test requires configured approvers.');
        }

        $creator = User::find($approvers->first()->user_id);
        if (!$creator) {
            $this->markTestSkipped('Configured approver user was not found.');
        }

        $creator->kode_kantor = $approvalHeader->location;
        $creator->save();
        $this->actingAs($creator);

        $createResponse = $this->postJson(route('addInspectionGoodsHeader'), [
            'location_id' => $approvalHeader->location,
        ])->assertOk();

        $code = $createResponse->json('data.inspection_code');
        $this->assertNotEmpty($code);

        $header = InspectionGood::where('inspection_code', $code)->firstOrFail();
        $this->assertSame(0, (int) $header->status);
        $this->assertSame(3, InspectionGoodsDetail::where('inspection_code', $code)->count());

        $detail = InspectionGoodsDetail::where('inspection_code', $code)->where('shift', 1)->firstOrFail();

        // lock tanpa progress ditolak
        $this->postJson(route('lockInspectionGoods'), [
            'detail_inspection_code' => $detail->detail_inspection_code,
        ])->assertStatus(400);

        $this->postJson(route('addSubDetailInspectionGoods'), [
            'detail_inspection_code_id' => $detail->detail_inspection_code,
            'product' => $product->id,
            'material' => $material->id,
            'machine_id' => $machine->id,
            'inspector' => $creator->id,
            'length_of_pipe' => 6,
            'appearance_of_pipe' => 'OK',
            'marking' => 'OK',
            'condition_pipe_of_cut' => 'OK',
            'angle_pipe_of_spigot' => 'OK',
            'color_of_pipes' => 'OK',
            'evaluation_spec' => 'OK',
            'manufacturing_code' => 'MFG-DEMO',
            'tape_diameter' => 110,
            'time' => '08:00',
            'add_remark' => 'Automated workflow test',
            'wt_1' => 5.1, 'wt_2' => 5.2, 'wt_3' => 5.1, 'wt_4' => 5.0,
            'wt_5' => 5.2, 'wt_6' => 5.1, 'wt_7' => 5.0, 'wt_8' => 5.1,
        ])->assertOk();

        $header->refresh();
        $this->assertSame(1, (int) $header->status);
        $this->assertSame(1, InspectionGoodsSubDetail::where('inspection_detail', $detail->detail_inspection_code)->count());
        $wt = WallThickness::where('detail_inspection_code', $detail->detail_inspection_code)->firstOrFail();

        $this->postJson(route('lockInspectionGoods'), [
            'detail_inspection_code' => $detail->detail_inspection_code,
        ])->assertOk();

        $detail->refresh();
        $header->refresh();
        $this->assertSame(1, (int) $detail->status);
        $this->assertSame(1, (int) $detail->lock);
        $this->assertSame(2, (int) $header->status);
        $this->assertSame(1, ApprovalHistory::where('transaction_code', $detail->detail_inspection_code)->where('inspection_type', 3)->count());

        // double-lock & mutasi pasca-lock ditolak
        $this->postJson(route('lockInspectionGoods'), [
            'detail_inspection_code' => $detail->detail_inspection_code,
        ])->assertStatus(400);
        $this->postJson(route('updateWallThickness'), [
            'id' => $wt->id,
            'dynamic_wt_1' => 9.9,
        ])->assertStatus(400);
        $this->postJson(route('addWallThickness'), [
            'detail_inspection_code' => $detail->detail_inspection_code,
            'inspection_sub_detail' => $wt->sub_detail_inspection_code,
            'update_wt_1' => 9.9,
        ])->assertStatus(400);

        // Reject -> shift kembali Incomplete + unlock, header kembali In Progress
        $firstApprover = User::findOrFail($approvers->first()->user_id);
        $this->actingAs($firstApprover)->postJson(route('updateInspectionGoodsApproval'), [
            'inspection_type' => 3, 'type' => 1,
            'transaction_code' => $detail->detail_inspection_code,
            'approval_insepection_goods_shift_id' => 2,
            'ig_remark' => 'Mohon periksa ulang wall thickness.',
        ])->assertOk();

        $detail->refresh();
        $header->refresh();
        $this->assertSame(0, (int) $detail->status);
        $this->assertSame(0, (int) $detail->lock);
        $this->assertSame(1, (int) $header->status);

        // perbaiki WT lalu lock ulang
        $this->actingAs($creator)->postJson(route('updateWallThickness'), [
            'id' => $wt->id,
            'dynamic_wt_1' => 5.15, 'dynamic_wt_2' => 5.2, 'dynamic_wt_3' => 5.1, 'dynamic_wt_4' => 5.0,
            'dynamic_wt_5' => 5.2, 'dynamic_wt_6' => 5.1, 'dynamic_wt_7' => 5.0, 'dynamic_wt_8' => 5.1,
        ])->assertOk();
        $this->postJson(route('lockInspectionGoods'), [
            'detail_inspection_code' => $detail->detail_inspection_code,
        ])->assertOk();

        // approve semua step -> shift Complete
        foreach ($approvers as $approver) {
            $this->actingAs(User::findOrFail($approver->user_id))->postJson(route('updateInspectionGoodsApproval'), [
                'inspection_type' => 3, 'type' => 1,
                'transaction_code' => $detail->detail_inspection_code,
                'approval_insepection_goods_shift_id' => 1,
                'ig_remark' => 'Approved by automated workflow test.',
            ])->assertOk();
        }

        $detail->refresh();
        $header->refresh();
        $this->assertSame(2, (int) $detail->status);
        // baru 1 dari 3 shift complete -> header masih Approval Progress
        $this->assertSame(2, (int) $header->status);

        // approval lanjutan setelah selesai ditolak
        $this->actingAs($firstApprover)->postJson(route('updateInspectionGoodsApproval'), [
            'inspection_type' => 3, 'type' => 1,
            'transaction_code' => $detail->detail_inspection_code,
            'approval_insepection_goods_shift_id' => 1,
            'ig_remark' => 'Should fail.',
        ])->assertStatus(422);
    }
}
