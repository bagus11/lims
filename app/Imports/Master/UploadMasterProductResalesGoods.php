<?php

namespace App\Imports\Master;

use App\Models\Master\MasterProductResalesGood;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UploadMasterProductResalesGoods implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    public function model(array $row)
    {
        $name = trim($row['name'] ?? '');
        if ($name == '') {
            return null;
        }

        $size = trim($row['size'] ?? '');

        $spesification = [];
        for ($i = 1; $i <= 10; $i++) {
            $symbol = trim($row['spec_' . $i . '_symbol'] ?? '');
            $value = trim($row['spec_' . $i . '_value'] ?? '');
            if ($symbol != '' || $value != '') {
                $spesification[] = [
                    'symbol' => $symbol,
                    'value'  => $value,
                ];
            }
        }

        $existing = MasterProductResalesGood::where([
            'name' => $name,
            'size' => $size,
        ])->first();

        if ($existing) {
            $existing->update([
                'spesification' => $spesification,
            ]);
            return null;
        }

        return new MasterProductResalesGood([
            'name' => $name,
            'size' => $size,
            'spesification' => $spesification,
        ]);
    }
}
