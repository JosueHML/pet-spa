<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\CarritoItem;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionMail;
use App\Helpers\AuditLogHelper;

class CarritoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function getCarrito()
    {
        $cliente = Cliente::where('id_usuario', Auth::id())->first();
        
        $carrito = Carrito::where('id_cliente', $cliente->id_cliente)
            ->where('estado', 'activo')
            ->first();
        
        if (!$carrito) {
            $carrito = Carrito::create([
                'id_cliente' => $cliente->id_cliente,
                'estado' => 'activo'
            ]);
        }
        
        return $carrito;
    }

    public function index()
    {
        $carrito = $this->getCarrito();
        $items = $carrito->items()->with('producto')->get();
        $total = $items->sum(function($item) {
            return $item->cantidad * $item->precio_unitario;
        });
        
        return view('carrito.index', compact('items', 'total', 'carrito'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1'
        ]);

        $carrito = $this->getCarrito();
        $producto = Producto::findOrFail($request->id_producto);

        $item = CarritoItem::where('id_carrito', $carrito->id_carrito)
            ->where('id_producto', $request->id_producto)
            ->first();

        if ($item) {
            $item->cantidad += $request->cantidad;
            $item->save();
        } else {
            CarritoItem::create([
                'id_carrito' => $carrito->id_carrito,
                'id_producto' => $request->id_producto,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $producto->precio,
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, $id)
    {
        $item = CarritoItem::findOrFail($id);
        $carrito = $this->getCarrito();
        
        if ($item->id_carrito != $carrito->id_carrito) {
            abort(403);
        }
        
        $item->cantidad = $request->cantidad;
        $item->save();
        
        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado');
    }

    public function remove($id)
    {
        $item = CarritoItem::findOrFail($id);
        $carrito = $this->getCarrito();
        
        if ($item->id_carrito != $carrito->id_carrito) {
            abort(403);
        }
        
        $item->delete();
        
        return redirect()->route('carrito.index')->with('success', 'Producto eliminado');
    }

    public function vaciar()
    {
        $carrito = $this->getCarrito();
        $carrito->items()->delete();
        
        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado');
    }

    public function whatsapp()
    {
        $carrito = $this->getCarrito();
        $items = $carrito->items()->with('producto')->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío');
        }

        $total = $items->sum(function($item) {
            return $item->cantidad * $item->precio_unitario;
        });

        $user = Auth::user();
        $cliente = Cliente::where('id_usuario', $user->id)->first();
        $numero = '59169866825'; // Cambia por el número del negocio

        // =============================================
        // 1. GENERAR FACTURA DE LA COMPRA
        // =============================================
        $ultimaFactura = Factura::orderBy('id_factura', 'desc')->first();
        $numeroFactura = $ultimaFactura ? str_pad(intval($ultimaFactura->numero_factura) + 1, 8, '0', STR_PAD_LEFT) : '00000001';

        $factura = Factura::create([
            'id_cliente' => $cliente->id_cliente,
            'id_cajero' => 1,
            'total' => $total,
            'numero_factura' => $numeroFactura,
            'metodo_pago' => 'QR',
            'estado_pago' => 'PAGADO',
        ]);

        // =============================================
        // 2. DESCONTAR STOCK
        // =============================================
        foreach ($items as $item) {
            $producto = Producto::find($item->id_producto);
            if ($producto) {
                $producto->decrement('stock_actual', $item->cantidad);
            }
        }

        // =============================================
        // 3. NOTIFICAR AL CLIENTE
        // =============================================
        Notificacion::create([
            'id_usuario' => $user->id,
            'tipo' => 'COMPRA_PRODUCTOS',
            'mensaje' => "¡Compra confirmada! Factura N° {$numeroFactura} | Total: \${$total}",
            'canal' => 'EMAIL',
            'destino' => $user->email,
        ]);

        // Enviar email al cliente
        Mail::to($user->email)->send(new NotificacionMail(
            "✅ ¡COMPRA CONFIRMADA!\n\n" .
            "Factura N°: {$numeroFactura}\n" .
            "Total: \${$total}\n\n" .
            "Gracias por tu compra. Tu pedido será procesado.",
            'COMPRA_PRODUCTOS',
            null
        ));

        // =============================================
        // 4. REGISTRAR EN LOG DE AUDITORÍA
        // =============================================
        AuditLogHelper::log('COMPRA_PRODUCTOS', [
            'usuario' => $user->name,
            'total' => $total,
            'factura' => $numeroFactura,
            'productos' => $items->count()
        ]);

        // =============================================
        // 5. GENERAR MENSAJE DE WHATSAPP
        // =============================================
        $mensaje = "🛒 *COMPRA PET SPA*\n";
        $mensaje .= "Cliente: {$user->name}\n";
        $telefono = $user->cliente ? $user->cliente->telefono : 'No registrado';
        $mensaje .= "Teléfono: {$telefono}\n";
        $mensaje .= "Factura: {$numeroFactura}\n\n";
        $mensaje .= "*PRODUCTOS:*\n";

        foreach ($items as $item) {
            $subtotal = $item->cantidad * $item->precio_unitario;
            $mensaje .= "• {$item->producto->nombre_producto} x{$item->cantidad} = \${$subtotal}\n";
        }

        $mensaje .= "\n*TOTAL: \${$total}*\n";
        $mensaje .= "\n*Forma de pago:* QR / Efectivo";

        // =============================================
        // 6. VACIAR EL CARRITO
        // =============================================
        $carrito->items()->delete();

        $whatsappUrl = "https://wa.me/{$numero}?text=" . urlencode($mensaje);

        return redirect()->away($whatsappUrl);
    }

    public function solicitarFactura()
    {
        $carrito = $this->getCarrito();
        $items = $carrito->items()->with('producto')->get();

        if ($items->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío');
        }

        $total = $items->sum(function($item) {
            return $item->cantidad * $item->precio_unitario;
        });

        $cliente = Cliente::where('id_usuario', Auth::id())->first();

        // Guardar solicitud
        $solicitud = \App\Models\SolicitudFactura::create([
            'id_cliente' => $cliente->id_cliente,
            'carrito_data' => $items->map(function($item) {
                return [
                    'producto' => $item->producto->nombre_producto,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal' => $item->cantidad * $item->precio_unitario
                ];
            }),
            'total' => $total,
            'estado' => 'PENDIENTE',
        ]);

        // Notificar al admin
        $admins = \App\Models\User::where('id_rol', 1)->get();
        foreach ($admins as $admin) {
            \App\Models\Notificacion::create([
                'id_usuario' => $admin->id,
                'tipo' => 'SOLICITUD_FACTURA',
                'mensaje' => "Nueva solicitud de factura #{$solicitud->id_solicitud} del cliente {$cliente->user->name} por \${$total}",
                'canal' => 'EMAIL',
                'destino' => $admin->email,
            ]);
        }

        return redirect()->route('carrito.index')->with('success', 'Solicitud de factura enviada. Espera la aprobación del administrador.');
    }
}