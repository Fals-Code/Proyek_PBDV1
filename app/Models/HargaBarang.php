<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaBarang extends Model
{
    protected $table = 'v_harga_jual_otomatis';
    protected $primaryKey = 'idbarang';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'idbarang',
        'nama_barang',
        'nama_satuan',
        'harga_beli_terakhir',
        'stok_aktual',
        'margin_aktif',
        'harga_jual_otomatis',
    ];
}
