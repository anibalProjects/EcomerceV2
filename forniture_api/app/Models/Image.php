<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['url'];

    public function muebles()
    {
        return $this->belongsToMany(Mueble::class, 'mueble_image');
    }
}
