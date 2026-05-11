<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        $roles = [
            ['id' => 1, 'rol' => 'admin',      'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'rol' => 'gestor',     'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'rol' => 'cliente',     'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(['id' => $rol['id']], $rol);
        }
    }
}
