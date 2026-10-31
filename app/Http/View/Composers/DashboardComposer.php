<?php

namespace App\Http\View\Composers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardComposer
{
    /**
     * Sisipkan data chart dinamis dashboard ke view 'dashboard' sehingga
     * berlaku untuk semua entry point (/, /home).
     */
    public function compose(View $view)
    {
        $view->with('charts', $this->chartData(auth()->id()));
    }

    /**
     * Data chart dashboard, otomatis menyesuaikan role: hanya modul transaksi
     * yang permission-nya dimiliki user yang ikut dihitung.
     *  - modules : komposisi jumlah transaksi per modul (doughnut)
     *  - trend   : total transaksi 6 bulan terakhir, dijumlah antar modul (line)
     */
    private function chartData($userId)
    {
        $empty = ['modules' => [], 'trend' => ['labels' => [], 'data' => []]];
        if (!$userId) {
            return $empty;
        }

        $perms = DB::table('permissions')
            ->join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'role_has_permissions.role_id')
            ->where('model_has_roles.model_id', $userId)
            ->distinct()
            ->pluck('permissions.name')
            ->all();

        $modules = [
            ['perm' => 'view-inspection-material',      'label' => 'Inspection Material', 'table' => 'inspection_materials', 'color' => '#179bae', 'creator' => 'user_id',    'location' => 'location'],
            ['perm' => 'view-inspection-goods',         'label' => 'Inspection Goods',    'table' => 'inspection_goods',     'color' => '#2563eb', 'creator' => 'user_id',    'location' => 'location_id'],
            ['perm' => 'view-inspection-resales-goods', 'label' => 'Resales Goods',       'table' => 'resales_goods',        'color' => '#d97706', 'creator' => 'created_by', 'location' => 'location_id'],
            ['perm' => 'view-lab',                      'label' => 'Lab',                 'table' => 'laboratory_headers',   'color' => '#15803d', 'creator' => 'user_id',    'location' => 'location_id'],
        ];
        $accessible = array_values(array_filter($modules, fn ($m) => in_array($m['perm'], $perms, true)));

        // Scope angka chart mengikuti scope list transaksi: Super Admin semua,
        // Admin per-lokasi, User hanya miliknya.
        $user = auth()->user();
        $scope = $user ? $user->transactionScope() : 'own';
        $applyScope = function ($query, array $m) use ($scope, $user) {
            if ($scope === 'location') {
                $query->where($m['location'], $user->kode_kantor);
            } elseif ($scope === 'own') {
                $query->where($m['creator'], $user->id);
            }

            return $query;
        };

        // Bucket 6 bulan terakhir (termasuk bulan berjalan).
        $start = Carbon::now()->startOfMonth()->subMonths(5);
        $buckets = [];
        $labels = [];
        for ($i = 0; $i < 6; $i++) {
            $month = (clone $start)->addMonths($i);
            $buckets[$month->format('Y-m')] = 0;
            $labels[] = $month->translatedFormat('M Y');
        }

        $slices = [];
        foreach ($accessible as $m) {
            $value = (int) $applyScope(DB::table($m['table']), $m)->count();
            if ($value > 0) {
                $slices[] = [
                    'label' => $m['label'],
                    'value' => $value,
                    'color' => $m['color'],
                ];
            }

            $rows = $applyScope(DB::table($m['table']), $m)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS c")
                ->where('created_at', '>=', $start)
                ->groupBy('ym')
                ->pluck('c', 'ym');

            foreach ($rows as $ym => $c) {
                if (array_key_exists($ym, $buckets)) {
                    $buckets[$ym] += (int) $c;
                }
            }
        }

        return [
            'modules' => $slices,
            'trend'   => [
                'labels' => $labels,
                'data'   => array_values($buckets),
            ],
        ];
    }
}
