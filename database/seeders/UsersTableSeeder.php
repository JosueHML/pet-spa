<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Principal',
            'email' => 'admin@petspa.com',
            'password' => Hash::make('Admin123!'),
            'account_status' => 'ACTIVO',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Carlos Cliente',
            'email' => 'cliente@petspa.com',
            'password' => Hash::make('Cliente123!'),
            'account_status' => 'ACTIVO',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}