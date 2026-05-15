<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos para verificación de email (PDF1: link expira 15 min)
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->string('verification_token')->nullable();
            $table->datetime('verification_token_expires_at')->nullable();
            
            // Campos para bloqueo por intentos (PDF1: 5 fallos = 15 min bloqueo)
            $table->integer('login_attempts')->default(0);
            $table->datetime('blocked_until')->nullable();
            
            // Campos para 2FA (PDF1: solo Administrador)
            $table->string('two_factor_secret')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            
            // Campos para recuperación de contraseña
            $table->string('reset_password_token')->nullable();
            $table->datetime('reset_password_expires_at')->nullable();
            
            // Estado de la cuenta
            $table->enum('account_status', ['ACTIVO', 'INACTIVO', 'BLOQUEADO'])->default('ACTIVO');
            $table->datetime('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_token',
                'verification_token_expires_at',
                'login_attempts',
                'blocked_until',
                'two_factor_secret',
                'two_factor_enabled',
                'reset_password_token',
                'reset_password_expires_at',
                'account_status',
                'last_login_at'
            ]);
        });
    }
};