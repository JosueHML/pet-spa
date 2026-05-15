<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('servicios')->insert([
            ['nombre_servicio' => 'Baño y Secado', 'precio' => 80.00, 'duracion_minutos' => 60],
            ['nombre_servicio' => 'Corte de Pelo', 'precio' => 120.00, 'duracion_minutos' => 90],
            ['nombre_servicio' => 'Baño + Corte', 'precio' => 180.00, 'duracion_minutos' => 120],
            ['nombre_servicio' => 'Corte de Uñas', 'precio' => 40.00, 'duracion_minutos' => 20],
        ]);
    }
}