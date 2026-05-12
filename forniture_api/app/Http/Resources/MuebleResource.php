<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuebleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
       return [
        'id' => $this->id,
        'nombre_producto' => $this->nombre,
        'descripcion' => $this->descripcion,
        'precio_venta' => $this->precio,
        'stock_disponible' => $this->stock,
        'color' => $this->color,
        'novedad' => $this->novedad,
        'activo' => $this->activo,
        'categoria' => $this->category?->nombre,
        'categoria_id' => $this->categoria_id, 
        'imagenes' => $this->galeria->pluck('url'),
    ];
    }
}
