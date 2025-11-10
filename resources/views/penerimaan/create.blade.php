@extends('layouts.app')

@section('title', 'Tambah Penerimaan Barang')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6 space-y-6">
    <h1 class="text-2xl font-bold">Tambah Penerimaan Barang</h1>

    <form method="POST" action="{{ route('penerimaan.store') }}" id="formPenerimaan">
        @csrf

        {{-- Pilih Pengadaan --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pengadaan</label>
                <select name="idpengadaan" class="w-full border rounded px-2 py-1" required>
                    <option value="">-- Pilih Pengadaan --</option>
                    @foreach($pengadaan as $p)
                        <option value="{{ $p->idpengadaan }}">
                            #{{ $p->idpengadaan }} - {{ $p->nama_vendor ?? 'Vendor ID '.$p->vendor_idvendor }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="text" value="{{ now()->format('Y-m-d H:i:s') }}" 
                       class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
            </div>
        </div>

        {{-- Detail Barang --}}
        <div class="mt-6">
            <h3 class="font-semibold text-lg mb-2">Detail Barang</h3>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Barang</th>
                        <th class="p-2 text-center">Jumlah Terima</th>
                        <th class="p-2 text-center">Harga Satuan</th>
                        <th class="p-2 text-center">Subtotal</th>
                        <th class="p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabel-barang"></tbody>
            </table>

            <button type="button" id="tambah-barang" 
                    class="mt-3 bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">
                + Tambah Barang
            </button>
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex justify-end mt-6 space-x-2">
            <a href="{{ route('penerimaan.index') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">Batal</a>
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Simpan</button>
        </div>
    </form>
</div>

<script>
let idx = 0;
document.getElementById('tambah-barang').addEventListener('click', function() {
    const tbody = document.getElementById('tabel-barang');
    const row = document.createElement('tr');
    row.classList.add('border-b');
    row.innerHTML = `
        <td class="p-2">
            <select name="items[${idx}][idbarang]" class="w-full border rounded px-2 py-1" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $b)
                    <option value="{{ $b->idbarang }}">{{ $b->nama_barang ?? $b->nama }}</option>
                @endforeach
            </select>
        </td>
        <td class="p-2 text-center">
            <input type="number" name="items[${idx}][jumlah_terima]" min="1" value="1" 
                   class="w-24 border rounded px-2 py-1 text-center jumlah">
        </td>
        <td class="p-2 text-center">
            <input type="number" name="items[${idx}][harga_satuan_terima]" min="0" value="0" 
                   class="w-32 border rounded px-2 py-1 text-center harga">
        </td>
        <td class="p-2 text-center">
            <input type="text" class="subtotal w-32 border rounded px-2 py-1 text-center bg-gray-50" readonly>
        </td>
        <td class="p-2 text-center">
            <button type="button" class="hapus bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">X</button>
        </td>
    `;
    tbody.appendChild(row);

    row.querySelector('.hapus').addEventListener('click', () => row.remove());
    row.querySelectorAll('.jumlah, .harga').forEach(input => {
        input.addEventListener('input', () => {
            const j = parseFloat(row.querySelector('.jumlah').value) || 0;
            const h = parseFloat(row.querySelector('.harga').value) || 0;
            row.querySelector('.subtotal').value = (j * h).toLocaleString('id-ID');
        });
    });
    idx++;
});
</script>
@endsection
