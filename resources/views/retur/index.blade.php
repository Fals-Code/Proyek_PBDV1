@extends('layouts.app')

@section('title', 'Retur Barang ke Vendor')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">📦 Retur Barang ke Vendor</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div>
    @endif

    {{-- Info Penting --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 text-sm">
        <p class="font-semibold text-blue-800">ℹ️ Catatan Penting:</p>
        <ul class="list-disc ml-5 mt-2 text-blue-700">
            <li>Retur adalah pengembalian barang cacat/rusak kepada vendor</li>
            <li>Retur <strong>TIDAK mengurangi stok</strong> (hanya pencatatan)</li>
            <li>Jumlah retur tidak boleh melebihi jumlah penerimaan</li>
            <li>Gunakan untuk klaim penggantian ke vendor</li>
        </ul>
    </div>

    {{-- Tombol Tambah --}}
    <button onclick="toggleModal(true)" 
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
        + Buat Retur Baru
    </button>

    {{-- Tabel Retur --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-3">Daftar Retur</h2>
        <table class="table-auto w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">ID Retur</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-center">Vendor</th>
                    <th class="p-2 text-center">Barang</th>
                    <th class="p-2 text-center">Jumlah</th>
                    <th class="p-2 text-left">Alasan</th>
                    <th class="p-2 text-center">Status</th>
                    <th class="p-2 text-center">User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($retur as $index => $r)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2 font-semibold text-red-600">#{{ $r->idretur }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="p-2 text-center">{{ $r->nama_vendor ?? '-' }}</td>
                    <td class="p-2 text-center">{{ $r->nama_barang }}</td>
                    <td class="p-2 text-center font-semibold text-red-700">
                        {{ $r->jumlah_retur }} unit
                        <small class="block text-gray-500">(dari {{ $r->jumlah_diterima_awal }} unit)</small>
                    </td>
                    <td class="p-2 text-sm">{{ $r->alasan }}</td>
                    <td class="p-2 text-center">
                        @if($r->status == 'S')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Selesai</span>
                        @elseif($r->status == 'P')
                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Proses</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-200 text-gray-700">Belum</span>
                        @endif
                    </td>
                    <td class="p-2 text-center text-sm">{{ $r->user_retur }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">Belum ada data retur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Retur --}}
    <div id="returModal" 
         class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-2/3 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4">Buat Retur Barang</h2>
            
            <form method="POST" action="{{ route('retur.store') }}" id="returForm">
                @csrf

                {{-- Pilih Penerimaan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Pilih Penerimaan (Opsional)</label>
                    <select id="penerimaanSelect" name="idpenerimaan" 
                            class="w-full border rounded px-2 py-1">
                        <option value="">-- Pilih Penerimaan atau Input Manual --</option>
                        @foreach($penerimaan as $p)
                            <option value="{{ $p->idpenerimaan }}">
                                #{{ $p->idpenerimaan }} - {{ $p->nama_vendor }} 
                                ({{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Pilih penerimaan untuk auto-load barang, atau isi manual di bawah</small>
                </div>

                {{-- Detail Barang Retur --}}
                <h3 class="font-semibold text-gray-700 mb-2">Detail Barang yang Diretur</h3>
                
                <div id="returItems" class="space-y-3 mb-4">
                    <!-- Items akan di-load dari JS -->
                </div>

                <button type="button" onclick="tambahItemManual()" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm mb-4">
                    + Tambah Item Manual
                </button>

                {{-- Tombol Submit --}}
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" onclick="toggleModal(false)" 
                            class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                        Simpan Retur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
let itemIndex = 0;

function toggleModal(show) {
    const modal = document.getElementById('returModal');
    modal.classList.toggle('hidden', !show);
    
    if (!show) {
        document.getElementById('returForm').reset();
        document.getElementById('returItems').innerHTML = '';
        itemIndex = 0;
    }
}

// Load barang dari penerimaan
document.getElementById('penerimaanSelect').addEventListener('change', async function() {
    const penerimaanId = this.value;
    const container = document.getElementById('returItems');
    
    container.innerHTML = '';
    itemIndex = 0;

    if (!penerimaanId) return;

    try {
        const res = await fetch(`/penerimaan/${penerimaanId}`);
        const html = await res.text();
        
        // Parse HTML untuk ambil data detail
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const rows = doc.querySelectorAll('table tbody tr');
        
        if (rows.length === 0) {
            container.innerHTML = '<p class="text-gray-500 italic">Tidak ada detail barang.</p>';
            return;
        }

        rows.forEach((row) => {
            const cells = row.querySelectorAll('td');
            if (cells.length < 3) return;
            
            const namaBarang = cells[0]?.textContent?.trim() || 'Unknown';
            const jumlahTerima = parseInt(cells[1]?.textContent?.replace(/[^0-9]/g, '') || '0');
            
            // Cari idbarang dari attribute atau hidden field (sesuaikan dengan struktur HTML detail penerimaan)
            // Untuk sementara kita buat input manual
            
            tambahItemDariPenerimaan(namaBarang, jumlahTerima);
        });

    } catch (err) {
        console.error(err);
        alert('Gagal memuat data penerimaan');
    }
});

function tambahItemDariPenerimaan(namaBarang, maxJumlah) {
    const container = document.getElementById('returItems');
    const item = document.createElement('div');
    item.className = 'flex gap-3 items-end border rounded-lg p-3 bg-gray-50';
    item.innerHTML = `
        <div class="flex-1">
            <label class="text-xs text-gray-600">Nama Barang</label>
            <input type="text" value="${namaBarang}" readonly 
                   class="w-full border rounded px-2 py-1 bg-gray-100">
        </div>
        <div class="w-1/4">
            <label class="text-xs text-gray-600">ID Barang</label>
            <input type="number" name="items[${itemIndex}][idbarang]" required
                   placeholder="ID Barang" class="w-full border rounded px-2 py-1">
        </div>
        <div class="w-1/4">
            <label class="text-xs text-gray-600">Jumlah Retur</label>
            <input type="number" name="items[${itemIndex}][jumlah]" required
                   min="1" max="${maxJumlah}" placeholder="Qty"
                   class="w-full border rounded px-2 py-1">
            <small class="text-gray-500">Maks: ${maxJumlah}</small>
        </div>
        <div class="flex-1">
            <label class="text-xs text-gray-600">Alasan Retur</label>
            <input type="text" name="items[${itemIndex}][alasan]" required
                   placeholder="Barang rusak, cacat, dll"
                   class="w-full border rounded px-2 py-1">
        </div>
        <div class="w-1/6">
            <label class="text-xs text-gray-600">Detail Penerimaan ID</label>
            <input type="number" name="items[${itemIndex}][iddetail_penerimaan]"
                   placeholder="Optional" class="w-full border rounded px-2 py-1">
        </div>
        <button type="button" onclick="this.parentElement.remove()"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
            X
        </button>
    `;
    
    container.appendChild(item);
    itemIndex++;
}

function tambahItemManual() {
    const container = document.getElementById('returItems');
    const item = document.createElement('div');
    item.className = 'flex gap-3 items-end border rounded-lg p-3 bg-gray-50';
    item.innerHTML = `
        <div class="w-1/5">
            <label class="text-xs text-gray-600">ID Barang</label>
            <input type="number" name="items[${itemIndex}][idbarang]" required
                   placeholder="ID Barang" class="w-full border rounded px-2 py-1">
        </div>
        <div class="w-1/6">
            <label class="text-xs text-gray-600">Jumlah</label>
            <input type="number" name="items[${itemIndex}][jumlah]" required
                   min="1" placeholder="Qty" class="w-full border rounded px-2 py-1">
        </div>
        <div class="flex-1">
            <label class="text-xs text-gray-600">Alasan Retur</label>
            <input type="text" name="items[${itemIndex}][alasan]" required
                   placeholder="Barang rusak, cacat, dll"
                   class="w-full border rounded px-2 py-1">
        </div>
        <div class="w-1/6">
            <label class="text-xs text-gray-600">ID Detail Penerimaan</label>
            <input type="number" name="items[${itemIndex}][iddetail_penerimaan]"
                   placeholder="Optional" class="w-full border rounded px-2 py-1">
        </div>
        <button type="button" onclick="this.parentElement.remove()"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
            X
        </button>
    `;
    
    container.appendChild(item);
    itemIndex++;
}

// Validasi form sebelum submit
document.getElementById('returForm').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('#returItems > div');
    
    if (items.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 item untuk diretur!');
        return;
    }
    
    // Validasi setiap item
    let valid = true;
    items.forEach(item => {
        const idbarang = item.querySelector('input[name*="[idbarang]"]').value;
        const jumlah = item.querySelector('input[name*="[jumlah]"]').value;
        const alasan = item.querySelector('input[name*="[alasan]"]').value;
        
        if (!idbarang || !jumlah || !alasan) {
            valid = false;
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Lengkapi semua data item retur!');
    }
});
</script>
@endsection