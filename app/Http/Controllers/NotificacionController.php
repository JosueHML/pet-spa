<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notificaciones = Notificacion::where('id_usuario', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id_usuario', Auth::id())->findOrFail($id);
        $notificacion->estado = 'LEIDA';
        $notificacion->save();

        return redirect()->route('notificaciones.index')->with('success', 'Notificación marcada como leída');
    }
}