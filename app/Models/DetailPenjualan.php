<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $iddetail_penjualan
 * @property int $idpenjualan
 * @property int $idbarang
 * @property int $jumlah
 * @property float $harga_satuan
 * @property float $subtotal
 * @property \App\Models\Barang $barang
 * @property \App\Models\Penjualan $penjualan
 */
class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'iddetail_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'idpenjualan',
        'idbarang',
        'jumlah',
        'harga_satuan',
        'subtotal'
    ];

    /**
     * Relasi ke tabel penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(\App\Models\Penjualan::class, 'idpenjualan', 'idpenjualan');
    }

    /**
     * Relasi ke tabel barang
     */
    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class, 'idbarang', 'idbarang');
    }
}
