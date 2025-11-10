<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    protected $table = 'pengadaan';
    protected $primaryKey = 'idpengadaan';
    public $timestamps = false;

    protected $fillable = [
        'user_iduser',
        'vendor_idvendor',
        'subtotal_nilai',
        'ppn',
        'total_nilai',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_idvendor', 'idvendor');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_iduser', 'iduser');
    }

    public function details()
    {
        return $this->hasMany(DetailPengadaan::class, 'idpengadaan', 'idpengadaan');
    }

    // app/Models/Pengadaan.php
    public function scopePending($query)
    {
        return $query->where('status', 'P');
    }
}
