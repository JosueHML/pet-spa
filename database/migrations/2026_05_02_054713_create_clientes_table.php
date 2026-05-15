<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            $table->string('ci', 20)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->integer('puntos_frecuencia')->default(0);
            $table->enum('preferencia_notificacion', ['EMAIL', 'WHATSAPP', 'SMS'])->default('EMAIL');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};