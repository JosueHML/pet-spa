<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('id_factura');
            $table->unsignedBigInteger('id_cita')->nullable();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_cajero');
            $table->decimal('monto_total', 10, 2);
            $table->string('numero_factura', 50)->unique();
            $table->enum('metodo_pago', ['EFECTIVO', 'QR', 'TRANSFERENCIA'])->nullable();
            $table->enum('estado_pago', ['PENDIENTE', 'PAGADO', 'CANCELADO'])->default('PENDIENTE');
            $table->timestamp('fecha_emision')->useCurrent();
            $table->timestamps();

            $table->foreign('id_cita')->references('id_cita')->on('citas')->onDelete('set null');
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_cajero')->references('id_cajero')->on('cajeros');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};