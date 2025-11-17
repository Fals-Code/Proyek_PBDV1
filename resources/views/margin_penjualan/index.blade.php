@extends('layouts.app')

@section('title', 'Margin Penjualan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Margin Penjualan
            </h1>
            <p class="text-sm text-gray-600 mt-1">Manajemen persentase margin keuntungan penjualan</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filter Status --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-700">Filter Margin</h3>
            <form method="GET" action="{{ route('margin_penjualan.index') }}" class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="status" class="text-sm font-medium text-gray-600">Tampilkan:</label>
                    <select name="status" id="status" class="border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    </select>
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition-all duration-300">
                    Filter
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Persentase Margin</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($margin as $i => $m)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $i + 1 }}</td>
                        <td class="px-6 py-4 text-center font-bold text-indigo-600">{{ $m->persen_margin ?? $m->persen }}%</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold
                                {{ $m->status == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ $m->status == 1 ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <form action="{{ route('margin_penjualan.toggle', $m->idmargin_penjualan) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    onclick="return confirm('Aktifkan margin ini dan nonaktifkan yang lain?')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                    {{ $m->status == 1 ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <button onclick="editMargin({{ json_encode($m) }})"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                Edit
                            </button>

                            <form action="{{ route('margin_penjualan.destroy', $m->idmargin_penjualan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus margin ini?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">
                            Tidak ada data margin penjualan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="marginModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8 m-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Edit Margin</h2>
                <button onclick="toggleModal(false)" class="text-gray-500 hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="marginForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Margin (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="persen_margin"
                            id="persen_margin" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>

                <div class="flex justify-end mt-8 space-x-4">
                    <button type="button" onclick="toggleModal(false)"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-4 py-2 rounded-lg transition-all duration-300">Batal</button>
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(show) {
    const modal = document.getElementById('marginModal');
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
            
            const form = document.getElementById('marginForm');
            form.reset();
            document.getElementById('modalTitle').innerText = 'Edit Margin';
            form.action = "";
        }, 300);
    }
}

function editMargin(data) {
    const form = document.getElementById('marginForm');
    form.action = `/margin-penjualan/${data.idmargin_penjualan}`;
    document.getElementById('persen_margin').value = data.persen_margin ?? data.persen;
    toggleModal(true);
}
</script>
@endsection