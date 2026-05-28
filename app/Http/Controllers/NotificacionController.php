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
            ->get();
        
        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeida($id)
    {
        $notificacion = Notificacion::where('id_notificacion', $id)
            ->where('id_usuario', Auth::id())
            ->first();
        
        if ($notificacion) {
            $notificacion->leida = true;
            $notificacion->save();
        }
        
        return redirect()->back();
    }
}