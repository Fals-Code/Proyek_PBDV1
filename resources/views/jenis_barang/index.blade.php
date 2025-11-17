@extends('layouts.app')

@section('title', 'Data Jenis Barang')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                Data Jenis Barang
            </h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen kategori atau jenis-jenis barang</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Tabel Jenis Barang --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Jenis</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Deskripsi</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisBarang as $i => $j)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-600">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $j->nama_jenis }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $j->deskripsi ?? '-' }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('jenis-barang.edit', $j->idjenis) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">Edit</a>
                                <form action="{{ route('jenis-barang.destroy', $j->idjenis) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus jenis barang ini?')" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                Belum ada data jenis barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection