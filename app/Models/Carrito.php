<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'cantidad_productos',
        'precio',
        'id_usuario',
    ];

    public function Usuario() {
        return $this->belongsTo(Usuario::class);
    }

    public function Mueble() {
        return $this->belongsToMany(Mueble::class);
    }

    static function getCarritoActual($sesionId){
        $user = Auth::user();
        // Busca un carrito que coincida con el Usuario y si el carrito no existe lo creo
        return Carrito::firstOrCreate(
            [
                'usuario_id' => $user->id,
                'sesionId' => $sesionId
            ]
        );
    }
}
