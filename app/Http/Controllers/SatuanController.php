<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Satuan;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = DB::table('v_master_satuan')->orderBy('idsatuan', 'asc')->get();
        return view('satuan.index', compact('satuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:50',
        ]);

        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
            'status' => 1
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
            'status' => $request->status ?? 1
        ]);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Satuan::findOrFail($id)->delete();
        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
