<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Usuario extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'rol_id',
        'nombre',
        'apellido',
        'email',
        'password'
    ];

    public function Carrito() {
        return $this->hasOne(Carrito::class);
    }

    public function Rol() {
        return $this->hasOne(Rol::class);
    }
}
