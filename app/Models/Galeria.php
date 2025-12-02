<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    protected $table = 'galerias';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'mueble_id',
        'ruta',
        'es_principal',
        'orden'
    ];
    public function mueble() {
        return $this->belongsTo(Mueble::class);
    }
}
