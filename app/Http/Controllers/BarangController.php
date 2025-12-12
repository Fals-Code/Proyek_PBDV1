<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $filterJenis = $request->get('jenis', 'semua');

        # Pilih view berdasarkan status
        if ($status === 'aktif') {
            $query = DB::table('v_barang_aktif');
        } elseif ($status === 'nonaktif') {
            $query = DB::table('v_barang_nonaktif');
        } else {
            $query = DB::table('v_master_barang');
        }

        # Filter jenis → gunakan kode_jenis dari view
        if ($filterJenis !== 'semua') {
            $query->where('kode_jenis', $filterJenis);
        }

        $barang = $query->orderBy('nama_barang', 'asc')->get();

        # Mapping kode → nama untuk dropdown
        $jenisBarang = [
            'S' => 'Sembako',
            'M' => 'Minuman',
            'N' => 'Snack',
            'E' => 'Elektronik',
            'K' => 'Kebersihan',
            'D' => 'Peralatan Dapur'
        ];

        $satuan = DB::table('satuan')->get();

        return view('barang.index', [
            'barang' => $barang,
            'status' => $status,
            'filterJenis' => $filterJenis,
            'jenisBarang' => $jenisBarang,
            'satuan' => $satuan
        ]);
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'jenis'    => 'required|string|size:1',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|integer|min:0',
        ]);

        Barang::create([
            'nama'     => $request->nama,
            'jenis'    => $request->jenis,
            'idsatuan' => $request->idsatuan,
            'harga'    => $request->harga,
            'status'   => 1,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }


    /**
     * Update barang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'jenis'    => 'required|string|size:1',
            'idsatuan' => 'required|integer',
            'harga'    => 'required|integer|min:0',
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama'     => $request->nama,
            'jenis'    => $request->jenis,
            'idsatuan' => $request->idsatuan,
            'harga'    => $request->harga,
            'status'   => $request->status ?? $barang->status,
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }


    /**
     * Hapus barang
     */
    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }


    /**
     * Ambil harga barang (AJAX)
     */
    public function getHarga($id)
    {
        $barang = Barang::find($id);

        return response()->json([
            'harga' => $barang->harga ?? 0
        ]);
    }


    /**
     * Toggle status barang
     */
    public function toggleStatus($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->status = $barang->status == 1 ? 0 : 1;
        $barang->save();

        return redirect()->route('barang.index')->with('success', 'Status barang berhasil diperbarui.');
    }


    /**
     * Info barang (AJAX)
     */
    public function getInfo($id)
    {
        try {
            $barang = Barang::findOrFail($id);

            return response()->json([
                'success'     => true,
                'nama_barang' => $barang->nama,
                'stok'        => $barang->stok ?? 0,
                'harga'       => $barang->harga,
                'satuan'      => optional($barang->satuanModel)->nama_satuan ?? 'unit'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }
    }
}
