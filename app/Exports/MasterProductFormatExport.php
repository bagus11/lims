<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MasterProductFormatExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'seri',
            'join',
            'ukuran',
            'marking',
            'berat_pcs',
            'berat',
            'kode_ts',
            'kode_ts_2',
            'kode_be',
            'kode_be_2',
            'marking_2',
            'formula',
            'min_od',
            'toleransi_od',
            'marking_od',
            'avg_od',
            'max_od',
            'min_wt',
            'toleransi_wt',
            'marking_wt',
            'avg_wt',
            'max_wt',
        ];
    }

    public function array(): array
    {
        return [
            [
                'PVC 1"',
                'SOCKET',
                '32 MM',
                'A',
                '0.5',
                '1.2',
                'TS-001',
                'REV A',
                'BE-100',
                'REV B',
                'MARKING A',
                'A + B',
                '31.8',
                '0.2',
                '31.8 ± 0.2',
                '31.9',
                '32.0',
                '2.0',
                '0.1',
                '2.0 ± 0.1',
                '2.0',
                '2.1',
            ],
            [
                'PVC 2"',
                'SPIGOT',
                '63 MM',
                'B',
                '1.8',
                '3.5',
                'TS-002',
                '',
                'BE-200',
                '',
                'MARKING B',
                'C + D',
                '62.5',
                '0.3',
                '62.5 ± 0.3',
                '62.7',
                '62.9',
                '3.0',
                '0.2',
                '3.0 ± 0.2',
                '3.1',
                '3.2',
            ],
        ];
    }

    public function title(): string
    {
        return 'format_master_product';
    }
}
