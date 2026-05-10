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
}
