<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaan';
    protected $primaryKey = 'idpenerimaan';
    public $timestamps = false;

    protected $fillable = [
        'idpengadaan',
        'iduser',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(DetailPenerimaan::class, 'idpenerimaan', 'idpenerimaan');
    }

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'idpengadaan', 'idpengadaan');
    }
}
