<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        // Filter jenis dari dropdown
        $filterJenis = $request->get('jenis', 'all');

        // Ambil stok dari VIEW
        $query = DB::table('view_stok_barang');

        // Filter jika user memilih jenis tertentu
        if ($filterJenis !== 'all') {
            $query->where('nama_jenis', $filterJenis);
        }

        $stokBarang = $query->orderBy('nama_barang', 'asc')->get();

        // Dropdown jenis berdasarkan kolom 'jenis' di tabel barang
        // lalu di-convert ke label agar sama dengan nama_jenis di view
        $jenisMapping = [
            'S' => 'Sembako',
            'M' => 'Minuman',
            'N' => 'Snack',
            'E' => 'Elektronik',
            'D' => 'Peralatan Dapur',
        ];

        $jenisBarang = DB::table('barang')
            ->select('jenis')
            ->distinct()
            ->get()
            ->map(function ($row) use ($jenisMapping) {
                return $jenisMapping[$row->jenis] ?? 'Tidak diketahui';
            });

        return view('stok.index', compact('stokBarang', 'jenisBarang', 'filterJenis'));
    }

    public function detail($idbarang)
    {
        // Ambil data barang tanpa JOIN jenis_barang (karena tabelnya sudah tidak ada)
        $barang = DB::table('barang as b')
            ->leftJoin('satuan as s', 'b.idsatuan', '=', 's.idsatuan')
            ->select(
                'b.*',
                's.nama_satuan',
                DB::raw("
                    CASE b.jenis
                        WHEN 'S' THEN 'Sembako'
                        WHEN 'M' THEN 'Minuman'
                        WHEN 'N' THEN 'Snack'
                        WHEN 'E' THEN 'Elektronik'
                        WHEN 'D' THEN 'Peralatan Dapur'
                        ELSE 'Tidak diketahui'
                    END AS nama_jenis
                ")
            )
            ->where('b.idbarang', $idbarang)
            ->first();

        if (!$barang) {
            return redirect()->route('stok.index')->with('error', 'Barang tidak ditemukan.');
        }

        // Riwayat kartu stok dari VIEW
        $kartuStok = DB::table('view_kartu_stok_detail')
            ->where('idbarang', $idbarang)
            ->get();

        // Stok akhir dari VIEW
        $stokAkhir = DB::table('view_stok_akhir')
            ->where('idbarang', $idbarang)
            ->first();

        return view('stok.detail', compact('barang', 'kartuStok', 'stokAkhir'));
    }
}
