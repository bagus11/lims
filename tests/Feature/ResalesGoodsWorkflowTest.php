<?php

namespace Tests\Feature;

use App\Models\Master\MasterProductResalesGood;
use App\Models\Master\Supplier;
use App\Models\Setting\Approval\ApprovalDetail;
use App\Models\Setting\Approval\ApprovalHeader;
use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\Transaction\Inspection\ResalesGood;
use App\Models\Transaction\Inspection\ResalesGoodDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Smoke test alur lengkap Resales Goods (inspection_type = 4):
 * create -> add detail -> lock -> revision -> lock ulang -> approve semua
 * step -> Done, plus guard locked-transaction dan IDOR lintas lokasi.
 * Mengikuti pola LaboratoryWorkflowTest: jalan di DB lokal, skip bila data
 * master/approval setting belum ada.
 */
class ResalesGoodsWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_complete_resales_goods_workflow_supports_revision_and_multi_step_approval()
    {
        $approvalHeader = ApprovalHeader::where('inspection_type', 4)->first();
        $supplier = Supplier::first();

        if (!$approvalHeader || !$supplier) {
            $this->markTestSkipped('Resales workflow smoke test requires a supplier and an approval setting for inspection_type 4.');
        }

        $approvers = ApprovalDetail::where('approval_code', $approvalHeader->approval_code)
            ->orderBy('step')
            ->get();

        if ($approvers->isEmpty()) {
            $this->markTestSkipped('Resales workflow smoke test requires at least one configured approver.');
        }

        $creator = User::find($approvers->first()->user_id);
        if (!$creator) {
            $this->markTestSkipped('Configured Resales approver user was not found.');
        }

        $product = MasterProductResalesGood::create([
            'name' => 'Automated Test Product ' . strtoupper(substr(sha1(uniqid('', true)), 0, 8)),
            'size' => 'DN 110',
            'spesification' => [
                ['symbol' => 'D', 'value' => '110.0'],
                ['symbol' => 'L', 'value' => '250'],
            ],
        ]);

        // non-GM dipaksa membuat transaksi di lokasinya sendiri — samakan
        // kode_kantor creator dengan lokasi approval setting (rollback oleh
        // DatabaseTransactions)
        $creator->kode_kantor = $approvalHeader->location;
        $creator->save();

        $this->actingAs($creator);

        $createResponse = $this->postJson(route('addResalesGoodsHeader'), [
            'location_id' => $approvalHeader->location,
            'product_id' => $product->id,
            'inspector_id' => $creator->id,
            'supplier_id' => $supplier->id,
            'produsen_id' => $supplier->id,
            'quantity' => 10,
            'type' => 'Fitting',
            'standard' => 'SNI TEST',
            'certificate' => 'CERT TEST',
        ])->assertOk();

        $transactionCode = $createResponse->json('data.transaction_code');
        $this->assertNotEmpty($transactionCode);

        $header = ResalesGood::where('transaction_code', $transactionCode)->firstOrFail();
        $this->assertSame(0, (int) $header->status);
        $this->assertEquals($approvalHeader->location, $header->location_id);

        // quantity <= 0 ditolak
        $this->postJson(route('addResalesGoodsHeader'), [
            'location_id' => $approvalHeader->location,
            'product_id' => $product->id,
            'inspector_id' => $creator->id,
            'supplier_id' => $supplier->id,
            'produsen_id' => $supplier->id,
            'quantity' => 0,
            'type' => 'Fitting',
        ])->assertStatus(422);

        $this->postJson(route('addResalesGoodsDetail'), [
            'transaction_code' => $transactionCode,
            'product_id' => $product->id,
            'spesification' => [
                ['symbol' => 'D', 'value' => '110.2'],
                ['symbol' => 'L', 'value' => '249'],
            ],
        ])->assertOk();

        $header->refresh();
        $this->assertSame(1, (int) $header->status);
        $detail = ResalesGoodDetail::where('transaction_code', $transactionCode)->firstOrFail();

        // lock tanpa no_pf ditolak
        $this->postJson(route('lockResalesGoods'), [
            'transaction_code' => $transactionCode,
        ])->assertStatus(422);

        $this->postJson(route('lockResalesGoods'), [
            'transaction_code' => $transactionCode,
            'no_pf' => 'PF-TEST-001',
            'keterangan' => 'Automated workflow test',
        ])->assertOk();

        $header->refresh();
        $this->assertSame(2, (int) $header->status);
        $this->assertSame(1, ApprovalHistory::where('transaction_code', $transactionCode)->where('inspection_type', 4)->count());

        // transaksi locked: edit/add/delete detail & delete transaksi ditolak
        $this->postJson(route('addResalesGoodsDetail'), [
            'transaction_code' => $transactionCode,
            'spesification' => [['symbol' => 'X', 'value' => '1']],
        ])->assertStatus(400);
        $this->postJson(route('editResalesGoodsDetail'), [
            'id' => $detail->id,
            'transaction_code' => $transactionCode,
            'spesification' => [['symbol' => 'D', 'value' => '999']],
        ])->assertStatus(400);
        $this->postJson(route('deleteResalesGoodsDetail'), ['id' => $detail->id])->assertStatus(400);
        $this->postJson(route('deleteResalesGoods'), ['transaction_code' => $transactionCode])->assertStatus(400);

        // Revision: kembali ke In Progress, bisa diedit & lock ulang
        $firstApprover = User::findOrFail($approvers->first()->user_id);
        $this->actingAs($firstApprover)->postJson(route('updateResalesGoodsApproval'), [
            'transaction_code' => $transactionCode,
            'decision' => 4,
            'remark' => 'Please re-check the measurement.',
        ])->assertOk();

        $header->refresh();
        $this->assertSame(1, (int) $header->status);

        $this->actingAs($creator)->postJson(route('editResalesGoodsDetail'), [
            'id' => $detail->id,
            'transaction_code' => $transactionCode,
            'spesification' => [['symbol' => 'D', 'value' => '110.1']],
        ])->assertOk();

        $this->postJson(route('lockResalesGoods'), [
            'transaction_code' => $transactionCode,
            'no_pf' => 'PF-TEST-001',
            'keterangan' => 'Resubmitted after revision',
        ])->assertOk();

        // Approve seluruh step sampai Done
        foreach ($approvers as $approver) {
            $this->actingAs(User::findOrFail($approver->user_id))->postJson(route('updateResalesGoodsApproval'), [
                'transaction_code' => $transactionCode,
                'decision' => 1,
                'remark' => 'Approved by automated workflow test.',
            ])->assertOk();
        }

        $header->refresh();
        $this->assertSame(3, (int) $header->status);

        // Approver bukan giliran aktif ditolak
        $this->actingAs($firstApprover)->postJson(route('updateResalesGoodsApproval'), [
            'transaction_code' => $transactionCode,
            'decision' => 1,
            'remark' => 'Should fail, already done.',
        ])->assertStatus(422);

        // IDOR: user lokasi lain tanpa permission GM tidak boleh menyentuh
        $outsider = User::where('kode_kantor', '!=', $header->location_id)
            ->whereNotIn('id', $approvers->pluck('user_id'))
            ->first();
        if ($outsider && !$outsider->hasPermissionSafe('get-only_gm-inspection-goods')) {
            $this->actingAs($outsider);
            $this->getJson(route('getResalesGoodApprovalDetail', ['transaction_code' => $transactionCode]))
                ->assertStatus(403);
            $this->postJson(route('deleteResalesGoods'), ['transaction_code' => $transactionCode])
                ->assertStatus(403);
        }
    }
}
