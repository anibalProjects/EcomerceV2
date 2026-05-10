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
            'precio_venta' => $this->precio,
            'categoria' => $this->category?->nombre,
            'imagenes' => $this->galeria->pluck('url'),
        ];
    }
}
