@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Edit Pengadaan #{{ $pengadaan->idpengadaan }}</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card p-3 shadow-sm">
        <form action="{{ route('pengadaan.update', $pengadaan->idpengadaan) }}" method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Vendor</label>
                    <select class="form-control" name="vendor_idvendor" required>
                        @foreach ($vendors as $v)
                            <option value="{{ $v->idvendor }}" {{ $pengadaan->vendor_idvendor == $v->idvendor ? 'selected' : '' }}>
                                {{ $v->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="barangList">
                @foreach ($pengadaan->details as $d)
                    <div class="row mb-2 barang-item">
                        <div class="col-md-4">
                            <select class="form-control" name="idbarang">
                                @foreach ($barang as $b)
                                    <option value="{{ $b->idbarang }}" {{ $b->idbarang == $d->idbarang ? 'selected' : '' }}>
                                        {{ $b->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="jumlah" value="{{ $d->jumlah }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="harga" value="{{ $d->harga_satuan }}" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger removeBarang">X</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="addBarang" class="btn btn-sm btn-secondary mt-2">+ Tambah Barang</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>

        <hr>
        <form action="{{ route('pengadaan.cancel', $pengadaan->idpengadaan) }}" method="POST" onsubmit="return confirm('Yakin batalkan pengadaan ini?')">
            @csrf
            <div class="mt-3">
                <label>Alasan Pembatalan</label>
                <textarea name="alasan" class="form-control" placeholder="Tuliskan alasan pembatalan..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger mt-2">❌ Batalkan Pengadaan</button>
        </form>
    </div>
</div>

<script>
document.getElementById('addBarang').addEventListener('click', function() {
    const div = document.createElement('div');
    div.classList.add('row', 'mb-2', 'barang-item');
    div.innerHTML = `
        <div class="col-md-4">
            <select class="form-control" name="idbarang">
                @foreach ($barang as $b)
                    <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="jumlah" placeholder="Jumlah" class="form-control" required>
        </div>
        <div class="col-md-3">
            <input type="number" name="harga" placeholder="Harga" class="form-control" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger removeBarang">X</button>
        </div>
    `;
    document.getElementById('barangList').appendChild(div);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('removeBarang')) {
        e.target.closest('.barang-item').remove();
    }
});
</script>
@endsection
