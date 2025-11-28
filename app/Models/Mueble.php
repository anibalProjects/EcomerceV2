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
    /* public function Categoria() {
        return $this->hasMany(Categoria::class);
    } */
    /* public function Galeria() {
        return $this->hasOne(Galeria::class);
    } */

    public function Carrito() {
        return $this->belongsToMany(Carrito::class);
    }

    public function filtrar (Request $request) {
        $filtro = [];

        if($request->has('filtro') && is_array($request->filtro)){
             foreach ($request->filtro as $i => $valor) {
                if (!$valor == null) {
                    $filtro[$i] = $valor;
                }
            }
        }

        $mueblesFiltrados = Mueble::all();
        if (isset($filtro['nombre'])) {
            $mueblesFiltrados = $this->filtrarPorNombre($filtro['nombre'], $mueblesFiltrados);
        }
        if (isset($filtro['categoria_id'])) {
            $mueblesFiltrados = $this->filtrarPorCategoria($filtro['categoria_id'], $mueblesFiltrados);
        }
        if (isset($filtro['precio_min']) && isset($filtro['precio_max'])) {
            $mueblesFiltrados = $this->filtrarPorPrecio($filtro['precio_min'], $filtro['precio_max'], $mueblesFiltrados);
        }
        if (isset($filtro['color'])) {
            $mueblesFiltrados = $this->filtrarPorColor($filtro['color'], $mueblesFiltrados);
        }

        return $this->ordenar($mueblesFiltrados, $request->orden, $filtro);
    }


}
