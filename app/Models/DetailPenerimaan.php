<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenerimaan extends Model
{
    protected $table = 'detail_penerimaan';
    protected $primaryKey = 'iddetail_penerimaan';
    public $timestamps = false;

    protected $fillable = [
        'idpenerimaan',
        'idbarang',
        'jumlah_terima',
        'harga_satuan_terima',
        'sub_total_terima'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
