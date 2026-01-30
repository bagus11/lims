<?php

namespace App\Imports\Master;

use App\Models\Master\LogMasterProduct;
use App\Models\Master\MasterProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UploadMasterProduct implements ToModel, WithHeadingRow, WithCalculatedFormulas
{

    public function model(array $row)
    {
        // Convert all values to uppercase and handle missing keys
        $seri = strtoupper($row['seri'] ?? '');
        $join = strtoupper($row['join'] ?? '');
        $ukuran = strtoupper($row['ukuran'] ?? '');
        $marking = strtoupper($row['marking'] ?? '');
        $berat = strtoupper($row['berat'] ?? '');
        $berat_pcs = strtoupper($row['berat_pcs'] ?? '');
        $kode_ts = strtoupper($row['kode_ts'] ?? '');
        $kode_ts_2 = strtoupper($row['kode_ts_2'] ?? '');
        $kode_be = strtoupper($row['kode_be'] ?? '');
        $kode_be_2 = strtoupper($row['kode_be_2'] ?? '');
        $marking_2 = strtoupper($row['marking_2'] ?? '');
        $formula = strtoupper($row['formula'] ?? '');
        $min_od = strtoupper($row['min_od'] ?? '');
        $toleransi_od = strtoupper($row['toleransi_od'] ?? '');
        $marking_od = strtoupper($row['marking_od'] ?? '');
        $avg_od = strtoupper($row['avg_od'] ?? '');
        $max_od = strtoupper($row['max_od'] ?? '');
        $min_wt = strtoupper($row['min_wt'] ?? '');
        $toleransi_wt = strtoupper($row['toleransi_wt'] ?? '');
        $marking_wt = strtoupper($row['marking_wt'] ?? '');
        $avg_wt = strtoupper($row['avg_wt'] ?? '');
        $max_wt = strtoupper($row['max_wt'] ?? '');

        // Check if a record with the same seri, join, ukuran, marking exists
        $existingProduct = MasterProduct::where([
            'seri' => $seri,
            'join' => $join,
            'ukuran' => $ukuran,
            'marking' => $marking,
        ])->first();

        if ($existingProduct) {
            $existingProduct->delete();
        }
        $insert = LogMasterProduct::insert([[
            'seri' => $seri,
            'join' => $join,
            'ukuran' => $ukuran,
            'marking' => $marking,
            'berat_pcs' => $berat_pcs,
            'berat' => $berat,
            'kode_ts' => $kode_ts . ' ' . $kode_ts_2,
            'kode_be' => $kode_be . ' ' . $kode_be_2,
            'marking_2' => $marking_2,
            'formula' => $formula,
            'min_od' => $min_od,
            'toleransi_od' => $toleransi_od,
            'marking_od' => $marking_od,
            'avg_od' => $avg_od,
            'max_od' => $max_od,
            'min_wt' => $min_wt,
            'toleransi_wt' => $toleransi_wt,
            'marking_wt' => $marking_wt,
            'avg_wt' => $avg_wt,
            'max_wt' => $max_wt,
            'user_id' => auth()->user()->id,
            'created_at'=>date('Y-m-d H:i:s')
        ]]);
        // Create new product record
        return new MasterProduct([
            'seri' => $seri,
            'join' => $join,
            'ukuran' => $ukuran,
            'marking' => $marking,
            'berat_pcs' => $berat_pcs,
            'berat' => $berat,
            'kode_ts' => $kode_ts . ' ' . $kode_ts_2,
            'kode_be' => $kode_be . ' ' . $kode_be_2,
            'marking_2' => $marking_2,
            'formula' => $formula,
            'min_od' => $min_od,
            'toleransi_od' => $toleransi_od,
            'marking_od' => $marking_od,
            'avg_od' => $avg_od,
            'max_od' => $max_od,
            'min_wt' => $min_wt,
            'toleransi_wt' => $toleransi_wt,
            'marking_wt' => $marking_wt,
            'avg_wt' => $avg_wt,
            'max_wt' => $max_wt,
            'user_id' => auth()->user()->id,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
    }
}
