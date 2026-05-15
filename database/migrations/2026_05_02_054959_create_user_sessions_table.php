<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id('id_sesion');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->text('token_jwt');
            $table->string('refresh_token', 255);
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->datetime('fecha_expiracion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};