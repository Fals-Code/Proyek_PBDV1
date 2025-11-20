<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        // Filter dari dropdown
        $filterJenis = $request->get('jenis', 'all');

        // Ambil data stok dari VIEW
        $query = DB::table('view_stok_barang');

        // Jika filter jenis dipilih
        if ($filterJenis !== 'all') {
            $query->where('nama_jenis', $filterJenis);
        }

        $stokBarang = $query->orderBy('nama_barang', 'asc')->get();

        // Dropdown jenis barang
        $jenisBarang = DB::table('jenis_barang')->pluck('nama_jenis');

        return view('stok.index', compact('stokBarang', 'jenisBarang', 'filterJenis'));
    }

    public function detail($idbarang)
    {
        // Informasi barang tetap dari tabel barang
        $barang = DB::table('barang as b')
            ->leftJoin('satuan as s', 'b.idsatuan', '=', 's.idsatuan')
            ->leftJoin('jenis_barang as j', 'b.idjenis', '=', 'j.idjenis')
            ->select('b.*', 's.nama_satuan', 'j.nama_jenis')
            ->where('b.idbarang', $idbarang)
            ->first();

        if (!$barang) {
            return redirect()->route('stok.index')->with('error', 'Barang tidak ditemukan.');
        }

        // Riwayat kartu stok ambil dari VIEW
        $kartuStok = DB::table('view_kartu_stok_detail')
            ->where('idbarang', $idbarang)
            ->orderBy('created_at', 'desc')
            ->orderBy('idkartu_stok', 'desc')
            ->get();

        // Stok akhir dari VIEW
        $stokAkhir = DB::table('view_stok_akhir')
            ->where('idbarang', $idbarang)
            ->first();

        return view('stok.detail', compact('barang', 'kartuStok', 'stokAkhir'));
    }
}
