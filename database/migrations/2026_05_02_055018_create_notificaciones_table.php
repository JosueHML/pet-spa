<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('id_notificacion');
            $table->foreignId('id_usuario')->constrained('users');
            $table->enum('tipo', ['CONFIRMACION', 'RECORDATORIO_24H', 'RECORDATORIO_2H', 'LISTO_RECOGER', 'ENCUESTA', 'PROMOCION']);
            $table->text('mensaje');
            $table->enum('canal', ['EMAIL', 'WHATSAPP', 'SMS']);
            $table->string('destino', 200);
            $table->datetime('fecha_programacion')->nullable();
            $table->datetime('fecha_envio')->nullable();
            $table->enum('estado', ['PENDIENTE', 'ENVIADO', 'FALLIDO'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};