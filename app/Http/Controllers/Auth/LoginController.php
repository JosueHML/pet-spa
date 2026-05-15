<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\AuditLogHelper;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && $user->blocked_until && now()->lt($user->blocked_until)) {
            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $attempts = $user->login_attempts + 1;
            $user->login_attempts = $attempts;

            if ($attempts >= 5) {
                $user->blocked_until = now()->addMinutes(15);
                $user->login_attempts = 0;
                $user->save();

                return redirect('/login')->withErrors([
                    'email' => '❌ Demasiados intentos. Cuenta bloqueada por 15 minutos.',
                ]);
            }
            $user->save();
        }

        return redirect('/login')->withErrors([
            'email' => '❌ Estas credenciales no coinciden con nuestros registros.',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->two_factor_enabled && $user->id_rol == 1) {
            return redirect()->route('2fa.verify');
        }
        AuditLogHelper::log('INICIO_SESION', ['email' => $user->email]);

        $user->login_attempts = 0;
        $user->blocked_until = null;
        $user->save();

        // Redirección según rol
        switch ($user->id_rol) {
            case 1: return redirect()->route('admin.dashboard');
            case 2: return redirect()->route('cajero.dashboard');
            case 3: return redirect()->route('groomer.dashboard');
            default: return redirect()->route('cliente.dashboard');
        }
    }
}