@extends('layouts.app')

@section('title', 'Manajemen Role')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                Manajemen Role
            </h1>
            <p class="text-sm text-gray-600 mt-1">Pengelolaan peran dan hak akses pengguna</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Sukses!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Form Tambah Role --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Tambah Role Baru</h3>
        <form action="{{ route('role.store') }}" method="POST" class="flex items-center gap-4">
            @csrf
            <div class="flex-grow">
                <label for="nama_role" class="sr-only">Nama Role</label>
                <input type="text" name="nama_role" id="nama_role" placeholder="Masukkan nama role baru..." required 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-300">
            </div>
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah
            </button>
        </form>
    </div>

    {{-- Tabel Role --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama Role</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $i => $role)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-600">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $role->role }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button onclick="editRole({{ json_encode($role) }})" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">Edit</button>
                                <form action="{{ route('role.destroy', $role->idrole) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus role ini?')" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">
                                Belum ada data role.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Role -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md m-4 transform transition-all duration-300 scale-95 opacity-0" id="editModalContent">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Edit Role</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_nama_role" class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                    <input type="text" name="nama_role" id="edit_nama_role" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition" required>
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

    function editRole(role) {
        document.getElementById('edit_nama_role').value = role.role;
        document.getElementById('editForm').action = '/role/' + role.idrole;
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