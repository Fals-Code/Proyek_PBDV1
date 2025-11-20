<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = DB::select('CALL sp_ringkasan_dashboard()')[0];

        $penjualan = DB::table('v_penjualan_bulanan')->get();
        $pengadaan = DB::table('v_pengadaan_bulanan')->get();

        $terlaris = DB::table('v_barang_terlaris')->get();
        $hampirHabis = DB::table('v_barang_hampir_habis')->get();

        $vendor = DB::table('v_performa_vendor')->get();

        return view('dashboard.index', compact(
            'summary',
            'penjualan',
            'pengadaan',
            'terlaris',
            'hampirHabis',
            'vendor'
        ));
    }
}
