<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Models\User $user
 * @property \App\Models\MarginPenjualan $margin
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\DetailPenjualan[] $detail
 */
class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'idpenjualan';
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
        'iduser',
        'idmargin_penjualan'
    ];

    // Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'iduser', 'iduser');
    }

    // Relasi ke tabel margin_penjualan
    public function margin()
    {
        return $this->belongsTo(\App\Models\MarginPenjualan::class, 'idmargin_penjualan', 'idmargin_penjualan');
    }

    // Relasi ke tabel detail_penjualan
    public function detail()
    {
        return $this->hasMany(\App\Models\DetailPenjualan::class, 'idpenjualan', 'idpenjualan');
    }
}
