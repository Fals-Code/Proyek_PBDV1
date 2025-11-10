<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1️⃣ Ambil ringkasan dashboard dari prosedur
        $summary = DB::select('CALL sp_ringkasan_dashboard()')[0];

        // 2️⃣ Ambil data grafik penjualan & pengadaan bulanan
        $penjualan = DB::table('v_penjualan_bulanan')->get();
        $pengadaan = DB::table('v_pengadaan_bulanan')->get();

        // 3️⃣ Ambil barang terlaris & hampir habis
        $terlaris = DB::table('v_barang_terlaris')->get();
        $hampirHabis = DB::table('v_barang_hampir_habis')->get();

        // 4️⃣ Ambil performa vendor (vendor aktif dan kontribusinya)
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
