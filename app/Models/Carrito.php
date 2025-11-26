<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'cantidad_productos',
        'precio',
        'id_usuario',
    ];

    public function Usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function Mueble() {
        return $this->belongsToMany(Mueble::class);
    }
}
