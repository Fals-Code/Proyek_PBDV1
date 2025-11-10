<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';       // nama tabel di DB kamu
    protected $primaryKey = 'iduser';
    public $timestamps = false;      // karena di tabel kamu tidak ada created_at / updated_at

    protected $fillable = [
        'username',
        'password',
        'idrole',
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
