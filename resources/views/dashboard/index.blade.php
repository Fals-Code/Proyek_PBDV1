@extends('layouts.app')
@section('content')
<div class="p-6 space-y-6">
  <h1 class="text-2xl font-semibold mb-4"> Dashboard Sistem Inventory</h1>

  {{--  Ringkasan utama --}}
  <div class="grid grid-cols-4 gap-4">
    <x-dashboard.card title="Total Barang" :value="$summary->total_barang" icon="📦" />
    <x-dashboard.card title="Total Vendor" :value="$summary->total_vendor" icon="🏢" />
    <x-dashboard.card title="Total Penjualan" :value="$summary->total_penjualan" icon="💰" />
    <x-dashboard.card title="Total Stok" :value="$summary->total_stok_sistem" icon="📈" />
  </div>

  {{--  Grafik Penjualan vs Pengadaan --}}
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Tren Penjualan & Pengadaan Bulanan</h2>
    <canvas id="chartPenjualan"></canvas>
  </div>

  {{-- 🛍 Barang terlaris --}}
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-3">Barang Terlaris</h2>
    <table class="table-auto w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 text-left">Nama Barang</th>
          <th class="p-2">Satuan</th>
          <th class="p-2">Total Terjual</th>
          <th class="p-2">Pendapatan</th>
        </tr>
      </thead>
      <tbody>
        @foreach($terlaris as $t)
        <tr>
          <td class="p-2">{{ $t->nama_barang }}</td>
          <td class="p-2 text-center">{{ $t->nama_satuan }}</td>
          <td class="p-2 text-center">{{ $t->total_terjual }}</td>
          <td class="p-2 text-right">Rp {{ number_format($t->total_pendapatan, 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
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
        { label: 'Penjualan', data: penjualanData, borderWidth: 2, borderColor: '#3b82f6' },
        { label: 'Pengadaan', data: pengadaanData, borderWidth: 2, borderColor: '#10b981' },
      ]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
</script>
@endsection
