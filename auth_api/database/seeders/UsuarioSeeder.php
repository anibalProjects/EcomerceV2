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
        // Usuario admin para pruebas
        Usuario::factory()->create([
            'nombre' => 'Admin',
            'apellido' => 'User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Hash::make('password')?
            'rol_id' => 1,
        ]);

        // Resto de usuarios
        Usuario::factory(10)->create();
    }
}
