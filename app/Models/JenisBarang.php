<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    protected $table = 'jenis_barang';
    protected $primaryKey = 'idjenis';
    public $timestamps = false;

    protected $fillable = ['nama_jenis', 'deskripsi'];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'idjenis', 'idjenis');
    }
}
