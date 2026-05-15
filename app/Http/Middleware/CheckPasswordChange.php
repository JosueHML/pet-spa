<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if (!$user) {
            return $next($request);
        }
        
        // Verificar primer ingreso (debe cambiar contraseña)
        if ($user->primer_ingreso == 1) {
            return redirect()->route('password.change')->with('error', 'Debes cambiar tu contraseña antes de continuar.');
        }
        
        // Verificar si la contraseña expiró (30 días)
        if ($user->password_expira && now()->gt($user->password_expira)) {
            $user->account_status = 'INACTIVO';
            $user->save();
            Auth::logout();
            return redirect('/login')->with('error', 'Tu contraseña ha expirado. Contacta al administrador.');
        }
        
        return $next($request);
    }
}