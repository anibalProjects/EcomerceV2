<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;
use App\Models\Mueble;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URLs de ejemplo de Unsplash para muebles
        $urls = [
            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc', // Sofá
            'https://images.unsplash.com/photo-1505691938895-1758d7feb511', // Dormitorio
            'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd', // Oficina
            'https://images.unsplash.com/photo-1556912173-3bb406ef7e77', // Cocina
            'https://images.unsplash.com/photo-1524758631624-e2822e304c36', // Oficina Pro
            'https://images.unsplash.com/photo-1594026112284-02bb6f3352fe', // Mesa
        ];

        foreach ($urls as $url) {
            Image::create(['url' => $url]);
        }

        // Asociar imágenes a muebles de forma aleatoria
        $muebles = Mueble::all();
        $images = Image::all();

        foreach ($muebles as $mueble) {
            // Cada mueble tendrá entre 1 y 2 imágenes aleatorias
            $randomImages = $images->random(rand(1, 2))->pluck('id');
            $mueble->galeria()->attach($randomImages);
        }
    }
}
