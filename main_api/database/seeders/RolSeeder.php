<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['rol' => 'Administrador'],
            ['rol' => 'Gestor'],
            ['rol' => 'Cliente'],
        ]);
    }
}
