<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groomers', function (Blueprint $table) {
            $table->id('id_groomer');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->string('especialidad', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->enum('turno', ['MAÑANA', 'TARDE', 'COMPLETO'])->default('COMPLETO');
            $table->integer('max_citas_diarias')->default(6);
            $table->integer('capacidad_simultanea')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groomers');
    }
};