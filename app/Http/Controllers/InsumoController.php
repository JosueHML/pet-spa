<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:1']); // Solo Admin
    }

    public function index()
    {
        $insumos = Insumo::orderBy('created_at', 'desc')->paginate(15);
        return view('insumos.index', compact('insumos'));
    }

    public function create()
    {
        return view('insumos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio' => 'nullable|numeric|min:0',
        ]);

        Insumo::create($request->all());

        return redirect()->route('insumos.index')->with('success', 'Insumo creado correctamente');
    }

    public function edit($id)
    {
        $insumo = Insumo::findOrFail($id);
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio' => 'nullable|numeric|min:0',
        ]);

        $insumo = Insumo::findOrFail($id);
        $insumo->update($request->all());

        return redirect()->route('insumos.index')->with('success', 'Insumo actualizado correctamente');
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return redirect()->route('insumos.index')->with('success', 'Insumo eliminado correctamente');
    }

    public function show($id)
    {
        $insumo = Insumo::findOrFail($id);
        return view('insumos.show', compact('insumo'));
    }
}