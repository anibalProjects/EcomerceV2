<?php

namespace App\Models;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;

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
        'imagen_principal',
        'activo'
    ];
    public function categoria() {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function galeria() {
        return $this->hasMany(Galeria::class);
    }

    public function Carrito() {
        return $this->belongsToMany(Carrito::class);
    }

    public function getImagenRutaAttribute()
    {
        $galeria = Galeria::where('mueble_id', $this->id)->get();
        return $galeria;
    }




}
