<?php
namespace App\Exports\Transaction\Inspection;

use App\Models\Transaction\Inspection\ResalesGood;
use App\Models\Transaction\Inspection\ResalesGoodDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResalesGoodsExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    protected $transaction_code;

    public function __construct($transaction_code)
    {
        $this->transaction_code = $transaction_code;
    }

    public function view(): View
    {
        $header = ResalesGood::with([
            'locationRelation', 'productRelation', 'supplierRelation',
            'produsenRelation', 'inspectorRelation', 'createdByRelation'
        ])->where('transaction_code', $this->transaction_code)->first();
        if (!$header) {
            return view('errors.404');
        }
        $details = ResalesGoodDetail::with(['productRelation', 'createdByRelation'])
            ->where('transaction_code', $this->transaction_code)
            ->orderBy('id', 'ASC')
            ->get();

        $inspectionTypeResales = 4;
        $check_approval = \App\Models\Transaction\Inspection\ApprovalHistory::where('transaction_code', $this->transaction_code)->first();
        $set_approval = \App\Models\Transaction\Inspection\ApprovalHistory::where('transaction_code', $this->transaction_code)->orderBy('step', 'ASC')->get();
        $approval = '';
        if ($check_approval !== null) {
            $approvalHeader = \App\Models\Setting\Approval\ApprovalHeader::where([
                'inspection_type'   => $inspectionTypeResales,
                'type'              => 1,
                'location'          => $header->location_id
            ])->first();
            if ($approvalHeader) {
                $approval = \App\Models\Setting\Approval\ApprovalDetail::with([
                    'userRelation'
                ])->where('approval_code', $approvalHeader->approval_code)->orderBy('step', 'ASC')->get();
            }
        }

        return view('report.inspection.report_resales_goods', [
            'header'       => $header,
            'details'      => $details,
            'approval'     => $approval,
            'set_approval' => $set_approval,
        ]);
    }

    public function title(): string
    {
        // PhpSpreadsheet melarang karakter / \ ? * [ ] : pada nama sheet (max 31 char)
        $safeCode = str_replace(['/', '\\', '?', '*', '[', ']', ':'], '-', $this->transaction_code);
        return substr('ResalesGoods ' . $safeCode, 0, 31);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1  => ['font' => ['bold' => true, 'size' => 18]],
        ];
    }
}
