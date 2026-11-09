<?php

namespace App\Exports\Transaction\Inspection;

use App\Http\Controllers\Transaction\Inspection\LaboratoryController;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Versi spreadsheet dari form "Record of Laboratory Testing" 03/QC/12-1999.
 * Dibangun cell-per-cell (bukan konversi HTML) supaya hasilnya rapi dan tidak
 * tergantung pembacaan DOM yang gagal di server.
 */
class LaboratoryReportExport implements FromArray, WithTitle, WithEvents, WithColumnWidths
{
    protected $laboratoryCode;

    /** @var array hasil buildLaboratoryReport, diisi saat array() dipanggil */
    protected $report;

    /** @var array posisi baris penting untuk styling di AfterSheet */
    protected $marks = [];

    public function __construct($laboratoryCode)
    {
        $this->laboratoryCode = $laboratoryCode;
    }

    public function title(): string
    {
        return 'Laboratory Report';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 22, 'C' => 38, 'D' => 18, 'E' => 26, 'F' => 16, 'G' => 14];
    }

    protected function tick($checked)
    {
        return ($checked ? '[X] ' : '[  ] ');
    }

    public function array(): array
    {
        $this->report = app(LaboratoryController::class)->buildLaboratoryReport($this->laboratoryCode);
        $header = $this->report['header'];
        $history = $this->report['history'];

        $months = [1 => 'JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
        $testDate = $header->test_date
            ? $header->test_date->format('j') . ' ' . $months[(int) $header->test_date->format('n')] . ' ' . $header->test_date->format('Y')
            : '-';

        $romanShift = [1 => 'I', 2 => 'II', 3 => 'III'];
        $shiftTempHumd = array_filter([
            $header->shift ? ($romanShift[(int) $header->shift] ?? $header->shift) : null,
            $header->temperature,
            $header->humidity,
        ]);

        $product = $header->productRelation;
        $specification = $product ? trim($product->seri . ' ' . $product->ukuran . ' ' . $product->marking) : '-';

        $methodCode = optional(optional($header->productMethodRelation)->methodRelation)->code ?: data_get($header->method_snapshot, 'code');
        $methodApplied = strtoupper((string) $methodCode);

        $productType = $header->product_type === 'MS K 6741' ? 'JIS K 6741' : $header->product_type;
        $typeTicks = [];
        foreach (['Pralon', 'PE Pipe', 'JIS K 6741', 'Telkom', 'Fitting', 'Electric'] as $type) {
            $typeTicks[] = $this->tick($productType === $type) . $type;
        }

        $rows = [];
        $rows[] = ['PRALON', 'REPORT NO : ' . ($header->report_no ?: '-'), 'RECORD OF LABORATORY TESTING', null, null, 'ISO 9001', 'KAN'];
        $rows[] = [null, null, $header->laboratory_code, null, null, null, null];
        $rows[] = ['I   TESTING CONDITION'];
        $rows[] = [null, 'Date OF Tested', ': ' . $testDate, null, 'Location', ': ' . (optional($header->locationRelation)->name ?: '-'), null];
        $rows[] = ['II   SAMPLE TESTED'];
        $rows[] = [null, 'Production code Number', ': ' . ($header->production_code_number ?: '-'), null, 'Shift / Temp. / Humd.', ': ' . ($shiftTempHumd ? implode(' / ', $shiftTempHumd) : '-'), null];
        $rows[] = [null, 'Material Lot Number/Formula', ': ' . ($header->material_lot_number ?: '-'), null, 'Machine Number', ': ' . (optional($header->machineRelation)->no_machine ?: '-'), null];
        $rows[] = [null, 'Specification Of Pipe Sample', ': ' . $specification, null, null, null, null];
        $rows[] = [null, 'Customer', ': ' . ($header->customer_name ?: '-'), null, 'Proyek', ': ' . ($header->project_name ?: '-'), null];
        $rows[] = [null, 'Dibuat Untuk', ': ' . ($header->made_for ?: '-'), null, null, null, null];
        $rows[] = ['III   TEST RESULT :'];
        $rows[] = [null, 'Test Method Aplied', $this->tick(str_contains($methodApplied, 'SNI')) . 'SNI.06-0084-2002   ' . $this->tick(str_contains($methodApplied, 'ISO')) . 'ISO', null, implode('   ', array_slice($typeTicks, 0, 3)), null, null];
        $rows[] = [null, null, null, null, implode('   ', array_slice($typeTicks, 3)), null, null];

        $this->marks['table_header'] = count($rows) + 1;
        $rows[] = ['NO', 'ITEM', 'SPECIES', 'SPECIFICATION', 'FIGURE MEASURED', 'CHECKER', null];
        $rows[] = [null, null, null, null, null, 'INCHARGE', 'EVALUATION'];

        $this->marks['data_start'] = count($rows) + 1;
        foreach ($this->report['rows'] as $row) {
            $figure = '';
            if (count($row['measurements']) > 1) {
                $values = array_map(function ($value) {
                    return rtrim(rtrim(number_format((float) $value, 4, '.', ''), '0'), '.');
                }, $row['measurements']);
                $figure = implode("\n", $values) . "\nAverage : " . $row['figure'] . ($row['unit'] ? ' ' . $row['unit'] : '');
            } else {
                $figure = $row['figure'] . ($row['unit'] && $row['figure'] !== '-' ? ' ' . $row['unit'] : '');
            }

            $rows[] = [
                $row['sequence'],
                $row['item'],
                $row['species'],
                $row['specification'],
                $figure,
                $row['in_charge'] ?: '-',
                $row['evaluation'] === 'GOOD' ? 'Good' : ($row['evaluation'] ? 'No Good' : '-'),
            ];
        }
        $this->marks['data_end'] = count($rows);

        $rows[] = ['IV   COMMENT :', null, $header->comment ?: '', null, null, null, null];
        $this->marks['comment'] = count($rows);

        // slot tanda tangan sama dengan PDF: nama pengisi dari siklus approval terakhir
        $approvers = $history->where('status', 1)->pluck('userRelation.name')->filter()->values();
        $this->marks['signature_label'] = count($rows) + 1;
        $rows[] = ['V  FACTORY MGR.', null, 'VI. QC. Chift', 'VII. Reporter', null, 'VIII. Karu', null];
        $rows[] = [$approvers->get(0) ?: '', null, $approvers->get(1) ?: '', optional($header->userRelation)->name ?: '', null, $approvers->get(2) ?: '', null];
        $this->marks['signature_name'] = count($rows);

        $rows[] = ['03/QC/12-1999 Rev-00', null, null, null, null, 'Dicetak otomatis oleh LIMS PT Pralon · ' . date('d/m/Y H:i'), null];
        $this->marks['footer'] = count($rows);

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $m = $this->marks;
                $thin = ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']];

                // judul
                $sheet->mergeCells('C1:E1');
                $sheet->mergeCells('C2:E2');
                $sheet->getStyle('A1:G2')->getFont()->setBold(true);
                $sheet->getStyle('C1:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C1')->getFont()->setSize(13);

                // judul section I/II/III/IV tebal
                foreach ([3, 5, 11, $m['comment']] as $sectionRow) {
                    $sheet->getStyle('A' . $sectionRow)->getFont()->setBold(true);
                }

                // header tabel: merge CHECKER + rowspan kolom lain
                $h = $m['table_header'];
                $sheet->mergeCells("F{$h}:G{$h}");
                foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                    $sheet->mergeCells("{$col}{$h}:{$col}" . ($h + 1));
                }
                $sheet->getStyle("A{$h}:G" . ($h + 1))->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFEEEEEE']],
                ]);

                // isi tabel: border penuh + wrap
                $sheet->getStyle("A{$h}:G{$m['data_end']}")->applyFromArray(['borders' => ['allBorders' => $thin]]);
                $sheet->getStyle("A{$m['data_start']}:G{$m['data_end']}")
                    ->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle("A{$m['data_start']}:A{$m['data_end']}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("E{$m['data_start']}:G{$m['data_end']}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // komentar: merge sel isi
                $sheet->mergeCells("C{$m['comment']}:G{$m['comment']}");
                $sheet->getStyle("A{$m['comment']}:G{$m['comment']}")->applyFromArray(['borders' => ['allBorders' => $thin]]);

                // tanda tangan: label tebal, nama di bawahnya dengan garis atas
                $sheet->getStyle("A{$m['signature_label']}:G{$m['signature_label']}")->getFont()->setBold(true);
                $sheet->getRowDimension($m['signature_name'])->setRowHeight(46);
                $sheet->getStyle("A{$m['signature_name']}:G{$m['signature_name']}")
                    ->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM)->setHorizontal(Alignment::HORIZONTAL_CENTER);
                foreach (['A', 'C', 'D', 'F'] as $col) {
                    $sheet->getStyle($col . $m['signature_name'])->applyFromArray(['borders' => ['bottom' => $thin]]);
                }

                // footer kecil
                $sheet->getStyle("A{$m['footer']}:G{$m['footer']}")->getFont()->setSize(8);
            },
        ];
    }
}
