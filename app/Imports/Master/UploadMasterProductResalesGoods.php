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
        $uom = trim($row['uom'] ?? '');

        $existing = MasterProductResalesGood::where([
            'name' => $name,
            'size' => $size,
        ])->first();

        $spesification = $this->buildSpesification($row, $existing);

        if ($existing) {
            $existing->update([
                'uom' => $uom != '' ? $uom : $existing->uom,
                'spesification' => $spesification,
            ]);
            return null;
        }

        return new MasterProductResalesGood([
            'name' => $name,
            'size' => $size,
            'uom' => $uom != '' ? $uom : null,
            'spesification' => $spesification,
        ]);
    }

    private function buildSpesification(array $row, $existing)
    {
        $previous = $this->previousBySymbol($existing);
        $spesification = [];

        for ($i = 1; $i <= 10; $i++) {
            $symbol    = trim($row['spec_' . $i . '_symbol'] ?? '');
            $value     = trim($row['spec_' . $i . '_value'] ?? '');
            $tolerance = trim($row['spec_' . $i . '_tolerance'] ?? '');
            $min       = trim($row['spec_' . $i . '_min'] ?? '');
            $max       = trim($row['spec_' . $i . '_max'] ?? '');

            if ($symbol == '' && $value == '' && $tolerance == '' && $min == '' && $max == '') {
                continue;
            }

            // File format lama hanya punya symbol & value. Tanpa ini, impor ulang
            // akan menghapus toleransi yang sudah diisi lewat menu master.
            if ($tolerance == '' && $min == '' && $max == '' && isset($previous[$symbol])) {
                $tolerance = $previous[$symbol]['tolerance'];
                $min       = $previous[$symbol]['min'];
                $max       = $previous[$symbol]['max'];
            }

            if ($min == '' && $max == '' && is_numeric($value) && is_numeric($tolerance)) {
                $min = (string) ($value - $tolerance);
                $max = (string) ($value + $tolerance);
            }

            $spesification[] = [
                'symbol'    => $symbol,
                'value'     => $value,
                'tolerance' => $tolerance,
                'min'       => $min,
                'max'       => $max,
            ];
        }

        return $spesification;
    }

    private function previousBySymbol($existing)
    {
        $map = [];
        if (!$existing || !is_array($existing->spesification)) {
            return $map;
        }

        foreach ($existing->spesification as $spec) {
            if (!is_array($spec) || !isset($spec['symbol'])) {
                continue;
            }
            $map[trim($spec['symbol'])] = [
                'tolerance' => trim($spec['tolerance'] ?? ''),
                'min'       => trim($spec['min'] ?? ''),
                'max'       => trim($spec['max'] ?? ''),
            ];
        }

        return $map;
    }
}
