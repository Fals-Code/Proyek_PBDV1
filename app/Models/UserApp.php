<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApp extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'idrole'
    ];

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole', 'idrole');
    }
}
