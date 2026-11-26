<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MasterProductResalesGoodsFormatExport implements FromArray, WithHeadings, WithTitle
{
    const MAX_SPEC = 10;

    public function headings(): array
    {
        return self::formatHeadings();
    }

    public function array(): array
    {
        return self::formatRows();
    }

    public function title(): string
    {
        return 'format_master';
    }

    /**
     * Dipakai bersama oleh unduhan xlsx dan csv supaya kedua format tidak
     * pernah berbeda kolom.
     */
    public static function formatHeadings(): array
    {
        $headings = ['name', 'size', 'uom'];
        for ($i = 1; $i <= self::MAX_SPEC; $i++) {
            $headings[] = 'spec_' . $i . '_symbol';
            $headings[] = 'spec_' . $i . '_value';
            $headings[] = 'spec_' . $i . '_tolerance';
            $headings[] = 'spec_' . $i . '_min';
            $headings[] = 'spec_' . $i . '_max';
        }
        return $headings;
    }

    public static function formatRows(): array
    {
        // Contoh 1 memakai gaya "nilai ± toleransi"; simbol K memperlihatkan
        // gaya rentang (min s/d max) dengan nilai & toleransi dikosongkan.
        $sample = [
            ['A', '238', '1.4', '236.6', '239.4'],
            ['B', '254.6', '1.5', '253.1', '256.1'],
            ['C', '283.7', '1.7', '282', '285.4'],
            ['D', '15.8', '0.1', '15.7', '15.9'],
            ['E', '1.5', '0.1', '1.4', '1.6'],
            ['F', '7', '0.1', '6.9', '7.1'],
            ['G', '26.8', '0.2', '26.6', '27'],
            ['H', '29.4', '0.2', '29.2', '29.6'],
            ['K', '', '', '55', '58'],
        ];

        $socket = [
            ['D', '32.0', '0.3', '31.7', '32.3'],
            ['d', '25.0', '0.3', '24.7', '25.3'],
            ['L', '48', '1', '47', '49'],
            ['T', '2.0', '0.1', '1.9', '2.1'],
        ];

        return [
            self::buildRow('Rubber Ring 250 MM', 'DN 250', 'mm', $sample),
            self::buildRow('Socket PVC 1 Inch', '1 Inch', 'mm', $socket),
        ];
    }

    private static function buildRow(string $name, string $size, string $uom, array $specs): array
    {
        $row = [$name, $size, $uom];
        for ($i = 0; $i < self::MAX_SPEC; $i++) {
            $spec = $specs[$i] ?? ['', '', '', '', ''];
            foreach ($spec as $cell) {
                $row[] = $cell;
            }
        }
        return $row;
    }
}
