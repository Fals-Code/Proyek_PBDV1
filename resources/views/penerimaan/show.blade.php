@extends('layouts.app')

@section('title', 'Detail Penerimaan')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            Detail Penerimaan #{{ $header->idpenerimaan ?? '-' }}
        </h1>
        <a href="{{ route('penerimaan.index') }}" 
           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded transition">
           ← Kembali
        </a>
    </div>

    {{-- Informasi Umum --}}
    <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
        <div>
            <p><strong>Vendor:</strong> {{ $header->nama_vendor ?? '-' }}</p>
            <p><strong>ID Pengadaan:</strong> #{{ $header->idpengadaan ?? '-' }}</p>
        </div>
        <div>
            <p><strong>Tanggal:</strong> {{ $header->created_at ?? '-' }}</p>
            <p><strong>Diterima Oleh:</strong> {{ $header->diterima_oleh ?? '-' }}</p>
        </div>
    </div>

    <p>
        <strong>Status:</strong>
        <span class="px-2 py-1 text-sm rounded font-medium
            {{ ($header->status_penerimaan ?? '') == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
            {{ $header->status_penerimaan ?? 'Belum' }}
        </span>
    </p>

    <hr class="my-4">

    {{-- Detail Barang --}}
    <h2 class="text-lg font-semibold text-gray-800 mb-2">Detail Barang Diterima</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2 text-left">Nama Barang</th>
                    <th class="p-2 text-center">Jumlah Diterima</th>
                    <th class="p-2 text-center">Harga Satuan</th>
                    <th class="p-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($detail as $index => $d)
                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-indigo-50 transition">
                    <td class="p-2 border-b border-gray-200">{{ $d->nama_barang ?? '-' }}</td>
                    <td class="p-2 border-b border-gray-200 text-center">
                        {{ number_format($d->jumlah_terima ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-2 border-b border-gray-200 text-center">
                        Rp {{ number_format($d->harga_satuan_terima ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-2 border-b border-gray-200 text-right font-semibold text-gray-700">
                        Rp {{ number_format($d->sub_total_terima ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500 italic">Belum ada detail barang.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-semibold text-gray-800">
                <tr>
                    <td colspan="3" class="p-3 text-right border-t border-gray-300">Total</td>
                    <td class="p-3 text-right border-t border-gray-300 text-indigo-700">
                        Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
