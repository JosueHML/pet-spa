<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Groomer;
use App\Models\Cajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\AuditLogHelper;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SetPasswordMail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::with(['groomer', 'cajero'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:GROOMER,CAJERO',
            'especialidad' => 'required_if:rol,GROOMER|nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'max_citas_diarias' => 'required_if:rol,GROOMER|nullable|integer|min:1|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_status' => 'INACTIVO',
                'primer_ingreso' => 1,
                'email_verified_at' => now(),
            ]);

            if ($request->rol == 'GROOMER') {
                $user->id_rol = 3;
                $user->save();
                
                Groomer::create([
                    'id_usuario' => $user->id,
                    'especialidad' => $request->especialidad,
                    'telefono' => $request->telefono,
                    'max_citas_diarias' => $request->max_citas_diarias,
                    'activo' => 1,
                ]);
            } elseif ($request->rol == 'CAJERO') {
                $user->id_rol = 2;
                $user->save();
                
                Cajero::create([
                    'id_usuario' => $user->id,
                    'permisos_pagos' => 1,
                ]);
            }

            $token = Str::random(60);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            // ✅ ENVIAR EMAIL CON EL ROL INCLUIDO
            Mail::to($request->email)->send(new SetPasswordMail($user, $token, $request->rol));

            DB::commit();

            AuditLogHelper::log('CREAR_PERSONAL', [
                'creado_por' => auth()->user()->email,
                'usuario_creado' => $user->email,
                'rol' => $request->rol
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario de personal creado correctamente. Se ha enviado un email para establecer la contraseña.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'especialidad' => 'nullable|string|max:100',
            'max_citas_diarias' => 'nullable|integer|min:1|max:20',
            'activo' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->groomer) {
            $user->groomer->update([
                'especialidad' => $request->especialidad,
                'max_citas_diarias' => $request->max_citas_diarias,
                'activo' => $request->activo ?? 1,
            ]);
        }

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        AuditLogHelper::log('EDITAR_PERSONAL', [
            'editado_por' => auth()->user()->email,
            'usuario_editado' => $user->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $email = $user->email;
        $user->delete();

        AuditLogHelper::log('ELIMINAR_PERSONAL', [
            'eliminado_por' => auth()->user()->email,
            'usuario_eliminado' => $email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'ACTIVO';
        $user->save();

        AuditLogHelper::log('ACTIVAR_USUARIO', [
            'activado_por' => auth()->user()->email,
            'usuario_activado' => $user->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario activado correctamente.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        $user->account_status = 'INACTIVO';
        $user->save();

        AuditLogHelper::log('DESACTIVAR_USUARIO', [
            'desactivado_por' => auth()->user()->email,
            'usuario_desactivado' => $user->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }
}