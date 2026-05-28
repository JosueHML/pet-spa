<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:1']);
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:200',
            'sku' => 'required|string|max:100|unique:productos',
            'categoria' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('admin.productos.nuevo')->with('success', 'Producto creado correctamente');
    }
}