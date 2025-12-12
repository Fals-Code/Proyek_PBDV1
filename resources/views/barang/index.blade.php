@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Data Barang
            </h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen daftar barang, status, dan jenis</p>
        </div>
    </div>

    {{-- Alert success --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filter status & jenis --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Filter Barang</h3>
            <form method="GET" action="{{ route('barang.index') }}" class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="status" class="text-sm font-medium text-gray-600">Status:</label>
                    <select name="status" id="status" class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    </select>
                </div>

                <div>
                    <label for="jenis" class="text-sm font-medium text-gray-600">Jenis Barang:</label>
                    <select name="jenis">
    <option value="semua">Semua Jenis</option>
    @foreach($jenisBarang as $kode => $nama)
        <option value="{{ $kode }}" 
            {{ $filterJenis == $kode ? 'selected' : '' }}>
            {{ $nama }}
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

    {{-- Tabel Barang --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Barang</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Jenis Barang</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Satuan</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Harga</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $index => $b)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $b->nama_barang ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $b->jenis_barang ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $b->nama_satuan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-semibold text-green-700">
                            Rp{{ number_format($b->harga ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
    <form action="{{ route('barang.toggleStatus', $b->idbarang) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <button type="submit"
            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300
                {{ $b->status == 1
                    ? 'bg-green-100 text-green-800 hover:bg-green-200 hover:scale-105'
                    : 'bg-gray-200 text-gray-600 hover:bg-gray-300 hover:scale-105' }}">
            {{ $b->status == 1 ? 'Aktif' : 'Nonaktif' }}
        </button>
    </form>
</td>

                        <td class="px-6 py-4 text-center space-x-2">
                            <button 
                                onclick="editBarang({{ json_encode($b) }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                Edit
                            </button>

                            <form action="{{ route('barang.destroy', $b->idbarang) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300"
                                    onclick="return confirm('Hapus barang ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">Tidak ada data barang yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form Barang --}}
    <div id="barangModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 m-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Edit Barang</h2>
                <button onclick="toggleModal(false)" class="text-gray-500 hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="barangForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                        <input type="text" name="nama" id="nama" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang</label>
                        <select name="jenis" class="form-control">
    <option value="semua">Semua Jenis</option>
    @foreach($jenisBarang as $kode => $nama)
        <option value="{{ $kode }}" 
            {{ $filterJenis == $kode ? 'selected' : '' }}>
            {{ $nama }}
        </option>
    @endforeach
</select>

                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                        <select name="idsatuan" id="idsatuan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($satuan as $s)
                                <option value="{{ $s->idsatuan }}">{{ $s->nama_satuan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input type="number" name="harga" id="harga" min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>
                <div class="flex justify-end mt-8 space-x-4">
                    <button type="button" onclick="toggleModal(false)" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-lg transition-all duration-300">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(show) {
    const modal = document.getElementById('barangModal');
    const modalContent = document.getElementById('modalContent');
    
    if (show) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    } else {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

function editBarang(data) {
    const form = document.getElementById('barangForm');
    form.action = `/barang/${data.idbarang}`;
    
    document.getElementById('nama').value = data.nama_barang ?? data.nama ?? '';
document.getElementById('jenis').value = data.jenis ?? '';
document.getElementById('idsatuan').value = data.idsatuan ?? '';
document.getElementById('harga').value = data.harga ?? 0;

    toggleModal(true);
}
</script>
@endsection