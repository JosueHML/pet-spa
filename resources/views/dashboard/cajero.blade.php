@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-warning text-white" style="background: linear-gradient(135deg, #ffc107, #ff9800);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cash-register fa-2x"></i>
                            <h3 class="d-inline-block ml-3 mb-0">Panel de Cajero</h3>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-clock"></i> {{ now()->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-user-circle"></i> <strong>¡Bienvenido Cajero, {{ Auth::user()->name }}!</strong>
                        <p class="mb-0 mt-1">Sistema de facturación y gestión de pagos.</p>
                    </div>

                    <div class="row mt-4">
                        <!-- Tarjetas de métricas -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-primary text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Factura::whereDate('created_at', today())->count() }}</h2>
                                    <p class="mb-0">Facturas Hoy</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-success text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-dollar-sign fa-3x mb-2"></i>
                                    <h2 class="mb-0">${{ number_format(App\Models\Factura::whereDate('created_at', today())->sum('total'), 0) }}</h2>
                                    <p class="mb-0">Ventas Hoy</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-info text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-calendar-check fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Cita::whereDate('fecha_inicio', today())->count() }}</h2>
                                    <p class="mb-0">Citas Hoy</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-warning text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-clock fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Cita::whereDate('fecha_inicio', today())->where('estado', 'PENDIENTE')->count() }}</h2>
                                    <p class="mb-0">Pendientes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-info mb-2"></i>
                                    <h5>💰 Facturación</h5>
                                    <p class="text-muted">Registrar pagos y emitir facturas</p>
                                    <a href="{{ route('facturas.index') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ir a Facturación
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-3x text-success mb-2"></i>
                                    <h5>📅 Ver Citas</h5>
                                    <p class="text-muted">Consulta y gestiona citas</p>
                                    <a href="{{ route('citas.index') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver Citas
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-box-open fa-3x text-primary mb-2"></i>
                                    <h5>📦 Productos</h5>
                                    <p class="text-muted">Catálogo de productos</p>
                                    <a href="{{ route('productos.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver Productos
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice fa-3x text-warning mb-2"></i>
                                    <h5>📋 Solicitudes Factura</h5>
                                    <p class="text-muted">Aprobar o rechazar solicitudes</p>
                                    <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver Solicitudes
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-cash-register fa-3x text-danger mb-2"></i>
                                    <h5>💰 Cierre de Caja</h5>
                                    <p class="text-muted">Consolidar ventas del día</p>
                                    <a href="{{ route('admin.cierres.index') }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-arrow-right"></i> Cierre de Caja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection