<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showSetPasswordForm($token)
{
    return view('auth.set-password', ['token' => $token]);
}

public function setPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ]);

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Verificar si el token existe
        if (!$reset) {
            return back()->withErrors(['email' => 'Token inválido. Solicita un nuevo enlace.']);
        }

        // Verificar si el token expiró (24 horas)
        $createdAt = \Carbon\Carbon::parse($reset->created_at);
        if ($createdAt->diffInHours(now()) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'El enlace ha expirado. Solicita un nuevo enlace al administrador.']);
        }

        if (!Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Token inválido o expirado.']);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->primer_ingreso = 0;
        $user->ultimo_cambio_password = now();
        $user->password_expira = now()->addDays(30);
        $user->account_status = 'ACTIVO';
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', '✅ Contraseña establecida correctamente. Ahora puedes iniciar sesión.');
    }
}