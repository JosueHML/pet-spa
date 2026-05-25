<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:4')->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $cliente = Cliente::where('id_usuario', Auth::id())->first();
        if (!$cliente) {
            return redirect()->route('cliente.dashboard')->with('error', 'No eres un cliente registrado.');
        }
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

        $factores = [
            'PEQUEÑO' => 1.00,
            'MEDIANO' => 1.15,
            'GRANDE' => 1.25,
            'EXTRA_GRANDE' => 1.30,
        ];

        Mascota::create([
            'id_cliente' => $cliente->id_cliente,
            'nombre_mascota' => $request->nombre,
            'raza' => $request->raza,
            'tamanio' => $request->tamanio,
            'factor_tamanio' => $factores[$request->tamanio],
            'edad_meses' => $request->edad_meses,
            'alergias' => $request->alergias,
            'vacunas' => $request->vacunas,
            'restricciones' => $request->restricciones,
        ]);

        return redirect()->route('mascotas.index')->with('success', 'Mascota registrada correctamente');
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
        
        $factores = [
            'PEQUEÑO' => 1.00,
            'MEDIANO' => 1.15,
            'GRANDE' => 1.25,
            'EXTRA_GRANDE' => 1.30,
        ];
        
        $mascota->update([
            'nombre_mascota' => $request->nombre,
            'raza' => $request->raza,
            'tamanio' => $request->tamanio,
            'factor_tamanio' => $factores[$request->tamanio],
            'edad_meses' => $request->edad_meses,
            'alergias' => $request->alergias,
            'vacunas' => $request->vacunas,
            'restricciones' => $request->restricciones,
        ]);

        return redirect()->route('mascotas.index')->with('success', 'Mascota actualizada correctamente');
    }

    public function destroy($id)
    {
        try {
            $mascota = Mascota::findOrFail($id);
            $mascota->delete();

            return redirect()->route('mascotas.index')->with('success', 'Mascota eliminada correctamente');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                return redirect()->route('mascotas.index')->with('error', 'No se puede eliminar la mascota porque tiene citas asociadas. Elimine primero las citas.');
            }
            return redirect()->route('mascotas.index')->with('error', 'Error al eliminar la mascota.');
        }
    }

    public function recomendaciones($id)
    {
        $mascota = Mascota::with(['cliente.user'])->findOrFail($id);
        
        // Reglas de recomendación según tamaño y temperamento
        $recomendaciones = [];
        
        switch ($mascota->tamanio) {
            case 'pequeño':
                $recomendaciones[] = ['nombre' => 'Cepillo suave para pelo corto', 'precio' => 15000, 'imagen' => 'cepillo.jpg'];
                $recomendaciones[] = ['nombre' => 'Champú para piel sensible', 'precio' => 25000, 'imagen' => 'champu.jpg'];
                break;
            case 'mediano':
                $recomendaciones[] = ['nombre' => 'Cepillo de cerdas firmes', 'precio' => 20000, 'imagen' => 'cepillo.jpg'];
                $recomendaciones[] = ['nombre' => 'Champú antipulgas', 'precio' => 30000, 'imagen' => 'champu.jpg'];
                break;
            case 'grande':
                $recomendaciones[] = ['nombre' => 'Cepillo deslanador', 'precio' => 35000, 'imagen' => 'cepillo.jpg'];
                $recomendaciones[] = ['nombre' => 'Champú para pelo largo', 'precio' => 35000, 'imagen' => 'champu.jpg'];
                break;
        }
        
        if ($mascota->temperamento == 'nervioso') {
            $recomendaciones[] = ['nombre' => 'Ferlax - Calmante natural', 'precio' => 45000, 'imagen' => 'ferlax.jpg'];
        }
        
        return view('mascotas.recomendaciones', compact('mascota', 'recomendaciones'));
    }
}