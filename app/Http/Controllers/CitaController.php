<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Mascota;
use App\Models\Groomer;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\AgendaBloqueo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\AuditLogHelper;

class CitaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function verificarBloqueo($groomer_id, $fecha)
    {
        // Verificar bloqueos globales
        $bloqueoGlobal = AgendaBloqueo::where('alcance', 'GLOBAL')
            ->where('fecha_bloqueo', $fecha)
            ->exists();

        if ($bloqueoGlobal) {
            return true;
        }

        // Verificar bloqueos individuales del groomer
        $bloqueoIndividual = AgendaBloqueo::where('id_groomer', $groomer_id)
            ->where('fecha_bloqueo', $fecha)
            ->exists();

        return $bloqueoIndividual;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Si es GROOMER (rol 3), solo ver sus citas
        if ($user->id_rol == 3) {
            $groomer = Groomer::where('id_usuario', $user->id)->first();
            $citas = Cita::where('id_groomer', $groomer->id_groomer)
                ->with(['mascota', 'servicio'])
                ->orderBy('fecha_inicio', 'desc')
                ->get();
            return view('citas.index', compact('citas'));
        }
        
        // Si es ADMIN o CAJERO, ver todas las citas
        if ($user->id_rol == 1 || $user->id_rol == 2) {
            $citas = Cita::with(['mascota', 'servicio', 'mascota.cliente.user'])
                ->orderBy('fecha_inicio', 'desc')
                ->paginate(15);
            return view('citas.index', compact('citas'));
        }
        
        // Si es CLIENTE, solo sus citas
        $cliente = Cliente::where('id_usuario', $user->id)->first();
        if (!$cliente) {
            return redirect()->route('cliente.dashboard')->with('error', 'No eres un cliente registrado.');
        }
        
        $citas = Cita::whereHas('mascota', function($q) use ($cliente) {
            $q->where('id_cliente', $cliente->id_cliente);
        })->with(['mascota', 'servicio'])->orderBy('fecha_inicio', 'desc')->get();

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $usuario = Auth::user();
        $groomers = Groomer::where('activo', 1)->get();
        $servicios = Servicio::all();
        
        // Si es ADMIN o CAJERO, pueden seleccionar cualquier mascota
        if ($usuario->id_rol == 1 || $usuario->id_rol == 2) {
            $mascotas = Mascota::with('cliente.user')->get();
            return view('citas.create', compact('mascotas', 'groomers', 'servicios'));
        }
        
        // Si es CLIENTE, solo sus mascotas
        $cliente = Cliente::where('id_usuario', $usuario->id)->first();
        if (!$cliente) {
            return redirect()->route('cliente.dashboard')->with('error', 'No tienes mascotas registradas.');
        }
        $mascotas = Mascota::where('id_cliente', $cliente->id_cliente)->get();
        
        return view('citas.create', compact('mascotas', 'groomers', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id_mascota',
            'id_groomer' => 'required|exists:groomers,id_groomer',
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'fecha_inicio' => 'required|date',
        ]);

        $servicio = Servicio::find($request->id_servicio);
        $mascota = Mascota::find($request->id_mascota);
        $fecha_inicio = Carbon::parse($request->fecha_inicio);

        // Calcular duración con factor de tamaño
        $duracionBase = $servicio->duracion_minutos;
        $factor = $mascota->factor_tamanio ?? 1.00;
        $duracionAjustada = round($duracionBase * $factor);

        $fecha_fin = $fecha_inicio->copy()->addMinutes($duracionAjustada);
        // ✅ VERIFICAR BLOQUEOS DE AGENDA
        $fechaStr = $fecha_inicio->format('Y-m-d');
        if ($this->verificarBloqueo($request->id_groomer, $fechaStr)) {
            return back()->with('error', 'El groomer no está disponible en esa fecha (feriado, mantenimiento o ausencia).');
        }

        // Verificar disponibilidad del groomer
        $existing = Cita::where('id_groomer', $request->id_groomer)
            ->where('fecha_inicio', '<', $fecha_fin)
            ->where('fecha_fin', '>', $fecha_inicio)
            ->where('estado', '!=', 'CANCELADA')
            ->exists();

        if ($existing) {
            return back()->with('error', 'El groomer no está disponible en ese horario');
        }

        $cita = Cita::create([
            'id_mascota' => $request->id_mascota,
            'id_groomer' => $request->id_groomer,
            'id_servicio' => $request->id_servicio,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'estado' => 'PENDIENTE',
        ]);

        AuditLogHelper::log('CREAR_CITA', ['cita_id' => $cita->id_cita]);

        return redirect()->route('citas.index')->with('success', 'Cita agendada correctamente');
    }

    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $groomers = Groomer::where('activo', 1)->get();
        $servicios = Servicio::all();
        $mascotas = Mascota::all();
        
        return view('citas.edit', compact('cita', 'groomers', 'servicios', 'mascotas'));
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        
        $request->validate([
            'id_mascota' => 'required|exists:mascotas,id_mascota',
            'id_groomer' => 'required|exists:groomers,id_groomer',
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'fecha_inicio' => 'required|date',
        ]);

        $servicio = Servicio::find($request->id_servicio);
        $mascota = Mascota::find($request->id_mascota);
        $fecha_inicio = Carbon::parse($request->fecha_inicio);

        $duracionBase = $servicio->duracion_minutos;
        $factor = $mascota->factor_tamanio ?? 1.00;
        $duracionAjustada = round($duracionBase * $factor);

        $fecha_fin = $fecha_inicio->copy()->addMinutes($duracionAjustada);

        // ✅ VERIFICAR BLOQUEOS DE AGENDA EN ACTUALIZACIÓN
        $fechaStr = $fecha_inicio->format('Y-m-d');
        if ($this->verificarBloqueo($request->id_groomer, $fechaStr)) {
            return back()->with('error', 'El groomer no está disponible en esa fecha (feriado, mantenimiento o ausencia).');
        }

        // Verificar disponibilidad (excluyendo la cita actual)
        $existing = Cita::where('id_groomer', $request->id_groomer)
            ->where('id_cita', '!=', $id)
            ->where('fecha_inicio', '<', $fecha_fin)
            ->where('fecha_fin', '>', $fecha_inicio)
            ->where('estado', '!=', 'CANCELADA')
            ->exists();

        if ($existing) {
            return back()->with('error', 'El groomer no está disponible en ese horario');
        }

        $cita->update([
            'id_mascota' => $request->id_mascota,
            'id_groomer' => $request->id_groomer,
            'id_servicio' => $request->id_servicio,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ]);

        AuditLogHelper::log('ACTUALIZAR_CITA', ['cita_id' => $cita->id_cita]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente');
    }

    public function cancel($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'CANCELADA']);

        AuditLogHelper::log('CANCELAR_CITA', ['cita_id' => $id]);

        return redirect()->route('citas.index')->with('success', 'Cita cancelada correctamente');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        AuditLogHelper::log('ELIMINAR_CITA', ['cita_id' => $id]);

        return redirect()->route('citas.index')->with('success', 'Cita eliminada correctamente');
    }

    public function getHorariosOcupados($groomer_id, $fecha)
    {
        $citas = Cita::where('id_groomer', $groomer_id)
            ->whereDate('fecha_inicio', $fecha)
            ->where('estado', '!=', 'CANCELADA')
            ->with('servicio')
            ->get();
        
        $ocupados = [];
        foreach ($citas as $cita) {
            $ocupados[] = [
                'inicio' => Carbon::parse($cita->fecha_inicio)->format('H:i'),
                'fin' => Carbon::parse($cita->fecha_fin)->format('H:i'),
                'servicio' => $cita->servicio->nombre_servicio ?? 'Cita ocupada'
            ];
        }
        
        return response()->json($ocupados);
    }

    public function show($id)
    {
        $cita = Cita::with(['mascota', 'servicio', 'groomer.user'])->findOrFail($id);
        
        // Verificar permisos
        $user = Auth::user();
        if ($user->id_rol == 4) { // Cliente
            $cliente = Cliente::where('id_usuario', $user->id)->first();
            if ($cita->mascota->id_cliente != $cliente->id_cliente) {
                abort(403, 'No tienes permiso para ver esta cita.');
            }
        }
        
        return view('citas.show', compact('cita'));
    }

}