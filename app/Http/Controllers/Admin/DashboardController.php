<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cita;
use App\Models\Factura;
use App\Models\Producto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
    }

    public function index()
    {
        $totalUsuarios = User::count();
        $totalCitas = Cita::count();
        $totalIngresos = Factura::where('estado_pago', 'PAGADO')->sum('total');
        $totalProductos = Producto::count();
        
        return view('dashboard.admin', compact('totalUsuarios', 'totalCitas', 'totalIngresos', 'totalProductos'));
    }
}