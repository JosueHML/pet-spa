<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Groomer;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PruebaController extends Controller
{
    public function index()
    {
        $ultimasCitas = Cita::with('mascota')->orderBy('id_cita', 'desc')->limit(10)->get();
        return view('admin.pruebas', compact('ultimasCitas'));
    }

    public function crearCita(Request $request)
    {
        $mascota = Mascota::first();
        $groomer = Groomer::first();
        $servicio = Servicio::first();

        if (!$mascota || !$groomer || !$servicio) {
            return redirect()->route('admin.pruebas')->with('error', '❌ Faltan datos para crear la cita.');
        }

        $fecha_inicio = Carbon::now()->addHours(24);
        $fecha_fin = $fecha_inicio->copy()->addMinutes($servicio->duracion_minutos);

        $cita = Cita::create([
            'id_mascota' => $mascota->id_mascota,
            'id_groomer' => $groomer->id_groomer,
            'id_servicio' => $servicio->id_servicio,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'estado' => 'PENDIENTE',
        ]);

        return redirect()->route('admin.pruebas')->with('success', '✅ Cita de prueba creada. ID: ' . $cita->id_cita);
    }

    public function enviarRecordatorios(Request $request)
    {
        Artisan::call('reminders:send');
        return redirect()->route('admin.pruebas')->with('success', '✅ Recordatorios enviados correctamente.');
    }
}