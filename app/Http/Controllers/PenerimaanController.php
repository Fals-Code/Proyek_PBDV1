<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    /**
     * Menampilkan daftar penerimaan barang
     */
    public function index()
    {
        DB::flushQueryLog();
        $penerimaan = DB::select('SELECT * FROM v_penerimaan_semua ORDER BY created_at DESC');

        $penerimaan = DB::select('SELECT * FROM v_penerimaan_semua ORDER BY created_at DESC');
        $pengadaan = DB::select('SELECT * FROM v_pengadaan_terbuka');
        $barangs = DB::select('SELECT * FROM v_barang_aktif');

        return view('penerimaan.index', compact('penerimaan', 'pengadaan', 'barangs'));
    }

    /**
     * Form tambah penerimaan
     */
    public function create()
    {
        $pengadaan = DB::select('SELECT * FROM v_pengadaan_terbuka');
        $barangs = DB::select('SELECT * FROM v_barang_aktif');
        return view('penerimaan.create', compact('pengadaan', 'barangs'));
    }

    /**
     * Menampilkan detail penerimaan berdasarkan ID
     */
    public function show($id)
    {
        $header = DB::selectOne('SELECT * FROM v_detail_penerimaan_header WHERE idpenerimaan = ?', [$id]);
        $detail = DB::select('SELECT * FROM v_detail_penerimaan_barang WHERE idpenerimaan = ?', [$id]);

        $total = 0;
        foreach ($detail as $d) {
            $total += (float) ($d->sub_total_terima ?? 0);
        }

        return view('penerimaan.show', [
            'header' => $header,
            'detail' => $detail,
            'total' => $total,
            'title' => 'Detail Penerimaan #' . ($header->idpenerimaan ?? '-')
        ]);
    }

    /**
     * Simpan data penerimaan ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'idpengadaan' => 'required|integer',
            'items' => 'required|array|min:1'
        ]);

        try {
            $idUser = Auth::user()->iduser;
            $idPengadaan = $request->idpengadaan;
            $items = json_encode($request->items);

            DB::statement('CALL sp_add_penerimaan_fix(?, ?, ?, @out_id)', [
                $idPengadaan,
                $idUser,
                $items
            ]);

            // Ambil hasil output ID penerimaan
            $result = DB::select('SELECT @out_id AS idpenerimaan');
            $idpenerimaan = $result[0]->idpenerimaan ?? null;

            if (!$idpenerimaan) {
                return redirect()->back()->with('error', 'Gagal menyimpan data penerimaan (ID tidak terdeteksi).');
            }

            return redirect()->route('penerimaan.show', $idpenerimaan)
                ->with('success', 'Penerimaan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }


    /**
     * AJAX - Ambil daftar barang berdasarkan pengadaan
     */
    public function getBarangByPengadaan($id)
    {
        try {
            $data = DB::select('CALL sp_get_barang_pengadaan(?)', [$id]);

            if (empty($data)) {
                return response()->json([
                    'error' => false,
                    'message' => 'Tidak ada barang untuk pengadaan ini.',
                    'data' => []
                ], 200);
            }

            // map ke array sederhana (opsional) supaya JSON rapi
            $out = array_map(function ($row) {
                return [
                    'idbarang' => $row->idbarang,
                    'nama_barang' => $row->nama_barang,
                    'harga' => (float) $row->harga,
                    'jumlah_pengadaan' => (int) $row->jumlah_pengadaan,
                    'jumlah_diterima' => (int) $row->jumlah_diterima,
                    'sisa' => (int) $row->sisa,
                ];
            }, $data);

            return response()->json($out, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
