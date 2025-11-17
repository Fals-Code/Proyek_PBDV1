@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-2.305c.395-.44-.023-1.12-1.125-1.125H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                </svg>
                Manajemen User
            </h1>
            <p class="text-sm text-gray-600 mt-1">Pengelolaan akun dan hak akses pengguna sistem</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Form Tambah User --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Tambah User Baru</h3>
        <form action="{{ route('user.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div>
                <label for="idrole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="idrole" id="idrole" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->idrole }}">{{ $r->role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah User
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Username</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $i => $u)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-600">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $u->username }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->role }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button onclick="editUser({{ json_encode($u) }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">Edit</button>
                                <form action="{{ route('user.destroy', $u->iduser) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus user ini?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg m-4 transform transition-all duration-300 scale-95 opacity-0" id="editModalContent">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Edit User</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="edit_username" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                </div>
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">Password (Opsional)</label>
                    <input type="password" name="password" id="edit_password" placeholder="Isi jika ingin mengubah password" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label for="edit_idrole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="idrole" id="edit_idrole" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                        @foreach ($roles as $r)
                            <option value="{{ $r->idrole }}">{{ $r->role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition-all duration-300">Batal</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const editModal = document.getElementById('editModal');
    const editModalContent = document.getElementById('editModalContent');

    function editUser(user) {
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_idrole').value = user.idrole;
        document.getElementById('edit_password').value = '';
        document.getElementById('editForm').action = '/user/' + user.iduser;
        editModal.classList.remove('hidden');
        setTimeout(() => {
            editModal.classList.add('opacity-100');
            editModalContent.classList.remove('scale-95', 'opacity-0');
            editModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        editModalContent.classList.remove('scale-100', 'opacity-100');
        editModalContent.classList.add('scale-95', 'opacity-0');
        editModal.classList.remove('opacity-100');
        setTimeout(() => {
            editModal.classList.add('hidden');
        }, 300);
    }

    // Close modal on escape key press
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !editModal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endsection