<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mueble extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'categoria_id', 'stock', 'color', 'novedad', 'activo'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function galeria()
    {
        return $this->belongsToMany(Image::class, 'mueble_image');
    }
}
