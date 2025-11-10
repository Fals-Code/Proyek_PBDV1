<?php

namespace App\Http\Controllers;

use App\Models\MarginPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarginPenjualanController extends Controller
{
    public function index()
    {
        $margin = DB::table('v_master_margin_penjualan')
            ->orderBy('idmargin_penjualan', 'asc')
            ->get();

        return view('margin_penjualan.index', compact('margin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'persen_margin' => 'required|numeric|min:0|max:100',
            'status' => 'nullable|boolean',
        ]);

        MarginPenjualan::create([
            'persen_margin' => $request->persen_margin,
            'status' => 1
        ]);

        return redirect()->route('margin_penjualan.index')->with('success', 'Margin berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $margin = MarginPenjualan::findOrFail($id);
        $margin->update([
            'persen_margin' => $request->persen_margin,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('margin_penjualan.index')->with('success', 'Margin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MarginPenjualan::findOrFail($id)->delete();
        return redirect()->route('margin_penjualan.index')->with('success', 'Margin berhasil dihapus.');
    }

    public function activate($id)
    {
        $margin = MarginPenjualan::findOrFail($id);
        DB::statement('CALL sp_set_margin_aktif(?)', [$id]);

        return redirect()->route('margin_penjualan.index')
            ->with('success', 'Margin Persen ' . $margin->persen_margin . ' telah diaktifkan!');
    }
}
