<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'nombre_producto' => 'Shampoo Premium para Perros',
                'sku' => 'SH001',
                'categoria' => 'Higiene',
                'stock_actual' => 50,
                'precio' => 45.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_producto' => 'Collar Antipulgas',
                'sku' => 'CO002',
                'categoria' => 'Accesorios',
                'stock_actual' => 30,
                'precio' => 85.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_producto' => 'Cepillo Profesional',
                'sku' => 'CE003',
                'categoria' => 'Herramientas',
                'stock_actual' => 20,
                'precio' => 120.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_producto' => 'Alimento Premium Adultos',
                'sku' => 'AL004',
                'categoria' => 'Alimentos',
                'stock_actual' => 100,
                'precio' => 280.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre_producto' => 'Juguete Pelota Squeaker',
                'sku' => 'JU005',
                'categoria' => 'Juguetes',
                'stock_actual' => 45,
                'precio' => 35.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}