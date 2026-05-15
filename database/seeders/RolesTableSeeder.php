<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre_rol' => 'ADMINISTRADOR', 'descripcion' => 'Acceso total al sistema'],
            ['nombre_rol' => 'CAJERO', 'descripcion' => 'Gestión de agenda, cobros y facturación'],
            ['nombre_rol' => 'GROOMER', 'descripcion' => 'Atención de citas y fichas de grooming'],
            ['nombre_rol' => 'CLIENTE', 'descripcion' => 'Autogestión de citas e historial'],
        ]);
    }
}