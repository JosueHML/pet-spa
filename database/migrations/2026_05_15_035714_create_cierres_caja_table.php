<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id('id_cierre');
            $table->unsignedBigInteger('id_cajero');
            $table->decimal('total_efectivo', 10, 2)->default(0);
            $table->decimal('total_qr', 10, 2)->default(0);
            $table->decimal('total_transferencia', 10, 2)->default(0);
            $table->decimal('total_general', 10, 2)->default(0);
            $table->datetime('fecha_cierre');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_cajero')->references('id_cajero')->on('cajeros');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierres_caja');
    }
};