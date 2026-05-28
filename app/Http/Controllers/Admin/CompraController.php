<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:1']);
    }
    
    public function index()
    {
        $compras = Compra::with('proveedor')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.compras.index', compact('compras'));
    }
    
    public function create()
    {
        $proveedores = Proveedor::where('activo', 1)->get();
        $productos = Producto::all();
        $insumos = Insumo::all();
        return view('admin.compras.create', compact('proveedores', 'productos', 'insumos'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'fecha_compra' => 'required|date',
            'items' => 'required|array|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['cantidad'] * $item['precio'];
            }
            
            $compra = Compra::create([
                'id_proveedor' => $request->id_proveedor,
                'numero_factura' => $request->numero_factura,
                'fecha_compra' => $request->fecha_compra,
                'subtotal' => $total,
                'impuesto' => $total * 0.13,
                'total' => $total * 1.13,
                'estado' => 'PENDIENTE',
                'observaciones' => $request->observaciones,
            ]);
            
            foreach ($request->items as $item) {
                CompraDetalle::create([
                    'id_compra' => $compra->id_compra,
                    'tipo_producto' => $item['tipo'],
                    'id_item' => $item['id_item'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['cantidad'] * $item['precio'],
                ]);
            }
            
            DB::commit();
            return redirect()->route('admin.compras.index')->with('success', 'Compra registrada correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage());
        }
    }
    
    public function recibir($id)
    {
        $compra = Compra::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            foreach ($compra->detalles as $detalle) {
                if ($detalle->tipo_producto == 'PRODUCTO') {
                    $producto = Producto::find($detalle->id_item);
                    $producto->increment('stock_actual', $detalle->cantidad);
                } else {
                    $insumo = Insumo::find($detalle->id_item);
                    $insumo->increment('stock_actual', $detalle->cantidad);
                }
            }
            
            $compra->update(['estado' => 'RECIBIDO']);
            
            DB::commit();
            return redirect()->route('admin.compras.index')->with('success', 'Productos recibidos y stock actualizado');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al recibir compra');
        }
    }
    
    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'detalles'])->findOrFail($id);
        return view('admin.compras.show', compact('compra'));
    }



    public function getProductos()
    {
        return response()->json(Producto::select('id_producto', 'nombre', 'stock_actual')->get());
    }

    public function getInsumos()
    {
        return response()->json(Insumo::select('id_insumo', 'nombre', 'stock_actual')->get());
    }
}