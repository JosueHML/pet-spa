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
           // $inactivityLimit = 30 * 60; // 30 minutos en segundos
            $inactivityLimit = 120; // 2 minutos


            if ($lastActivity && (time() - $lastActivity) > $inactivityLimit) {
                Auth::logout();
                session()->flush();
                return redirect('/login')->with('error', 'Tu sesión ha expirado por inactividad (30 segundos).');
            }

            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}