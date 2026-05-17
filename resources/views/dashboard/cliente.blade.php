@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-metrics fade-in">
        <div class="card-metric bg-metric-primary">
            <h3>{{ $totalMascotas ?? App\Models\Mascota::where('id_cliente', Auth::user()->cliente->id_cliente ?? 0)->count() }}</h3>
            <p>Mis mascotas</p>
        </div>
        <div class="card-metric bg-metric-success">
            <h3>{{ $totalCitas ?? App\Models\Cita::whereHas('mascota', function($q) { $q->where('id_cliente', Auth::user()->cliente->id_cliente ?? 0); })->count() }}</h3>
            <p>Citas agendadas</p>
        </div>
        <div class="card-metric bg-metric-warning">
            <h3>${{ number_format($totalGastado ?? App\Models\Factura::where('id_cliente', Auth::user()->cliente->id_cliente ?? 0)->sum('total'), 2) }}</h3>
            <p>Total gastado</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg fade-in">
                <div class="card-header bg-gradient-success text-white" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-paw fa-2x"></i>
                            <h3 class="d-inline-block ml-3 mb-0">Panel de Cliente</h3>
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
                        <i class="fas fa-user-circle"></i> <strong>¡Bienvenido, {{ Auth::user()->name }}!</strong>
                        <p class="mb-0 mt-1">Administra tus mascotas y citas fácilmente.</p>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-dog fa-3x text-primary mb-2"></i>
                                    <h5>🐾 Mis Mascotas</h5>
                                    <p class="text-muted">Registra y gestiona tus mascotas</p>
                                    <a href="{{ route('mascotas.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver mascotas
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-calendar-check fa-3x text-success mb-2"></i>
                                    <h5>📅 Mis Citas</h5>
                                    <p class="text-muted">Solicita y consulta tus citas</p>
                                    <a href="{{ route('citas.index') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver citas
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-shopping-cart fa-3x text-warning mb-2"></i>
                                    <h5>🛒 Tienda / Productos</h5>
                                    <p class="text-muted">Compra productos para tu mascota</p>
                                    <a href="{{ route('productos.index') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver productos
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-info mb-2"></i>
                                    <h5>📄 Mis Facturas</h5>
                                    <p class="text-muted">Historial de facturación</p>
                                    <a href="{{ route('facturas.index') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver facturas
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-bell fa-3x text-secondary mb-2"></i>
                                    <h5>🔔 Mis Notificaciones</h5>
                                    <p class="text-muted">Recordatorios y avisos</p>
                                    <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver notificaciones
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-history fa-3x text-dark mb-2"></i>
                                    <h5>📜 Historial Servicios</h5>
                                    <p class="text-muted">Servicios realizados a tus mascotas</p>
                                    <a href="{{ route('citas.index') }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-arrow-right"></i> Ver historial
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