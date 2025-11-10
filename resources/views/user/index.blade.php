@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Manajemen User</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah User -->
    <form action="{{ route('user.store') }}" method="POST" class="mb-6 grid grid-cols-4 gap-4">
        @csrf
        <div>
            <label class="text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-700">Role</label>
            <select name="idrole" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $r)
                    <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah</button>
        </div>
    </form>

    <!-- Tabel User -->
    <table class="w-full border text-sm text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Username</th>
                <th class="p-2 border">Role</th>
                <th class="p-2 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $i => $u)
                <tr class="hover:bg-gray-50 border-b">
                    <td class="p-2">{{ $i + 1 }}</td>
                    <td class="p-2">{{ $u->username }}</td>
                    <td class="p-2">{{ $u->role }}</td>
                    <td class="p-2 text-center space-x-2">
                        <button onclick="editUser({{ json_encode($u) }})" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Edit</button>
                        <form action="{{ route('user.destroy', $u->iduser) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus user ini?')" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-1/3">
        <h3 class="text-lg font-semibold mb-4">Edit User</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="edit_username" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="text-sm font-medium text-gray-700">Password (Opsional)</label>
                <input type="password" name="password" id="edit_password" class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-3">
                <label class="text-sm font-medium text-gray-700">Role</label>
                <select name="idrole" id="edit_idrole" class="w-full border rounded px-3 py-2">
                    @foreach ($roles as $r)
                        <option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editUser(user) {
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_idrole').value = user.idrole;
        document.getElementById('editForm').action = '/user/' + user.iduser;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
