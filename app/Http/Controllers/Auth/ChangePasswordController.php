<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ]);

        $user = Auth::user();
        
        $user->password = Hash::make($request->password);
        $user->primer_ingreso = 0;
        $user->ultimo_cambio_password = now();
        $user->password_expira = now()->addDays(30);
        $user->account_status = 'ACTIVO';
        $user->save();

        // Redirigir según el rol del usuario
        switch ($user->id_rol) {
            case 1: return redirect()->route('admin.dashboard');
            case 2: return redirect()->route('cajero.dashboard');
            case 3: return redirect()->route('groomer.dashboard');
            default: return redirect()->route('cliente.dashboard');
        }
    }
}