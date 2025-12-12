<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MarginPenjualan;

class MarginPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');


        if ($status === 'aktif') {
            // hanya margin aktif
            $margin = DB::table('v_margin_penjualan_aktif')->get();
        } else {
            // view master menampilkan nama status text (Aktif / Nonaktif)
            $query = DB::table('v_master_margin_penjualan');

            if ($status === 'nonaktif') {
                $query->where('status', 'Nonaktif');
            }

            // urutkan berdasarkan persen_margin (nama kolom di view)
            $margin = $query->orderBy('persen_margin', 'asc')->get();
        }

        return view('margin_penjualan.index', compact('margin', 'status'));
    }


    /**
     * Tambah margin baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:100',
        ]);

        MarginPenjualan::create([
            'persen' => $request->persen,
            'status' => 0
        ]);

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Margin berhasil ditambahkan!');
    }


    /**
     * Update margin
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'persen' => 'required|numeric|min:0|max:100',
        ]);

        $margin = MarginPenjualan::findOrFail($id);

        $margin->update([
            'persen' => $request->persen,
            'status' => $request->status ?? $margin->status
        ]);

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Margin berhasil diperbarui!');
    }


    /**
     * Aktifkan margin (mode: cuma 1 aktif)
     */
    public function activate($id)
    {
        DB::statement("CALL sp_set_margin_aktif(?)", [$id]);

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Margin berhasil diaktifkan!');
    }


    /**
     * Toggle ON/OFF margin
     */
    public function toggle($id)
    {
        DB::statement("CALL sp_toggle_margin_penjualan(?)", [$id]);

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Status margin berhasil diperbarui!');
    }


    /**
     * Hapus margin
     */
    public function destroy($id)
    {
        MarginPenjualan::findOrFail($id)->delete();

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Margin berhasil dihapus.');
    }
}
