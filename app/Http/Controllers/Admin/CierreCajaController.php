<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Models\CierreCaja;
use App\Models\Cajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AuditLogHelper;

class CierreCajaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.inactivity']);
        $this->middleware('role:1,2'); // Admin y Cajero
    }

    public function index()
    {
        $cierres = CierreCaja::with('cajero.user')->orderBy('fecha_cierre', 'desc')->paginate(15);
        return view('admin.cierres.index', compact('cierres'));
    }

    public function create()
    {
        $hoy = now()->toDateString();
        
        $totalEfectivo = Factura::whereDate('created_at', $hoy)
            ->where('metodo_pago', 'EFECTIVO')
            ->where('estado_pago', 'PAGADO')
            ->sum('total');
        
        $totalQR = Factura::whereDate('created_at', $hoy)
            ->where('metodo_pago', 'QR')
            ->where('estado_pago', 'PAGADO')
            ->sum('total');
        
        $totalTransferencia = Factura::whereDate('created_at', $hoy)
            ->where('metodo_pago', 'TRANSFERENCIA')
            ->where('estado_pago', 'PAGADO')
            ->sum('total');
        
        $totalGeneral = $totalEfectivo + $totalQR + $totalTransferencia;

        $cierreExistente = CierreCaja::whereDate('fecha_cierre', $hoy)->first();

        return view('admin.cierres.create', compact(
            'totalEfectivo', 'totalQR', 'totalTransferencia', 'totalGeneral', 'cierreExistente'
        ));
    }

    public function store(Request $request)
    {
        $hoy = now();
        
        $existe = CierreCaja::whereDate('fecha_cierre', $hoy)->first();
        if ($existe) {
            return redirect()->route('admin.cierres.index')->with('error', 'Ya existe un cierre de caja para hoy');
        }

        $cajero = Cajero::where('id_usuario', Auth::id())->first();
        
        if (!$cajero) {
            if (Auth::user()->id_rol == 1) {
                $cajero = Cajero::first();
                if (!$cajero) {
                    $cajero = Cajero::create([
                        'id_usuario' => Auth::id(),
                        'permisos_pagos' => 1,
                    ]);
                }
            } else {
                return back()->with('error', 'No tienes permisos de cajero');
            }
        }

        $cierre = CierreCaja::create([
            'id_cajero' => $cajero->id_cajero,
            'total_efectivo' => $request->total_efectivo,
            'total_qr' => $request->total_qr,
            'total_transferencia' => $request->total_transferencia,
            'total_general' => $request->total_general,
            'fecha_cierre' => $hoy,
            'observaciones' => $request->observaciones,
        ]);

        AuditLogHelper::log('CIERRE_CAJA', [
            'cajero' => Auth::user()->name,
            'total' => $request->total_general,
            'cierre_id' => $cierre->id_cierre
        ]);

        return redirect()->route('admin.cierres.index')->with('success', 'Cierre de caja realizado correctamente');
    }

    public function show($id)
    {
        $cierre = CierreCaja::with('cajero.user')->findOrFail($id);
        return view('admin.cierres.show', compact('cierre'));
    }

    public function edit($id)
    {
        $cierre = CierreCaja::findOrFail($id);
        
        // Solo permitir editar si es del día actual o si es Admin
        if ($cierre->fecha_cierre->toDateString() != now()->toDateString() && Auth::user()->id_rol != 1) {
            return redirect()->route('admin.cierres.index')->with('error', 'No puedes editar cierres de días anteriores');
        }
        
        return view('admin.cierres.edit', compact('cierre'));
    }

    public function update(Request $request, $id)
    {
        $cierre = CierreCaja::findOrFail($id);
        
        // Solo permitir editar si es del día actual o si es Admin
        if ($cierre->fecha_cierre->toDateString() != now()->toDateString() && Auth::user()->id_rol != 1) {
            return redirect()->route('admin.cierres.index')->with('error', 'No puedes editar cierres de días anteriores');
        }

        $request->validate([
            'total_efectivo' => 'required|numeric|min:0',
            'total_qr' => 'required|numeric|min:0',
            'total_transferencia' => 'required|numeric|min:0',
            'total_general' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $cierre->update([
            'total_efectivo' => $request->total_efectivo,
            'total_qr' => $request->total_qr,
            'total_transferencia' => $request->total_transferencia,
            'total_general' => $request->total_general,
            'observaciones' => $request->observaciones,
        ]);

        AuditLogHelper::log('EDITAR_CIERRE_CAJA', [
            'admin' => Auth::user()->name,
            'cierre_id' => $id,
            'total_anterior' => $cierre->getOriginal('total_general'),
            'total_nuevo' => $request->total_general
        ]);

        return redirect()->route('admin.cierres.index')->with('success', 'Cierre de caja actualizado correctamente');
    }

    public function destroy($id)
    {
        $cierre = CierreCaja::findOrFail($id);
        
        // Solo Admin puede eliminar
        if (Auth::user()->id_rol != 1) {
            return redirect()->route('admin.cierres.index')->with('error', 'No tienes permiso para eliminar cierres de caja');
        }
        
        $cierre->delete();

        AuditLogHelper::log('ELIMINAR_CIERRE_CAJA', [
            'admin' => Auth::user()->name,
            'cierre_id' => $id,
            'fecha_cierre' => $cierre->fecha_cierre,
            'total' => $cierre->total_general
        ]);

        return redirect()->route('admin.cierres.index')->with('success', 'Cierre de caja eliminado correctamente');
    }
}