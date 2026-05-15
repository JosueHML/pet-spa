<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
        $this->middleware('auth');
        $this->middleware('role:1');
    }

    public function showSetup()
    {
        $user = Auth::user();
        
        // Generar secreto si no existe
        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $this->google2fa->generateSecretKey();
            $user->save();
        }
        
        // Generar código QR
        $qrCode = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
        
        return view('auth.2fa.setup', compact('qrCode'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);
        
        $user = Auth::user();
        
        // Verificar código
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);
        
        if (!$valid) {
            return back()->with('error', 'Código inválido. Intenta nuevamente.');
        }
        
        $user->two_factor_enabled = 1;
        $user->save();
        
        return redirect()->route('admin.dashboard')->with('success', '2FA activado correctamente');
    }

    public function showVerify()
    {
        return view('auth.2fa.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);
        
        $user = Auth::user();
        
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);
        
        if ($valid) {
            session(['2fa_verified' => true]);
            return redirect()->intended('/admin/dashboard');
        }
        
        return back()->with('error', 'Código 2FA inválido');
    }

    public function disable()
    {
        $user = Auth::user();
        $user->two_factor_secret = null;
        $user->two_factor_enabled = 0;
        $user->save();
        
        return redirect()->route('admin.dashboard')->with('success', '2FA desactivado correctamente');
    }
}