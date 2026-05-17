<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Factura;
use App\Models\Servicio;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('role:1'); // Solo administrador
    }

    public function index()
    {
        // Citas por mes (últimos 6 meses)
        $citasPorMes = Cita::select(
                DB::raw('YEAR(fecha_inicio) as año'),
                DB::raw('MONTH(fecha_inicio) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->where('estado', 'COMPLETADA')
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get();

        // Ingresos por mes
        $ingresosPorMes = Factura::select(
                DB::raw('YEAR(created_at) as año'),
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('SUM(total) as total')
            )
            ->where('estado_pago', 'PAGADO')
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get();

        // Top servicios más populares
        $topServicios = Cita::select('servicios.nombre_servicio', DB::raw('COUNT(*) as total'))
            ->join('servicios', 'citas.id_servicio', '=', 'servicios.id_servicio')
            ->where('citas.estado', 'COMPLETADA')
            ->groupBy('servicios.id_servicio', 'servicios.nombre_servicio')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Top productos más vendidos
        $topProductos = collect();

        // Ticket promedio
        $ticketPromedio = Factura::where('estado_pago', 'PAGADO')->avg('total');

        // Ocupación de groomers
        $ocupacionGroomers = Cita::select(
                'groomers.id_groomer',
                'users.name as groomer_name',
                DB::raw('COUNT(*) as total_citas')
            )
            ->join('groomers', 'citas.id_groomer', '=', 'groomers.id_groomer')
            ->join('users', 'groomers.id_usuario', '=', 'users.id')
            ->where('citas.estado', 'COMPLETADA')
            ->whereMonth('citas.created_at', date('m'))
            ->groupBy('groomers.id_groomer', 'users.name')
            ->orderBy('total_citas', 'desc')
            ->get();

        // Totales generales
        $totalCitas = Cita::count();
        $totalFacturas = Factura::where('estado_pago', 'PAGADO')->sum('total');
        $totalClientes = User::where('id_rol', 4)->count();

        return view('admin.reports', compact(
            'citasPorMes',
            'ingresosPorMes',
            'topServicios',
            'topProductos',
            'ticketPromedio',
            'ocupacionGroomers',
            'totalCitas',
            'totalFacturas',
            'totalClientes'
        ));
    }

    public function productividadGroomer()
    {
        // Productividad por groomer (citas completadas) - CORREGIDO
        $productividad = Cita::select(
                'groomers.id_groomer',
                'users.name as groomer_name',
                DB::raw('COUNT(*) as total_citas'),
                DB::raw('ROUND(AVG(TIMESTAMPDIFF(MINUTE, citas.fecha_inicio, citas.fecha_fin)), 0) as tiempo_promedio'),
                DB::raw('SUM(facturas.total) as total_ingresos')
            )
            ->join('groomers', 'citas.id_groomer', '=', 'groomers.id_groomer')
            ->join('users', 'groomers.id_usuario', '=', 'users.id')
            ->leftJoin('facturas', 'citas.id_cita', '=', 'facturas.id_cita')
            ->where('citas.estado', 'COMPLETADA')
            ->groupBy('groomers.id_groomer', 'users.name')
            ->orderBy('total_citas', 'desc')
            ->get();

        return view('admin.productividad', compact('productividad'));
    }
}