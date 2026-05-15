<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgendaBloqueo;
use App\Models\Groomer;
use Illuminate\Http\Request;

class AgendaBloqueoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('role:1'); // Solo admin
    }

    public function index()
    {
        $bloqueos = AgendaBloqueo::with('groomer.user')->orderBy('fecha_bloqueo', 'desc')->paginate(15);
        $groomers = Groomer::with('user')->get();
        return view('admin.bloqueos.index', compact('bloqueos', 'groomers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_bloqueo' => 'required|date',
            'tipo' => 'required|in:FERIADO,MANTENIMIENTO,AUSENCIA',
            'alcance' => 'required|in:GLOBAL,INDIVIDUAL',
            'id_groomer' => 'required_if:alcance,INDIVIDUAL|nullable|exists:groomers,id_groomer',
            'motivo' => 'nullable|string|max:200',
        ]);

        AgendaBloqueo::create($request->all());

        return redirect()->route('admin.bloqueos.index')->with('success', 'Bloqueo registrado correctamente');
    }

    public function destroy($id)
    {
        $bloqueo = AgendaBloqueo::findOrFail($id);
        $bloqueo->delete();

        return redirect()->route('admin.bloqueos.index')->with('success', 'Bloqueo eliminado');
    }
}