<?php

return [
    /*
     * Jumlah shift laboratorium yang bisa dipilih di form New Test.
     * Ubah lewat LIMS_LAB_SHIFTS di .env (lalu `php artisan config:clear`).
     * Catatan: setiap shift butuh chain approval Lab sendiri (Setting ->
     * Approval, per lokasi per shift) agar transaksinya bisa disubmit.
     */
    'lab_shifts' => max(1, (int) env('LIMS_LAB_SHIFTS', 3)),
];
