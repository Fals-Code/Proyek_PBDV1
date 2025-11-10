@extends('layouts.app')

@section('title', 'Data Vendor')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">🏢 Data Vendor (Supplier)</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif

    {{-- Tombol tambah --}}
    <button onclick="toggleModal(true)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        + Tambah Vendor
    </button>

    {{-- Tabel Vendor --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
        <table class="table-auto w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Nama Vendor</th>
                    <th class="p-2 text-center">Badan Hukum</th>
                    <th class="p-2 text-center">Status</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $index => $v)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $index + 1 }}</td>
                    <td class="p-2">{{ $v->nama_vendor }}</td>
                    <td class="p-2 text-center">
                        {{ $v->badan_hukum == 'P' ? 'PT' : ($v->badan_hukum == 'C' ? 'CV' : 'Lainnya') }}
                    </td>
                    <td class="p-2 text-center">
                        <span class="px-2 py-1 rounded text-xs {{ $v->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                            {{ $v->status }}
                        </span>
                    </td>
                    <td class="p-2 text-center space-x-2">
                        {{-- Tombol Edit --}}
                        <button onclick="editVendor({{ json_encode($v) }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                            Edit
                        </button>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('vendor.destroy', $v->idvendor) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs"
                                    onclick="return confirm('Hapus vendor ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah/Edit Vendor --}}
    <div id="vendorModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Vendor</h2>
            <form id="vendorForm" method="POST" action="{{ route('vendor.store') }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Nama Vendor</label>
                        <input type="text" name="nama_vendor" id="nama_vendor" class="w-full border rounded px-2 py-1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Badan Hukum</label>
                        <select name="badan_hukum" id="badan_hukum" class="w-full border rounded px-2 py-1">
                            <option value="P">PT</option>
                            <option value="C">CV</option>
                            <option value="D">UD / Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" onclick="toggleModal(false)" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1 rounded">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(show) {
    document.getElementById('vendorModal').classList.toggle('hidden', !show);
    if (!show) document.getElementById('vendorForm').reset();
}

function editVendor(data) {
    toggleModal(true);
    document.getElementById('modalTitle').innerText = 'Edit Vendor';
    const form = document.getElementById('vendorForm');
    form.action = `/vendor/${data.idvendor}`;
    form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
    document.getElementById('nama_vendor').value = data.nama_vendor;
    document.getElementById('badan_hukum').value = data.badan_hukum;
}
</script>
@endsection
