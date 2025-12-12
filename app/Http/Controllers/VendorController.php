<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Menampilkan daftar vendor dengan filter status.
     * Default: vendor aktif.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'aktif'); // aktif / nonaktif / all


        if ($status === 'aktif') {
            // View khusus yang hanya menampilkan vendor aktif
            $vendors = DB::table('v_vendor_aktif')
                ->orderBy('nama_vendor', 'asc')
                ->get();
        } else {
            // Ambil semua dari master view
            $query = DB::table('v_master_vendor');

            if ($status === 'nonaktif') {
                $query->where('status', 'N');
            }

            $vendors = $query->orderBy('nama_vendor', 'asc')->get();
        }

        return view('vendor.index', compact('vendors', 'status'));
    }


    /**
     * Tambah vendor baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|size:1', // P, C, D
        ]);

        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => strtoupper($request->badan_hukum),
            'status'      => 'A', // default aktif
        ]);

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Vendor baru berhasil ditambahkan.');
    }


    /**
     * Update vendor
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:100',
            'badan_hukum' => 'required|string|size:1',
        ]);

        $vendor = Vendor::findOrFail($id);

        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
            'badan_hukum' => strtoupper($request->badan_hukum),
            'status'      => $request->status ?? $vendor->status,
        ]);

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Data vendor berhasil diperbarui.');
    }


    /**
     * Toggle status aktif/nonaktif
     */
    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);

        // A ↔ N
        $vendor->status = $vendor->status === 'A' ? 'N' : 'A';
        $vendor->save();

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Status vendor berhasil diperbarui.');
    }


    /**
     * Hapus vendor
     */
    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Vendor berhasil dihapus.');
    }
}
