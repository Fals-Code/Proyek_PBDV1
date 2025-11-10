<header class="bg-white shadow flex justify-between items-center px-6 py-3">
    {{-- Left: Search --}}
    <div class="flex items-center space-x-3">
        <button class="lg:hidden text-gray-600 focus:outline-none">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <div class="relative">
            <input 
                type="text" 
                placeholder="Cari data..." 
                class="border rounded-lg pl-9 pr-3 py-2 text-sm focus:ring focus:ring-indigo-100 focus:border-indigo-500"
            >
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
    </div>

    {{-- Right: Notif & User --}}
    <div class="flex items-center space-x-5">
        {{-- Notification --}}
        <button class="relative focus:outline-none">
            <i class="far fa-bell text-gray-500 text-lg"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5">3</span>
        </button>

        {{-- User Menu --}}
        <div class="relative group">
            <button class="flex items-center space-x-2 focus:outline-none">
                <i class="fas fa-user-circle text-2xl text-gray-600"></i>
                <span class="text-sm text-gray-700">Admin</span>
                <i class="fas fa-chevron-down text-xs text-gray-500"></i>
            </button>
            <div class="absolute right-0 hidden group-hover:block bg-white shadow rounded w-40 mt-2">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
