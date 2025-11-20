@extends('layouts.app')

@section('title', 'Detail Kartu Stok')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Kartu Stok</h1>
            <p class="text-sm text-gray-600 mt-1">Riwayat pergerakan stok barang</p>
        </div>
        <a href="{{ route('stok.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- Info Barang --}}
    <div class="bg-gradient-to-br from-indigo-50 to-blue-50 border-2 border-indigo-200 rounded-xl p-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xl font-bold text-indigo-900 mb-4">Informasi Barang</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Nama Barang:</span>
                        <span class="text-sm text-gray-800 font-semibold">{{ $barang->nama }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Jenis:</span>
                        <span class="text-sm text-gray-800">{{ $barang->nama_jenis ?? '-' }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Satuan:</span>
                        <span class="text-sm text-gray-800">{{ $barang->nama_satuan ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-bold text-indigo-900 mb-4">Stok & Harga</h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Stok Akhir:</span>
                        <span class="text-2xl font-bold text-green-700">{{ number_format($stokAkhir->stok, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Harga Beli:</span>
                        <span class="text-sm text-gray-800 font-semibold">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-sm text-indigo-600 font-medium w-32">Harga Jual:</span>
                        <span class="text-sm text-gray-800 font-semibold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Kartu Stok --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Riwayat Pergerakan Stok</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Jenis Transaksi</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">ID Transaksi</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Masuk</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Keluar</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kartuStok as $index => $ks)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($ks->created_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @switch($ks->jenis_transaksi)
                                @case('M')
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        📦 Masuk (Penerimaan)
                                    </span>
                                    @break
                                @case('K')
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        📤 Keluar (Penjualan)
                                    </span>
                                    @break
                                @case('R')
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        ↩️ Retur ke Vendor
                                    </span>
                                    @break
                                @case('T')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        ↪️ Return dari Customer
                                    </span>
                                    @break
                                @case('B')
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        ❌ Batal
                                    </span>
                                    @break
                                @default
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                                        {{ $ks->jenis_transaksi }}
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                #{{ $ks->idtransaksi ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($ks->masuk > 0)
                                <span class="text-blue-700 font-bold">+{{ number_format($ks->masuk, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($ks->keluar > 0)
                                <span class="text-red-700 font-bold">-{{ number_format($ks->keluar, 0, ',', '.') }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-100 text-green-800 px-4 py-1 rounded-full font-bold">
                                {{ number_format($ks->stock, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            Belum ada riwayat pergerakan stok untuk barang ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection