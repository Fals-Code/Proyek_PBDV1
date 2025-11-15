@extends('layouts.app')

@section('title', 'Detail Retur')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6 space-y-6">
    
    {{-- Header --}}
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Retur #{{ $header->idretur }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Dibuat oleh: {{ $header->user_retur }} | 
                {{ \Carbon\Carbon::parse($header->created_at)->format('d M Y, H:i') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('retur.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded transition">
               ← Kembali
            </a>
            
            {{-- Form Update Status --}}
            @if($header->status != 'S')
            <form action="{{ route('retur.updateStatus', $header->idretur) }}" method="POST" class="inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="S">
                <button type="submit" 
                        onclick="return confirm('Tandai retur ini sebagai selesai?')"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
                    ✓ Tandai Selesai
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Info Vendor & Status --}}
    <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Vendor:</span>
                <span class="text-lg font-semibold">{{ $header->nama_vendor ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-600 font-medium">Status:</span>
                @if($header->status == 'S')
                    <span class="px-3 py-1 rounded text-sm font-semibold bg-green-100 text-green-700">
                        ✓ Selesai
                    </span>
                @elseif($header->status == 'P')
                    <span class="px-3 py-1 rounded text-sm font-semibold bg-yellow-100 text-yellow-700">
                        ⏳ Dalam Proses
                    </span>
                @else
                    <span class="px-3 py-1 rounded text-sm font-semibold bg-gray-200 text-gray-700">
                        ⚠️ Belum Diproses
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
            <p class="text-sm text-yellow-800">
                <strong>⚠️ Catatan:</strong> Retur tidak mengurangi stok sistem. 
                Barang yang diretur hanya dicatat untuk klaim ke vendor.
            </p>
        </div>
    </div>

    {{-- Detail Barang --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Daftar Barang yang Diretur</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Barang</th>
                        <th class="p-3 text-center">Jumlah Diretur</th>
                        <th class="p-3 text-center">Dari Penerimaan</th>
                        <th class="p-3 text-center">Harga Satuan</th>
                        <th class="p-3 text-left">Alasan Retur</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilai = 0; @endphp
                    @foreach($retur as $index => $item)
                        @php 
                            $subtotal = $item->jumlah_retur * ($item->harga_satuan_terima ?? 0);
                            $totalNilai += $subtotal;
                        @endphp
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }} hover:bg-red-50 transition">
                            <td class="p-3 border-b border-gray-200">{{ $index + 1 }}</td>
                            <td class="p-3 border-b border-gray-200 font-medium">
                                {{ $item->nama_barang }}
                            </td>
                            <td class="p-3 border-b border-gray-200 text-center font-bold text-red-700">
                                {{ $item->jumlah_retur }} unit
                            </td>
                            <td class="p-3 border-b border-gray-200 text-center text-gray-600">
                                {{ $item->jumlah_diterima_awal ?? '-' }} unit
                            </td>
                            <td class="p-3 border-b border-gray-200 text-center">
                                Rp {{ number_format($item->harga_satuan_terima ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="p-3 border-b border-gray-200">
                                <span class="text-sm text-gray-700">{{ $item->alasan }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-red-100 font-semibold text-gray-800">
                    <tr>
                        <td colspan="2" class="p-3 text-right border-t-2 border-red-300">
                            Total Nilai Retur:
                        </td>
                        <td colspan="4" class="p-3 text-left border-t-2 border-red-300 text-red-700">
                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Aksi Tambahan --}}
    <div class="flex justify-between items-center border-t pt-4">
        <div class="text-sm text-gray-600">
            <p><strong>Informasi:</strong></p>
            <ul class="list-disc ml-5 mt-1">
                <li>Barang yang diretur akan diklaim penggantian kepada vendor</li>
                <li>Stok sistem tetap tidak berubah sampai vendor mengirim penggantian</li>
                <li>Jika vendor sudah kirim penggantian, buat Penerimaan baru</li>
            </ul>
        </div>

        {{-- Tombol Delete (opsional, hanya untuk admin) --}}
        @if(Auth::user()->idrole == 1 && $header->status == 'N')
        <form action="{{ route('retur.destroy', $header->idretur) }}" method="POST" 
              onsubmit="return confirm('⚠️ Yakin ingin menghapus retur ini? Data tidak bisa dikembalikan!')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                🗑️ Hapus Retur
            </button>
        </form>
        @endif
    </div>

</div>
@endsection