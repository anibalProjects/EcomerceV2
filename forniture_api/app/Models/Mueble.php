<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mueble extends Model
{

    use SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'categoria_id', 'stock', 'color', 'novedad', 'activo'];

    protected $casts = [
        'novedad' => 'boolean',
        'activo' => 'boolean',
        'precio' => 'float',
        'stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function galeria()
    {
        return $this->belongsToMany(Image::class, 'mueble_image')->withTimestamps();
    }

    public function scopeDeCategoria($query, $categoriaId)
    {
        if ($categoriaId) {
            return $query->where('categoria_id', $categoriaId);
        }
    }

    public function scopeOrdenarPrecio($query, $orden = 'asc')
    {
        return $query->orderBy('precio', $orden);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDeNombre($query, $nombre)
    {
        if ($nombre) {
            return $query->where('nombre', 'like', '%' . $nombre . '%');
        }
    }

    public function scopeRangoPrecio($query, $min, $max)
    {
        if ($min) {
            $query->where('precio', '>=', $min);
        }
        if ($max) {
            $query->where('precio', '<=', $max);
        }
        return $query;
    }

    public function scopeDeColor($query, $color)
    {
        if ($color) {
            return $query->where('color', 'like', '%' . $color . '%');
        }
    }

    public function scopeEsNovedad($query, $novedad)
    {
        if ($novedad) {
            return $query->where('novedad', true);
        }
    }
}
