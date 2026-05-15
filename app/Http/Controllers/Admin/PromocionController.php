<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use App\Models\Producto;
use App\Models\Servicio;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('role:1');
    }

    public function index()
    {
        $promociones = Promocion::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        $productos = Producto::all();
        $servicios = Servicio::all();
        return view('admin.promociones.create', compact('productos', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:PORCENTAJE,MONTO_FIJO,COMPRA_XXX',
            'valor_descuento' => 'required|numeric|min:0.01',
            'compra_minima' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'aplica_a' => 'required|in:PRODUCTOS,SERVICIOS,AMBOS',
        ]);

        $promocion = Promocion::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'valor_descuento' => $request->valor_descuento,
            'compra_minima' => $request->compra_minima ?? 0,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'aplica_a' => $request->aplica_a,
            'activo' => true,
        ]);

        if ($request->tipo == 'PORCENTAJE') {
            $promocion->valor_descuento = $request->valor_descuento;
        } else {
            $promocion->valor_descuento = $request->valor_descuento;
        }
        $promocion->save();

        return redirect()->route('admin.promociones.index')->with('success', 'Promoción creada correctamente');
    }

    public function edit($id)
    {
        $promocion = Promocion::findOrFail($id);
        $productos = Producto::all();
        $servicios = Servicio::all();
        return view('admin.promociones.edit', compact('promocion', 'productos', 'servicios'));
    }

    public function update(Request $request, $id)
    {
        $promocion = Promocion::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:PORCENTAJE,MONTO_FIJO,COMPRA_XXX',
            'valor_descuento' => 'required|numeric|min:0',
            'compra_minima' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'aplica_a' => 'required|in:PRODUCTOS,SERVICIOS,AMBOS',
        ]);

        $promocion->update($request->all());

        if ($request->has('productos')) {
            $promocion->productos()->sync($request->productos);
        }

        if ($request->has('servicios')) {
            $promocion->servicios()->sync($request->servicios);
        }

        return redirect()->route('admin.promociones.index')->with('success', 'Promoción actualizada');
    }

    public function destroy($id)
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->delete();

        return redirect()->route('admin.promociones.index')->with('success', 'Promoción eliminada');
    }

    public function toggle($id)
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->activo = !$promocion->activo;
        $promocion->save();

        return redirect()->route('admin.promociones.index')->with('success', 'Estado de promoción actualizado');
    }
}