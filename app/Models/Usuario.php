<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'id_rol',
        'nombre',
        'correo',
        'password'
    ];

    public function Carrito() {
        return $this->hasOne(Carrito::class);
    }

    public function Rol() {
        return $this->hasOne(Rol::class);
    }
}
