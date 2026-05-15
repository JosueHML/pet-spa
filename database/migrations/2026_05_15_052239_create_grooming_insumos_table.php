<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grooming_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ficha');
            $table->unsignedBigInteger('id_insumo');
            $table->integer('cantidad_usada')->default(0);
            $table->boolean('usado')->default(false);
            $table->timestamps();

            $table->foreign('id_ficha')->references('id_ficha')->on('fichas_grooming')->onDelete('cascade');
            $table->foreign('id_insumo')->references('id_insumo')->on('insumos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grooming_insumos');
    }
};