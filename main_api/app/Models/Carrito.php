<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'usuario_id',
        'sesionId',
        'cantidad_productos',
        'precio'
    ];

    public function Usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function muebles() {
        return $this->belongsToMany(Mueble::class, 'carrito_productos')->withPivot('cantidad');
    }
}
