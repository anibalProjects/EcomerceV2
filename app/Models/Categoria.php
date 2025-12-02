<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'nombre',
        'descripcion'
    ];

    public function muebles() {
        return $this->hasMany(Mueble::class);
    }



    public static function getAllCategories() {
        $categorias = Categoria::all();
        return $categorias;
    }

    public static function getCategoriaPorId($id) {
        $categoria = Categoria::find($id);
        return $categoria;
    }
}
