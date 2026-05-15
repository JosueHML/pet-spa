<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->unsignedBigInteger('id_mascota');
            $table->unsignedBigInteger('id_groomer');
            $table->unsignedBigInteger('id_servicio');
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->enum('estado', ['PENDIENTE', 'CONFIRMADA', 'EN_PROCESO', 'COMPLETADA', 'CANCELADA', 'NO_ASISTIO'])->default('PENDIENTE');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_mascota')->references('id_mascota')->on('mascotas');
            $table->foreign('id_groomer')->references('id_groomer')->on('groomers');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};