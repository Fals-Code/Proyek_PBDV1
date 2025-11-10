<aside class="sidebar hidden lg:flex flex-col p-4 space-y-2">
    <div class="flex items-center mb-6 space-x-2">
        <i class="fas fa-cubes text-2xl text-indigo-400"></i>
        <h1 class="text-lg font-semibold">InventorySys</h1>
    </div>

    {{-- Navigasi utama --}}
    <nav class="flex flex-col space-y-1 text-sm">
        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line mr-2"></i> Dashboard
        </a>

        <a href="{{ route('barang.index') ?? '#' }}">
            <i class="fas fa-boxes mr-2"></i> Data Barang
        </a>

        <a href="{{ route('vendor.index') ?? '#' }}">
            <i class="fas fa-truck mr-2"></i> Vendor
        </a>

        <a href="{{ route('pengadaan.index') ?? '#' }}">
            <i class="fas fa-shopping-cart mr-2"></i> Pengadaan
        </a>

        <a href="{{ route('penjualan.index') ?? '#' }}">
            <i class="fas fa-cash-register mr-2"></i> Penjualan
        </a>

        <a href="{{ route('retur.index') ?? '#' }}">
            <i class="fas fa-undo-alt mr-2"></i> Retur
        </a>

        <a href="{{ route('laporan.index') ?? '#' }}">
            <i class="fas fa-file-alt mr-2"></i> Laporan
        </a>

        <a href="{{ route('pengaturan.index') ?? '#' }}">
            <i class="fas fa-cog mr-2"></i> Pengaturan
        </a>
    </nav>

    {{-- Footer --}}
    <div class="mt-auto text-xs text-gray-400 pt-4 border-t border-gray-600">
        © {{ date('Y') }} Sistem Inventory  
        <div>v1.0.0</div>
    </div>
</aside>
