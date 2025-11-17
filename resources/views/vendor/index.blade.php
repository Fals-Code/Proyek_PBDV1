@extends('layouts.app')

@section('title', 'Data Vendor')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Data Vendor (Supplier)
            </h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen daftar vendor, status, dan badan hukum</p>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filter Status --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Filter Vendor</h3>
            <form method="GET" action="{{ route('vendor.index') }}" class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="status" class="text-sm font-medium text-gray-600">Tampilkan:</label>
                    <select name="status" id="status" class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition-all duration-300">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Vendor --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Vendor</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Badan Hukum</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $index => $v)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $v->nama_vendor }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                @if($v->badan_hukum == 'P')
                                    PT
                                @elseif($v->badan_hukum == 'C')
                                    CV
                                @elseif($v->badan_hukum == 'D')
                                    UD / Lainnya
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('vendor.toggleStatus', $v->idvendor) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300
                                        {{ $v->status == 'Aktif'
                                            ? 'bg-green-100 text-green-800 hover:bg-green-200 hover:scale-105'
                                            : 'bg-gray-200 text-gray-600 hover:bg-gray-300 hover:scale-105' }}">
                                    {{ $v->status }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button onclick="editVendor({{ json_encode($v) }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                Edit
                            </button>
                            <form action="{{ route('vendor.destroy', $v->idvendor) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300"
                                    onclick="return confirm('Hapus vendor ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">Tidak ada data vendor yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Edit Vendor --}}
    <div id="vendorModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 m-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Edit Vendor</h2>
                <button onclick="toggleModal(false)" class="text-gray-500 hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="vendorForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
                        <input type="text" name="nama_vendor" id="nama_vendor" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Badan Hukum</label>
                        <select name="badan_hukum" id="badan_hukum" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="P">PT</option>
                            <option value="C">CV</option>
                            <option value="D">UD / Lainnya</option>
                        </select>
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
    const modal = document.getElementById('vendorModal');
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

function editVendor(data) {
    const form = document.getElementById('vendorForm');
    form.action = `/vendor/${data.idvendor}`;
    
    document.getElementById('nama_vendor').value = data.nama_vendor;
    document.getElementById('badan_hukum').value = data.badan_hukum;

    toggleModal(true);
}
</script>
@endsection