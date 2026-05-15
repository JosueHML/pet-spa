<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->id('id_admin');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->boolean('acceso_reportes')->default(true);
            $table->boolean('config_sistema')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};