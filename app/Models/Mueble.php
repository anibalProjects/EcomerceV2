<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Mueble extends Model
{
    use HasFactory;
    protected $table = 'muebles';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'color',
        'stock',
        'novedad',
        'materiales',
        'dimensiones',
        'activo'
    ];
    public function Categoria() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function Galeria() {
        return $this->hasOne(Galeria::class);
    }

    public function Carrito() {
        return $this->belongsToMany(Carrito::class);
    }




}
