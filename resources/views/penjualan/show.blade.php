@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
<div class="bg-white shadow rounded p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-bold">Detail Penjualan #{{ $penjualan->idpenjualan }}</h1>
        <a href="{{ route('penjualan.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">← Kembali</a>
    </div>

    {{-- Informasi Transaksi --}}
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->created_at)->format('d-m-Y H:i') }}</p>
            <p><strong>Kasir:</strong> {{ $penjualan->username }}</p>
            <p><strong>Margin Penjualan:</strong> {{ $penjualan->idmargin_penjualan }}</p>
        </div>
        <div>
            <p><strong>Subtotal:</strong> Rp {{ number_format($penjualan->subtotal_nilai, 0, ',', '.') }}</p>
            <p><strong>PPN:</strong> Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</p>
            <p class="font-semibold text-lg"><strong>Total:</strong> Rp {{ number_format($penjualan->total_nilai, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tabel Detail Barang --}}
    <div>
        <h2 class="text-lg font-semibold mb-3">Barang yang Dijual</h2>
        <table class="w-full text-sm border-collapse border">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-2 border text-left">Nama Barang</th>
                    <th class="p-2 border text-center">Jumlah</th>
                    <th class="p-2 border text-center">Harga Beli</th>
                    <th class="p-2 border text-center">Harga Jual</th>
                    <th class="p-2 border text-center">Subtotal</th>
                    <th class="p-2 border text-center">Laba Kotor</th>
                    <th class="p-2 border text-center">Stok Sekarang</th>
                </tr>
            </thead>
            <tbody>
                @php $totalLaba = 0; @endphp
                @foreach($detail as $d)
                    @php
                        $laba = ($d->harga_satuan - $d->harga_beli_terakhir) * $d->jumlah;
                        $totalLaba += $laba;
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $d->nama_barang }}</td>
                        <td class="p-2 text-center">{{ $d->jumlah }}</td>
                        <td class="p-2 text-center">Rp {{ number_format($d->harga_beli_terakhir, 0, ',', '.') }}</td>
                        <td class="p-2 text-center">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="p-2 text-center">Rp {{ number_format($d->subtotal_transaksi, 0, ',', '.') }}</td>
                        <td class="p-2 text-center text-green-700 font-semibold">Rp {{ number_format($laba, 0, ',', '.') }}</td>
                        <td class="p-2 text-center">{{ $d->stok_terkini }}</td>
                    </tr>
                @endforeach
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="5" class="p-2 text-right">Total Laba Kotor</td>
                    <td class="p-2 text-center text-green-700">Rp {{ number_format($totalLaba, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
