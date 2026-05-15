<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Cajero;
use App\Models\Notificacion;
use App\Models\Insumo;
use App\Models\ServicioInsumo;
use App\Models\CitaConsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AuditLogHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMail;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        
        if ($usuario->id_rol == 1 || $usuario->id_rol == 2) {
            $facturas = Factura::with(['cliente.user', 'cita'])->orderBy('created_at', 'desc')->paginate(15);
            return view('facturas.index', compact('facturas'));
        }
        
        $cliente = Cliente::where('id_usuario', $usuario->id)->first();
        if (!$cliente) {
            return view('facturas.index', ['facturas' => collect()]);
        }
        
        $facturas = Factura::where('id_cliente', $cliente->id_cliente)
            ->with(['cita'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('facturas.index', compact('facturas'));
    }

    public function create($cita_id = null)
    {
        if (!$cita_id) {
            return redirect()->route('citas.index')->with('error', 'Seleccione una cita primero');
        }
        
        $cita = Cita::with(['mascota', 'servicio', 'mascota.cliente.user'])->findOrFail($cita_id);
        $cliente = $cita->mascota->cliente;
        $monto = $cita->servicio->precio;
        
        $ultimaFactura = Factura::orderBy('id_factura', 'desc')->first();
        $numero = $ultimaFactura ? intval($ultimaFactura->numero_factura) + 1 : 1;
        $numeroFactura = str_pad($numero, 8, '0', STR_PAD_LEFT);
        
        return view('facturas.create', compact('cita', 'cliente', 'monto', 'numeroFactura'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cita' => 'required|exists:citas,id_cita',
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'monto_total' => 'required|numeric|min:0',
            'numero_factura' => 'required|unique:facturas,numero_factura',
            'metodo_pago' => 'required|in:EFECTIVO,QR,TRANSFERENCIA',
        ]);

        $usuario = Auth::user();
        $cajero = Cajero::where('id_usuario', $usuario->id)->first();

        if (!$cajero && $usuario->id_rol == 1) {
            $cajero = Cajero::first();
            if (!$cajero) {
                $cajero = Cajero::create([
                    'id_usuario' => $usuario->id,
                    'permisos_pagos' => 1,
                ]);
            }
        }

        if (!$cajero) {
            return back()->with('error', 'No tienes permisos de cajero');
        }

        $factura = Factura::create([
            'id_cita' => $request->id_cita,
            'id_cliente' => $request->id_cliente,
            'id_cajero' => $cajero->id_cajero,
            'total' => $request->monto_total,
            'numero_factura' => $request->numero_factura,
            'metodo_pago' => $request->metodo_pago,
            'estado_pago' => 'PAGADO',
        ]);

        $cita = Cita::find($request->id_cita);
        if ($cita) {
            $cita->update(['estado' => 'COMPLETADA']);
        }

        // Descontar insumos
        try {
            $insumosServicio = DB::table('servicio_insumos')->where('id_servicio', $cita->id_servicio)->get();
            foreach ($insumosServicio as $item) {
                DB::table('insumos')->where('id_insumo', $item->id_insumo)->decrement('stock_actual', $item->cantidad);
                DB::table('cita_consumo')->insert([
                    'id_cita' => $cita->id_cita,
                    'id_insumo' => $item->id_insumo,
                    'cantidad' => $item->cantidad,
                    'created_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al descontar insumos: ' . $e->getMessage());
        }

        // Notificar al cliente
        Notificacion::create([
            'id_usuario' => $cita->mascota->cliente->id_usuario,
            'tipo' => 'LISTO_RECOGER',
            'mensaje' => "🐾 ¡Servicio completado! {$cita->mascota->nombre_mascota} está listo para recoger. Factura N° {$factura->numero_factura}",
            'canal' => 'EMAIL',
            'destino' => $cita->mascota->cliente->user->email,
        ]);

        // Enviar email al cliente
        Mail::to($cita->mascota->cliente->user->email)->send(new NotificacionMail(
            "🐾 ¡SERVICIO COMPLETADO!\n\n" .
            "Mascota: {$cita->mascota->nombre_mascota}\n" .
            "Servicio: {$cita->servicio->nombre_servicio}\n" .
            "Factura N°: {$factura->numero_factura}\n" .
            "Total: \${$factura->total}\n\n" .
            "¡Tu mascota está lista para recoger!",
            'LISTO_RECOGER',
            $cita->mascota->nombre_mascota
        ));

        AuditLogHelper::log('CREAR_FACTURA', ['factura_id' => $factura->id_factura, 'monto' => $request->monto_total]);

        return redirect()->route('facturas.show', $factura->id_factura)->with('success', 'Factura generada correctamente');
    }

    public function show($id)
    {
        $factura = Factura::with(['cliente.user', 'cita.mascota', 'cita.servicio', 'cajero.user'])->findOrFail($id);
        return view('facturas.show', compact('factura'));
    }

    public function destroy($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->delete();

        AuditLogHelper::log('ELIMINAR_FACTURA', ['factura_id' => $id]);

        return redirect()->route('facturas.index')->with('success', 'Factura eliminada correctamente');
    }
}