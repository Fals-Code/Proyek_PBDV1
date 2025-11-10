@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Manajemen Role</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('role.store') }}" method="POST" class="mb-6 flex gap-3">
        @csrf
        <input type="text" name="nama_role" placeholder="Nama Role" required class="border rounded px-3 py-2 w-1/3">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah</button>
    </form>

    <table class="w-full border text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Nama Role</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $i => $role)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ $role->role }}</td>
                    <td class="p-2 text-center">
                        <button onclick="editRole({{ json_encode($role) }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</button>
                        <form action="{{ route('role.destroy', $role->idrole) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus role ini?')" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Edit Role -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/3">
        <h3 class="text-lg font-semibold mb-4">Edit Role</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="text-sm font-medium text-gray-700">Nama Role</label>
                <input type="text" name="nama_role" id="edit_nama_role" class="w-full border-gray-300 rounded-md mt-1" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editRole(role) {
        document.getElementById('edit_nama_role').value = role.role;
        document.getElementById('editForm').action = '/role/' + role.idrole;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
