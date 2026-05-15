<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolicitudFactura;
use App\Models\Factura;
use App\Models\Notificacion;
use App\Models\Cajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMail;
use App\Helpers\AuditLogHelper;

class SolicitudFacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('role:1,2'); // Admin y Cajero
    }

    public function index()
    {
        $solicitudes = SolicitudFactura::with('cliente.user')
            ->where('estado', 'PENDIENTE')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.solicitudes.index', compact('solicitudes'));
    }

    public function show($id)
    {
        $solicitud = SolicitudFactura::with('cliente.user')->findOrFail($id);
        return view('admin.solicitudes.show', compact('solicitud'));
    }

    public function aprobar($id)
    {
        $solicitud = SolicitudFactura::findOrFail($id);

        // Generar factura
        $ultimaFactura = Factura::orderBy('id_factura', 'desc')->first();
        $numeroFactura = $ultimaFactura ? str_pad(intval($ultimaFactura->numero_factura) + 1, 8, '0', STR_PAD_LEFT) : '00000001';

        $usuario = Auth::user();
        $cajero = Cajero::where('id_usuario', $usuario->id)->first();

        if (!$cajero && $usuario->id_rol == 1) {
            $cajero = Cajero::first();
        }

        $factura = Factura::create([
            'id_cliente' => $solicitud->id_cliente,
            'id_cajero' => $cajero->id_cajero ?? 1,
            'total' => $solicitud->total,
            'numero_factura' => $numeroFactura,
            'metodo_pago' => 'QR',
            'estado_pago' => 'PAGADO',
        ]);

        // Actualizar stock
        $items = $solicitud->carrito_data;
        foreach ($items as $item) {
            $producto = \App\Models\Producto::where('nombre_producto', $item['producto'])->first();
            if ($producto) {
                $producto->decrement('stock_actual', $item['cantidad']);
            }
        }

        $solicitud->estado = 'APROBADA';
        $solicitud->save();

        // Notificar al cliente
        $cliente = $solicitud->cliente;
        Notificacion::create([
            'id_usuario' => $cliente->id_usuario,
            'tipo' => 'FACTURA_APROBADA',
            'mensaje' => "✅ ¡Tu factura #{$numeroFactura} ha sido aprobada! Total: \${$solicitud->total}",
            'canal' => 'EMAIL',
            'destino' => $cliente->user->email,
        ]);

        Mail::to($cliente->user->email)->send(new NotificacionMail(
            "✅ ¡FACTURA APROBADA!\n\n" .
            "Factura N°: {$numeroFactura}\n" .
            "Total: \${$solicitud->total}\n\n" .
            "Gracias por tu compra.",
            'FACTURA_APROBADA',
            null
        ));

        AuditLogHelper::log('APROBAR_SOLICITUD_FACTURA', [
            'solicitud_id' => $solicitud->id_solicitud,
            'factura_id' => $factura->id_factura
        ]);

        return redirect()->route('admin.solicitudes.index')->with('success', 'Solicitud aprobada y factura generada');
    }

    public function rechazar($id)
    {
        $solicitud = SolicitudFactura::findOrFail($id);
        $solicitud->estado = 'RECHAZADA';
        $solicitud->save();

        $cliente = $solicitud->cliente;
        Notificacion::create([
            'id_usuario' => $cliente->id_usuario,
            'tipo' => 'FACTURA_RECHAZADA',
            'mensaje' => "❌ Tu solicitud de factura ha sido rechazada. Contacta al administrador.",
            'canal' => 'EMAIL',
            'destino' => $cliente->user->email,
        ]);

        return redirect()->route('admin.solicitudes.index')->with('error', 'Solicitud rechazada');
    }
}