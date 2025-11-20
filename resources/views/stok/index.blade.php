@extends('layouts.app')

@section('title', 'Laporan Stok Barang')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                </svg>
                Laporan Stok Barang
            </h1>
            <p class="text-sm text-gray-600 mt-1">Monitoring stok persediaan barang gudang</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filter Section --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Filter Stok</h3>
            <form method="GET" action="{{ route('stok.index') }}" class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="jenis" class="text-sm font-medium text-gray-600">Jenis Barang:</label>
                    <select name="jenis" id="jenis" class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="all" {{ $filterJenis == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                        @foreach($jenisBarang as $j)
                            <option value="{{ $j }}" {{ $filterJenis == $j ? 'selected' : '' }}>
                                {{ $j }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition-all duration-300">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Total Item Barang</p>
                    <p class="text-3xl font-bold text-blue-800 mt-2">{{ $stokBarang->count() }}</p>
                </div>
                <div class="bg-blue-500 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Total Stok Tersedia</p>
                    <p class="text-3xl font-bold text-green-800 mt-2">{{ number_format($stokBarang->sum('stok_akhir'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-500 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Nilai Total Stok</p>
                    <p class="text-3xl font-bold text-yellow-800 mt-2">
                        Rp {{ number_format($stokBarang->sum(function($item) { 
                            return $item->stok_akhir * $item->harga; 
                        }), 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-yellow-500 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Stok --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Barang</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Jenis</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Satuan</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Total Masuk</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Total Keluar</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Stok Akhir</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stokBarang as $index => $item)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama_barang }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $item->nama_jenis ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $item->nama_satuan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                                {{ number_format($item->total_masuk, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-semibold">
                                {{ number_format($item->total_keluar, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-4 py-1.5 rounded-full font-bold text-base">
                                {{ number_format($item->stok_akhir, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($item->stok_akhir <= 5)
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ⚠️ Stok Menipis
                                </span>
                            @elseif($item->stok_akhir <= 10)
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ⚡ Perlu Restock
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    ✓ Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('stok.detail', $item->idbarang) }}" 
                               class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-6 text-center text-gray-500">
                            Tidak ada data stok barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection