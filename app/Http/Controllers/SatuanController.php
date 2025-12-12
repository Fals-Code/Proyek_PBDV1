<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'aktif'); // aktif / nonaktif / all

        if ($status === 'aktif') {
            // Hanya satuan aktif
            $satuans = DB::table('v_satuan_aktif')->get();
        } else {
            // Semua status → pakai master
            $query = DB::table('v_master_satuan');

            if ($status === 'nonaktif') {
                $query->where('status', 0);
            }

            $satuans = $query->get();
        }

        return view('satuan.index', compact('satuans', 'status'));
    }


    /**
     * Tambah satuan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
        ]);

        Satuan::create([
            'nama_satuan' => $request->nama_satuan,
            'status'      => 1, // default aktif
        ]);

        return redirect()->route('satuan.index')
            ->with('success', 'Satuan berhasil ditambahkan.');
    }


    /**
     * Update satuan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:45',
        ]);

        $satuan = Satuan::findOrFail($id);

        $satuan->update([
            'nama_satuan' => $request->nama_satuan,
            'status'      => $request->status ?? $satuan->status,
        ]);

        return redirect()->route('satuan.index')
            ->with('success', 'Data satuan berhasil diperbarui.');
    }


    /**
     * Toggle status aktif/nonaktif
     */
    public function toggleStatus($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->status = $satuan->status == 1 ? 0 : 1;
        $satuan->save();

        return redirect()->route('satuan.index')
            ->with('success', 'Status satuan berhasil diperbarui.');
    }


    /**
     * Hapus satuan
     */
    public function destroy($id)
    {
        Satuan::findOrFail($id)->delete();

        return redirect()->route('satuan.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}
