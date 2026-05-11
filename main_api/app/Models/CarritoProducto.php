<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoProducto extends Model
{
    protected $fillable = [
        'carrito_id',
        'mueble_id',
        'cantidad',
    ];

    protected $table = 'carrito_productos';

    //Funccion que me dice a que carrito pertenece el producto
    public function carrito() {
        return $this->belongsTo(Carrito::class);
    }
}
