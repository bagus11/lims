<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MasterProductResalesGoodsFormatExport implements FromArray, WithHeadings, WithTitle
{
    public function headings(): array
    {
        return [
            'name',
            'size',
            'spec_1_symbol', 'spec_1_value',
            'spec_2_symbol', 'spec_2_value',
            'spec_3_symbol', 'spec_3_value',
            'spec_4_symbol', 'spec_4_value',
            'spec_5_symbol', 'spec_5_value',
            'spec_6_symbol', 'spec_6_value',
            'spec_7_symbol', 'spec_7_value',
            'spec_8_symbol', 'spec_8_value',
            'spec_9_symbol', 'spec_9_value',
            'spec_10_symbol', 'spec_10_value',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Fitting D 200 mm',
                'DN 200',
                'D', '201.0',
                'd', '160.3',
                'L', '405',
                'Z', '91',
                'H', '250',
                '', '',
                '', '',
                '', '',
                '', '',
                '', '',
            ],
            [
                'Socket PVC 1 Inch',
                '1 Inch',
                'D', '32.0',
                'd', '25.0',
                'L', '48',
                'T', '2.0',
                '', '',
                '', '',
                '', '',
                '', '',
                '', '',
                '', '',
            ],
        ];
    }

    public function title(): string
    {
        return 'format_master';
    }
}
