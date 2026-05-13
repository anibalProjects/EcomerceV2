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
            'color' => 'grey',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Cama King Size',
            'descripcion' => 'Cama de madera maciza con cabecero integrado y somier incluido.',
            'precio' => 800.00,
            'stock' => 15,
            'color' => 'oak',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 2,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Escritorio Elevable',
            'descripcion' => 'Escritorio ergonómico con motor eléctrico para trabajar de pie o sentado.',
            'precio' => 320.00,
            'stock' => 5,
            'color' => 'white',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 3,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Mesa de Comedor',
            'descripcion' => 'Mesa redonda de cristal templado para 6 comensales.',
            'precio' => 250.00,
            'stock' => 8,
            'color' => 'white',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Silla de Oficina Pro',
            'descripcion' => 'Silla ergonómica con soporte lumbar y reposacabezas ajustable.',
            'precio' => 180.00,
            'stock' => 20,
            'color' => 'black',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 3,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Estantería Industrial',
            'descripcion' => 'Estantería de madera y metal estilo industrial para libros o decoración.',
            'precio' => 120.00,
            'stock' => 12,
            'color' => 'walnut',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Mueble de Cocina Modular',
            'descripcion' => 'Mueble bajo para cocina con 3 cajones y acabado lacado.',
            'precio' => 210.00,
            'stock' => 7,
            'color' => 'white',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 4,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Armario de Dormitorio',
            'descripcion' => 'Armario de 2 puertas correderas con espejo central.',
            'precio' => 550.00,
            'stock' => 4,
            'color' => 'black',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 2,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Butaca de Terciopelo',
            'descripcion' => 'Butaca elegante con patas de madera y tapizado suave en color esmeralda.',
            'precio' => 185.50,
            'stock' => 6,
            'color' => 'green',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Mesita de Noche Vintage',
            'descripcion' => 'Mesita de noche con dos cajones y tiradores de bronce tallados a mano.',
            'precio' => 75.00,
            'stock' => 24,
            'color' => 'walnut',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 2,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Aparador Nórdico',
            'descripcion' => 'Mueble bajo con amplio almacenaje, puertas correderas y acabado en madera clara.',
            'precio' => 290.00,
            'stock' => 3,
            'color' => 'oak',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Silla de Comedor Velvet',
            'descripcion' => 'Silla de comedor con respaldo curvo y tapizado en lino gris de alta calidad.',
            'precio' => 89.99,
            'stock' => 32,
            'color' => 'grey',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 1,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Escritorio Juvenil',
            'descripcion' => 'Escritorio compacto ideal para espacios pequeños, con pasacables integrado.',
            'precio' => 115.00,
            'stock' => 12,
            'color' => 'blue',
            'novedad' => true,
            'activo' => true,
            'categoria_id' => 3,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Isla de Cocina Portátil',
            'descripcion' => 'Mesa auxiliar con ruedas y superficie de preparación de madera de bambú.',
            'precio' => 145.00,
            'stock' => 5,
            'color' => 'brown',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 4,
        ]);

        \App\Models\Mueble::create([
            'nombre' => 'Espejo de Cuerpo Entero',
            'descripcion' => 'Espejo con marco de madera minimalista para dormitorio o vestidor.',
            'precio' => 120.00,
            'stock' => 10,
            'color' => 'white',
            'novedad' => false,
            'activo' => true,
            'categoria_id' => 2,
        ]);
    }
}
