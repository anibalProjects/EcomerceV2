<?php

namespace App\Models;

use App\Models\CarritoProducto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function muebles() {
        return $this->belongsToMany(Mueble::class, 'carrito_productos')->withPivot('cantidad');
    }

    public function carritoProductos() {
        return $this->hasMany(CarritoProducto::class);
    }
}
