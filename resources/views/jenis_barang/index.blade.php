@extends('layouts.app')

@section('title', 'Data Jenis Barang')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Daftar Jenis Barang</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4 flex justify-between">
        <a href="{{ route('jenis-barang.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Jenis Barang
        </a>
    </div>

    <table class="w-full border text-sm text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama Jenis</th>
                <th class="p-2 border">Deskripsi</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jenisBarang as $i => $j)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ $j->nama_jenis }}</td>
                    <td class="p-2">{{ $j->deskripsi ?? '-' }}</td>
                    <td class="p-2 text-center">
                        <a href="{{ route('jenis-barang.edit', $j->idjenis) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Edit</a>
                        <form action="{{ route('jenis-barang.destroy', $j->idjenis) }}" 
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus jenis barang ini?')" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 text-center text-gray-500">
                        Belum ada data jenis barang.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
