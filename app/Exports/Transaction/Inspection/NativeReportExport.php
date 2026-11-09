<?php

namespace App\Exports\Transaction\Inspection;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Dasar bersama export report per-transaksi yang dibangun cell-per-cell.
 * Subclass mengisi $rows lewat row() dan mencatat gaya lewat merge()/bold()/
 * border()/center()/fill(); AfterSheet menerapkannya sekali jalan. Pendekatan
 * ini menggantikan FromView+HTML reader yang berantakan dan gagal DOM di server.
 */
abstract class NativeReportExport implements FromArray, WithEvents, WithTitle
{
    protected $rows = [];
    protected $ops = [];

    /** Subclass mengisi $this->rows + ops; dipanggil sekali dari array(). */
    abstract protected function build(): void;

    public function array(): array
    {
        $this->rows = [];
        $this->ops = [];
        $this->build();

        return $this->rows;
    }

    /** Tambah satu baris; kembalikan nomor baris spreadsheet-nya (1-based). */
    protected function row(array $cells): int
    {
        $this->rows[] = $cells;

        return count($this->rows);
    }

    protected function currentRow(): int
    {
        return count($this->rows);
    }

    protected function merge(string $range): void { $this->ops[] = ['merge', $range]; }
    protected function bold(string $range): void { $this->ops[] = ['bold', $range]; }
    protected function border(string $range): void { $this->ops[] = ['border', $range]; }
    protected function center(string $range): void { $this->ops[] = ['center', $range]; }
    protected function wrap(string $range): void { $this->ops[] = ['wrap', $range]; }
    protected function fill(string $range, string $argb = 'FFEEEEEE'): void { $this->ops[] = ['fill', $range, $argb]; }
    protected function fontSize(string $range, int $size): void { $this->ops[] = ['size', $range, $size]; }
    protected function rowHeight(int $row, int $height): void { $this->ops[] = ['height', $row, $height]; }
    protected function colWidth(string $col, int $width): void { $this->ops[] = ['width', $col, $width]; }

    /** Baris pasangan label: nilai dua kolom gaya form (B..C dan E..F). */
    protected function kvRow($label1, $value1, $label2 = null, $value2 = null): int
    {
        return $this->row([$label1, ': ' . ($value1 !== null && $value1 !== '' ? $value1 : '-'),
            null, $label2, $label2 !== null ? ': ' . ($value2 !== null && $value2 !== '' ? $value2 : '-') : null, null]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $thin = ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']];
                foreach ($this->ops as $op) {
                    switch ($op[0]) {
                        case 'merge': $sheet->mergeCells($op[1]); break;
                        case 'bold': $sheet->getStyle($op[1])->getFont()->setBold(true); break;
                        case 'border': $sheet->getStyle($op[1])->applyFromArray(['borders' => ['allBorders' => $thin]]); break;
                        case 'center': $sheet->getStyle($op[1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER); break;
                        case 'wrap': $sheet->getStyle($op[1])->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_TOP); break;
                        case 'fill': $sheet->getStyle($op[1])->applyFromArray(['fill' => ['fillType' => 'solid', 'startColor' => ['argb' => $op[2]]]]); break;
                        case 'size': $sheet->getStyle($op[1])->getFont()->setSize($op[2]); break;
                        case 'height': $sheet->getRowDimension($op[1])->setRowHeight($op[2]); break;
                        case 'width': $sheet->getColumnDimension($op[1])->setWidth($op[2]); break;
                    }
                }
            },
        ];
    }
}
