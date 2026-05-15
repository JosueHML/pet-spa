<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('primer_ingreso')->default(0);
            $table->datetime('ultimo_cambio_password')->nullable();
            $table->datetime('password_expira')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['primer_ingreso', 'ultimo_cambio_password', 'password_expira']);
        });
    }
};