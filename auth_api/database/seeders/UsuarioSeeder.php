<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin 
        Usuario::updateOrCreate(
            ['email' => 'admin@tienda.com'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Tienda',
                'password' => bcrypt('password'),
                'rol_id' => 1,
                'intentos' => 0,
            ]
        );

        // Gestor 
        Usuario::updateOrCreate(
            ['email' => 'gestor@tienda.com'],
            [
                'nombre' => 'Gestor',
                'apellido' => 'Tienda',
                'password' => bcrypt('password'),
                'rol_id' => 2,
                'intentos' => 0,
            ]
        );

        // Cliente 
        Usuario::updateOrCreate(
            ['email' => 'cliente@tienda.com'],
            [
                'nombre' => 'Cliente',
                'apellido' => 'Tienda',
                'password' => bcrypt('password'),
                'rol_id' => 3,
                'intentos' => 0,
            ]
        );
    }
}
