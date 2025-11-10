@extends('layouts.app')

@section('title', 'Data Satuan')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">📏 Master Data Satuan</h1>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <button onclick="toggleModal(true)"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
        + Tambah Satuan
    </button>

    {{-- Tabel Data --}}
    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto mt-4">
        <table class="table-auto w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">#</th>
                    <th class="p-2 text-left">Nama Satuan</th>
                    <th class="p-2 text-center">Status</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($satuan as $i => $s)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ $s->nama_satuan }}</td>
                    <td class="p-2 text-center">
                        <span class="px-2 py-1 rounded text-xs {{ $s->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                            {{ $s->status }}
                        </span>
                    </td>
                    <td class="p-2 text-center space-x-2">
                        <button onclick="editSatuan({{ json_encode($s) }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                            Edit
                        </button>
                        <form action="{{ route('satuan.destroy', $s->idsatuan) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus satuan ini?')"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah/Edit --}}
    <div id="satuanModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-1/3 p-6">
            <h2 id="modalTitle" class="text-lg font-semibold mb-4">Tambah Satuan</h2>
            <form id="satuanForm" method="POST" action="{{ route('satuan.store') }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Nama Satuan</label>
                        <input type="text" name="nama_satuan" id="nama_satuan" class="w-full border rounded px-2 py-1" required>
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
    const modal = document.getElementById('satuanModal');
    modal.classList.toggle('hidden', !show);
    if (!show) document.getElementById('satuanForm').reset();
}

function editSatuan(data) {
    toggleModal(true);
    document.getElementById('modalTitle').innerText = 'Edit Satuan';
    const form = document.getElementById('satuanForm');
    form.action = `/satuan/${data.idsatuan}`;
    form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
    document.getElementById('nama_satuan').value = data.nama_satuan;
}
</script>
@endsection
