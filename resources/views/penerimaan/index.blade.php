@extends('layouts.app')

@section('title', 'Penerimaan Barang')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold"> Penerimaan Barang</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded">{{ session('error') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <button onclick="toggleModal(true)" 
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        + Tambah Penerimaan
    </button>

    {{-- Tabel Penerimaan --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
        <table class="table-auto w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Vendor</th>
                    <th class="p-2 text-center">Tanggal</th>
                    <th class="p-2 text-center">Total Nilai Pengadaan</th>
                    <th class="p-2 text-center">Status</th>
                    <th class="p-2 text-center">Progress</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $index => $pr)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $pr->nama_vendor }}</td>
                    <td class="p-2 text-center">{{ $pr->created_at }}</td>
                    <td class="p-2 text-right">Rp {{ number_format($pr->total_nilai_pengadaan, 0, ',', '.') }}</td>

                    {{-- STATUS --}}
                    <td class="p-2 text-center">
                        @php
                            $status = strtolower($pr->status_penerimaan);
                        @endphp
                        @if($status == 'selesai')
                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-700">Selesai</span>
                        @elseif($status == 'proses')
                            <span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">Proses</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-gray-200 text-gray-700">Belum</span>
                        @endif
                    </td>

                    {{-- PROGRESS BAR --}}
                    <td class="p-2 text-center">
                        @php
                            $progress = $pr->progress_penerimaan ?? 0;
                            $progressColor = $progress >= 100 ? 'bg-green-600' : ($progress >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                            <div class="{{ $progressColor }} h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <small>{{ $progress }}%</small>
                    </td>

                    <td class="p-2 text-center">
                        <a href="{{ route('penerimaan.show', $pr->idpenerimaan) }}" 
                           class="bg-indigo-500 hover:bg-indigo-600 text-white px-2 py-1 rounded text-xs">
                           Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">Belum ada data penerimaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah Penerimaan --}}
    <div id="penerimaanModal" 
         class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-2/3 p-6">
            <h2 class="text-lg font-semibold mb-4">Tambah Penerimaan Barang</h2>
            <form method="POST" action="{{ route('penerimaan.store') }}">
                @csrf

                {{-- Pilih Pengadaan --}}
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium">Pengadaan</label>
                        <select id="pengadaanSelect" name="idpengadaan" class="w-full border rounded px-2 py-1" required>
                            <option value="">-- Pilih Pengadaan --</option>
                            @foreach($pengadaan as $p)
                                <option value="{{ $p->idpengadaan }}">
                                    #{{ $p->idpengadaan }} - {{ $p->nama_vendor ?? 'Vendor ID '.$p->vendor_idvendor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Tanggal</label>
                        <input type="text" value="{{ now()->format('Y-m-d H:i:s') }}" 
                               class="w-full border rounded px-2 py-1 bg-gray-100" readonly>
                    </div>
                </div>

                {{-- Detail Barang --}}
                <h3 class="font-semibold text-gray-700 mb-2">Detail Barang</h3>
                <div id="items" class="space-y-2"></div>

                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" onclick="toggleModal(false)" 
                            class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">Batal</button>
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
function toggleModal(show) {
    document.getElementById('penerimaanModal').classList.toggle('hidden', !show);
}

document.getElementById('pengadaanSelect').addEventListener('change', async function() {
    const pengadaanId = this.value;
    const container = document.getElementById('items');
    container.innerHTML = '';

    if (!pengadaanId) return;

    try {
        const res = await fetch(`/penerimaan/pengadaan/${pengadaanId}/barang`);
        const data = await res.json();

        if (!Array.isArray(data) || data.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm italic">Tidak ada barang pada pengadaan ini.</p>';
            return;
        }

        data.forEach((item, i) => {
            // jika sisa 0, tampilkan disabled / info
            const maks = item.sisa ?? 0;
            const disabledAttr = (maks <= 0) ? 'disabled' : '';

            const row = `
                <div class="flex gap-2 items-center border rounded-lg p-2 bg-gray-50">
                    <input type="hidden" name="items[${i}][idbarang]" value="${item.idbarang}">
                    <input type="text" value="${item.nama_barang}" 
                           class="w-1/2 border rounded px-2 py-1 bg-gray-100" readonly>

                    <div class="flex flex-col w-1/4">
                        <input type="number" 
                            name="items[${i}][jumlah_terima]" 
                            class="border rounded px-2 py-1 jumlah-terima" 
                            placeholder="Jumlah" 
                            max="${maks}" min="1" ${disabledAttr} required>
                        <small class="text-gray-500 text-xs mt-1">Maks: ${maks}</small>
                    </div>

                    <input type="number" 
                           name="items[${i}][harga_satuan_terima]" 
                           value="${item.harga}" 
                           class="w-1/4 border rounded px-2 py-1 bg-gray-100" readonly>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', row);
        });

        // Validasi jumlah diterima di client
        document.querySelectorAll('.jumlah-terima').forEach(input => {
            input.addEventListener('input', function() {
                const max = parseInt(this.getAttribute('max')) || 0;
                const val = parseInt(this.value) || 0;
                if (val > max) {
                    this.value = max;
                    alert(`Jumlah diterima tidak boleh melebihi ${max}.`);
                }
            });
        });

    } catch (err) {
        console.error(err);
        container.innerHTML = '<p class="text-red-600 text-sm">Gagal memuat data barang.</p>';
    }
});
</script>
@endsection
