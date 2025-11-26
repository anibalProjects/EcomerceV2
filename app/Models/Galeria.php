<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    protected $table = 'galerias';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'id_mueble',
        'ruta',
        'es_principal'
    ];
    public function Mueble() {
        return $this->belongsTo(Mueble::class);
    }
}
