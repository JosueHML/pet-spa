<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $user = User::where('verification_token', $token)
                    ->where('verification_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect('/login')->with('error', '❌ Token inválido o expirado. Solicita un nuevo enlace.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->verification_token_expires_at = null;
        $user->account_status = 'ACTIVO';
        $user->save();

        return redirect('/login')->with('success', '✅ ¡Email verificado correctamente! Ahora puedes iniciar sesión.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->with('info', 'Esta cuenta ya está verificada.');
        }

        $user->generateVerificationToken();
        
        Mail::to($user->email)->send(new VerifyEmail($user));

        return back()->with('success', '📧 Se ha enviado un nuevo enlace de verificación a tu correo.');
    }
}