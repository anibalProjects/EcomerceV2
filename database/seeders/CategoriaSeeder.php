<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create([
            'nombre' => 'Baño',
            'descripcion' => 'Productos tecnológicos'
        ]);

        Categoria::create([
            'nombre' => 'Sofas',
            'descripcion' => 'Prendas de vestir'
        ]);

        Categoria::create([
            'nombre' => 'Cocina',
            'descripcion' => 'Artículos para el hogar'
        ]);
    }
}
