<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create(['nombre' => 'Salón']);
        \App\Models\Category::create(['nombre' => 'Dormitorio']);
        \App\Models\Category::create(['nombre' => 'Oficina']);
        \App\Models\Category::create(['nombre' => 'Cocina']);
    }
}
