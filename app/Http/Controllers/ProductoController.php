<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productos = Producto::orderBy('created_at', 'desc')->paginate(12);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre_producto' => 'required|string|max:200',
                'sku' => 'required|string|max:100|unique:productos,sku',
                'categoria' => 'nullable|string|max:100',
                'stock_actual' => 'required|integer|min:0',
                'precio' => 'required|numeric|min:0',
            ]);

            $producto = new Producto();
            $producto->nombre_producto = $request->nombre_producto;
            $producto->sku = $request->sku;
            $producto->categoria = $request->categoria;
            $producto->stock_actual = $request->stock_actual;
            $producto->stock_minimo = 5;
            $producto->precio = $request->precio;
            $producto->save();

            return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear producto: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:200',
            'sku' => 'required|string|max:100|unique:productos,sku,' . $id . ',id_producto',
            'categoria' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto = Producto::findOrFail($id);
        $producto->nombre_producto = $request->nombre_producto;
        $producto->sku = $request->sku;
        $producto->categoria = $request->categoria;
        $producto->stock_actual = $request->stock_actual;
        $producto->precio = $request->precio;
        $producto->save();

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente');
    }
}