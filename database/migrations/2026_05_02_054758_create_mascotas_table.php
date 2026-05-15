<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id('id_mascota');
            $table->unsignedBigInteger('id_cliente');
            $table->string('nombre_mascota', 100);
            $table->string('raza', 100)->nullable();
            $table->enum('tamanio', ['PEQUEÑO', 'MEDIANO', 'GRANDE', 'EXTRA_GRANDE']);
            $table->integer('edad_meses')->nullable();
            $table->text('alergias')->nullable();
            $table->text('vacunas')->nullable();
            $table->text('restricciones')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};