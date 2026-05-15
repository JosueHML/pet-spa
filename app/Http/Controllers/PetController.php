<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cliente = Cliente::where('id_usuario', Auth::id())->first();
        $mascotas = Mascota::where('id_cliente', $cliente->id_cliente)->get();
        
        return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {
        return view('mascotas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'tamanio' => 'required|in:PEQUEÑO,MEDIANO,GRANDE,EXTRA_GRANDE',
            'edad_meses' => 'nullable|integer|min:0',
            'alergias' => 'nullable|string',
            'vacunas' => 'nullable|string',
            'restricciones' => 'nullable|string',
        ]);

        $cliente = Cliente::where('id_usuario', Auth::id())->first();

        Mascota::create([
            'id_cliente' => $cliente->id_cliente,
            'nombre' => $request->nombre,
            'raza' => $request->raza,
            'tamanio' => $request->tamanio,
            'edad_meses' => $request->edad_meses,
            'alergias' => $request->alergias,
            'vacunas' => $request->vacunas,
            'restricciones' => $request->restricciones,
        ]);

        return redirect()->route('pet.index')->with('success', 'Mascota registrada correctamente');
    }

    public function edit($id)
    {
        $mascota = Mascota::findOrFail($id);
        return view('mascotas.edit', compact('mascota'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'tamanio' => 'required|in:PEQUEÑO,MEDIANO,GRANDE,EXTRA_GRANDE',
            'edad_meses' => 'nullable|integer|min:0',
            'alergias' => 'nullable|string',
            'vacunas' => 'nullable|string',
            'restricciones' => 'nullable|string',
        ]);

        $mascota = Mascota::findOrFail($id);
        $mascota->update($request->all());

        return redirect()->route('pet.index')->with('success', 'Mascota actualizada correctamente');
    }

    public function destroy($id)
    {
        $mascota = Mascota::findOrFail($id);
        $mascota->delete();

        return redirect()->route('pet.index')->with('success', 'Mascota eliminada correctamente');
    }
}