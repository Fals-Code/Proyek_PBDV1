<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'idjenis',
        'idsatuan',
        'status',
    ];

    // ✅ Relasi ke tabel jenis_barang
    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'idjenis', 'idjenis');
    }

    // ✅ Relasi ke tabel satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'idsatuan', 'idsatuan');
    }
}
