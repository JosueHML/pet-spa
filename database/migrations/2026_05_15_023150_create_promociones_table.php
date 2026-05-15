<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promociones', function (Blueprint $table) {
            $table->id('id_promocion');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['PORCENTAJE', 'MONTO_FIJO', 'COMPRA_XXX']);
            $table->decimal('valor_descuento', 10, 2);
            $table->decimal('compra_minima', 10, 2)->default(0);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('aplica_a', ['PRODUCTOS', 'SERVICIOS', 'AMBOS']);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla pivote para aplicar promociones a productos específicos
        Schema::create('promocion_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promocion');
            $table->unsignedBigInteger('id_producto');
            $table->timestamps();

            $table->foreign('id_promocion')->references('id_promocion')->on('promociones')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });

        // Tabla pivote para aplicar promociones a servicios específicos
        Schema::create('promocion_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promocion');
            $table->unsignedBigInteger('id_servicio');
            $table->timestamps();

            $table->foreign('id_promocion')->references('id_promocion')->on('promociones')->onDelete('cascade');
            $table->foreign('id_servicio')->references('id_servicio')->on('servicios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promocion_servicios');
        Schema::dropIfExists('promocion_productos');
        Schema::dropIfExists('promociones');
    }
};