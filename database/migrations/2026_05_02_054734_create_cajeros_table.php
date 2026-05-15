<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajeros', function (Blueprint $table) {
            $table->id('id_cajero');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->boolean('permisos_pagos')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cajeros');
    }
};