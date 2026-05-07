<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MuebleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Mueble::create([
            'nombre' => 'Sofá Minimalista',
            'descripcion' => 'Sofá de 3 plazas con diseño nórdico y tela antimanchas.',
            'precio' => 450.00,
            'stock' => 10,
            'color' => 'Gris',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Cama King Size',
            'descripcion' => 'Cama de madera maciza con cabecero integrado y somier incluido.',
            'precio' => 800.00,
            'stock' => 15,
            'color' => 'Roble',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 2,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Escritorio Elevable',
            'descripcion' => 'Escritorio ergonómico con motor eléctrico para trabajar de pie o sentado.',
            'precio' => 320.00,
            'stock' => 5,
            'color' => 'Blanco',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 3,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Mesa de Comedor',
            'descripcion' => 'Mesa redonda de cristal templado para 6 comensales.',
            'precio' => 250.00,
            'stock' => 8,
            'color' => 'Transparente',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Silla de Oficina Pro',
            'descripcion' => 'Silla ergonómica con soporte lumbar y reposacabezas ajustable.',
            'precio' => 180.00,
            'stock' => 20,
            'color' => 'Negro',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 3,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Estantería Industrial',
            'descripcion' => 'Estantería de madera y metal estilo industrial para libros o decoración.',
            'precio' => 120.00,
            'stock' => 12,
            'color' => 'Madera Oscura',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Mueble de Cocina Modular',
            'descripcion' => 'Mueble bajo para cocina con 3 cajones y acabado lacado.',
            'precio' => 210.00,
            'stock' => 7,
            'color' => 'Crema',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 4,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Armario de Dormitorio',
            'descripcion' => 'Armario de 2 puertas correderas con espejo central.',
            'precio' => 550.00,
            'stock' => 4,
            'color' => 'Wengué',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 2,
        ]);
    }
}
