@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Header Section --}}
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Dashboard Sistem Inventory
            </h1>
            <p class="text-sm text-gray-600 mt-1">Overview sistem manajemen inventory dan transaksi</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Total Barang Card --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-500 p-3 rounded-lg shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-600 font-medium">Total Barang</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $summary->total_barang }}</p>
                </div>
            </div>
            <div class="flex items-center text-xs text-blue-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span>Produk terdaftar</span>
            </div>
        </div>

        {{-- Total Vendor Card --}}
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-500 p-3 rounded-lg shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-green-600 font-medium">Total Vendor</p>
                    <p class="text-3xl font-bold text-green-800">{{ $summary->total_vendor }}</p>
                </div>
            </div>
            <div class="flex items-center text-xs text-green-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Supplier aktif</span>
            </div>
        </div>

        {{-- Total Penjualan Card --}}
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-500 p-3 rounded-lg shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-yellow-600 font-medium">Total Penjualan</p>
                    <p class="text-3xl font-bold text-yellow-800">{{ $summary->total_penjualan }}</p>
                </div>
            </div>
            <div class="flex items-center text-xs text-yellow-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Transaksi selesai</span>
            </div>
        </div>

        {{-- Total Stok Card --}}
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-500 p-3 rounded-lg shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm text-purple-600 font-medium">Total Stok</p>
                    <p class="text-3xl font-bold text-purple-800">{{ $summary->total_stok_sistem }}</p>
                </div>
            </div>
            <div class="flex items-center text-xs text-purple-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                <span>Unit tersedia</span>
            </div>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <h2 class="text-xl font-bold text-white">Tren Penjualan & Pengadaan Bulanan</h2>
            </div>
            <p class="text-sm text-blue-100 mt-1">Perbandingan nilai penjualan dan pengadaan per bulan</p>
        </div>
        
        <div class="p-6">
            <canvas id="chartPenjualan" class="w-full" style="height: 350px;"></canvas>
        </div>
    </div>

    {{-- Barang Terlaris Section --}}
    <div class="bg-white border-2 border-gray-200 rounded-xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <h2 class="text-xl font-bold text-white">Barang Terlaris</h2>
            </div>
            <p class="text-sm text-yellow-100 mt-1">Produk dengan penjualan tertinggi</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold text-gray-700">
                            <div class="flex items-center gap-2">
                                <span class="bg-orange-500 text-white px-2 py-1 rounded-lg text-xs">No</span>
                                Nama Barang
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Satuan</th>
                        <th class="px-6 py-4 text-center font-semibold text-gray-700">Total Terjual</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-700">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terlaris as $index => $t)
                    <tr class="border-b hover:bg-orange-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-orange-400 to-yellow-400 text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <span class="font-medium text-gray-800">{{ $t->nama_barang }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $t->nama_satuan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full font-semibold">
                                {{ $t->total_terjual }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-green-700 text-lg">
                                Rp {{ number_format($t->total_pendapatan, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-700">
                            Total Pendapatan dari Produk Terlaris:
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-2xl font-bold text-green-700">
                                Rp {{ number_format($terlaris->sum('total_pendapatan'), 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </tbody>
            </table>
        </div>
    </div>
</div>

{{-- JS Chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('chartPenjualan').getContext('2d');
  const labels = {!! json_encode($penjualan->pluck('periode')) !!};
  const penjualanData = {!! json_encode($penjualan->pluck('total_penjualan')) !!};
  const pengadaanData = {!! json_encode($pengadaan->pluck('total_nilai_pengadaan')) !!};

  new Chart(ctx, {
    type: 'line',
    data: {
      labels,
      datasets: [
        { 
          label: 'Penjualan', 
          data: penjualanData, 
          borderWidth: 3, 
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          fill: true,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: '#3b82f6',
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        },
        { 
          label: 'Pengadaan', 
          data: pengadaanData, 
          borderWidth: 3, 
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          fill: true,
          tension: 0.4,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointBackgroundColor: '#10b981',
          pointBorderColor: '#fff',
          pointBorderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            usePointStyle: true,
            padding: 20,
            font: {
              size: 13,
              weight: '600'
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: {
            size: 14,
            weight: 'bold'
          },
          bodyFont: {
            size: 13
          },
          callbacks: {
            label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
              return label;
            }
          }
        }
      },
      scales: { 
        y: { 
          beginAtZero: true,
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          ticks: {
            callback: function(value) {
              return 'Rp ' + value.toLocaleString('id-ID');
            },
            font: {
              size: 12
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            font: {
              size: 12
            }
          }
        }
      }
    }
  });
</script>
@endsection