<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroomerTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('groomers')->insert([
            'id_usuario' => 1,
            'especialidad' => 'Corte y Baño profesional',
            'telefono' => '777111222',
            'turno' => 'COMPLETO',
            'max_citas_diarias' => 8,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}