<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable =
    [
        'rol'
    ];

    /* public function Usuarios() {
        return $this->belongsToMany(Usuario::class);
    } */
}
