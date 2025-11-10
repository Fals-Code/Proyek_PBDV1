<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenisBarang = JenisBarang::all();
        return view('jenis_barang.index', compact('jenisBarang'));
    }


    public function create()
    {
        return view('jenis_barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:200',
        ]);

        JenisBarang::create($request->all());
        return redirect()->route('jenis-barang.index')->with('success', 'Jenis barang berhasil ditambahkan.');
    }

    public function edit(JenisBarang $jenis_barang)
    {
        return view('jenis_barang.edit', compact('jenis_barang'));
    }

    public function update(Request $request, JenisBarang $jenis_barang)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'deskripsi' => 'nullable|string|max:200',
        ]);

        $jenis_barang->update($request->all());
        return redirect()->route('jenis-barang.index')->with('success', 'Jenis barang berhasil diperbarui.');
    }

    public function destroy(JenisBarang $jenis_barang)
    {
        $jenis_barang->delete();
        return redirect()->route('jenis-barang.index')->with('success', 'Jenis barang berhasil dihapus.');
    }
}
