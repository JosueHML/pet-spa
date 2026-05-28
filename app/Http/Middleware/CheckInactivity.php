<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInactivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            // Tiempo de inactividad permitido en segundos
            $inactivityLimit = 120; // 2 minutos

            if ($lastActivity && (time() - $lastActivity) > $inactivityLimit) {
                Auth::logout();
                session()->flush();
                return redirect('/login')->with('error', 'Tu sesión ha expirado por inactividad (2 minutos).');
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}