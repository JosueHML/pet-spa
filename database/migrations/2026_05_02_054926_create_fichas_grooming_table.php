<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas_grooming', function (Blueprint $table) {
            $table->id('id_ficha');
            $table->unsignedBigInteger('id_cita');
            $table->enum('estado_ficha', ['ABIERTA', 'COMPLETADA'])->default('ABIERTA');
            $table->text('observaciones')->nullable();
            $table->json('checklist_json')->nullable();
            $table->json('fotos_urls')->nullable();
            $table->datetime('fecha_cierre')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_cita')->references('id_cita')->on('citas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas_grooming');
    }
};