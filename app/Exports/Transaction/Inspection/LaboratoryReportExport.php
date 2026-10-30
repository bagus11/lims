<?php

namespace App\Exports\Transaction\Inspection;

use App\Http\Controllers\Transaction\Inspection\LaboratoryController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Spreadsheet twin of the printed Record of Laboratory Testing. It renders the
 * very same blade as the PDF so the two layouts cannot drift apart; the $excel
 * flag only strips what PhpSpreadsheet's HTML reader cannot handle.
 */
class LaboratoryReportExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $laboratoryCode;

    public function __construct($laboratoryCode)
    {
        $this->laboratoryCode = $laboratoryCode;
    }

    public function view(): View
    {
        $report = app(LaboratoryController::class)->buildLaboratoryReport($this->laboratoryCode);
        $report['excel'] = true;

        return view('report.inspection.report_laboratory', $report);
    }

    public function title(): string
    {
        return 'Laboratory Report';
    }
}
