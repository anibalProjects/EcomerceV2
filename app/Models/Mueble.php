<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mueble extends Model
{
    protected $table = 'muebles';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'id_categoria',
        'nombre',
        'descripcion',
        'precio',
        'color',
        'stock',
        'novedad',
        'material',
        'dimensiones',
        'activo'
    ];
    /* public function Categoria() {
        return $this->hasMany(Categoria::class);
    } */
    /* public function Galeria() {
        return $this->hasOne(Galeria::class);
    } */

    public function Carrito() {
        return $this->belongsToMany(Carrito::class);
    }


}
