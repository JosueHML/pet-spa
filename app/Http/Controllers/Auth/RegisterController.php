<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'account_status' => 'INACTIVO',
            'id_rol' => 4,  // 👈 ASIGNAR ROL CLIENTE AUTOMÁTICAMENTE
        ]);

        // 👇 CREAR EL CLIENTE AUTOMÁTICAMENTE
        DB::table('clientes')->insert([
            'id_usuario' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generar token de verificación
        $user->generateVerificationToken();

        // Enviar email de verificación
        Mail::to($user->email)->send(new VerifyEmail($user));

        return $user;
    }
}