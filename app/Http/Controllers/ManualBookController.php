<?php

namespace App\Http\Controllers;

class ManualBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Manual penggunaan seluruh modul LIMS, disusun mengikuti urutan kerja:
     * pengaturan akses, lalu data master, baru transaksi harian.
     */
    public function index()
    {
        return view('manual.manual-book');
    }
}
