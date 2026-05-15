<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use App\Helpers\AuditLogHelper;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Buscar usuario por email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if (!$user) {
                // Crear nuevo usuario
                DB::beginTransaction();
                try {
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'password' => bcrypt(uniqid()),
                        'account_status' => 'ACTIVO',
                        'email_verified_at' => now(),
                        'id_rol' => 4, // ROL CLIENTE
                    ]);
                    
                    // Crear cliente asociado
                    Cliente::create([
                        'id_usuario' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    DB::commit();
                    
                    AuditLogHelper::log('REGISTRO_GOOGLE', ['email' => $user->email]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } else {
                // Si el usuario existe pero está inactivo, lo activamos
                if ($user->account_status == 'INACTIVO') {
                    $user->update([
                        'account_status' => 'ACTIVO',
                        'email_verified_at' => now(),
                    ]);
                }
                
                AuditLogHelper::log('LOGIN_GOOGLE', ['email' => $user->email]);
            }
            
            Auth::login($user);
            
            // Redirigir según el rol
            switch ($user->id_rol) {
                case 1: return redirect()->route('admin.dashboard');
                case 2: return redirect()->route('cajero.dashboard');
                case 3: return redirect()->route('groomer.dashboard');
                default: return redirect()->route('cliente.dashboard');
            }
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error al autenticar con Google: ' . $e->getMessage());
        }
    }
}