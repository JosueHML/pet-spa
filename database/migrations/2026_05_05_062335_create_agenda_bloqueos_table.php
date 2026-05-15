<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_bloqueos', function (Blueprint $table) {
            $table->id('id_bloqueo');
            $table->unsignedBigInteger('id_groomer')->nullable();
            $table->date('fecha_bloqueo');
            $table->enum('tipo', ['FERIADO', 'MANTENIMIENTO', 'AUSENCIA']);
            $table->string('motivo', 200)->nullable();
            $table->enum('alcance', ['GLOBAL', 'INDIVIDUAL'])->default('INDIVIDUAL');
            $table->timestamps();

            $table->foreign('id_groomer')->references('id_groomer')->on('groomers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_bloqueos');
    }
};