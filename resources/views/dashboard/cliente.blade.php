@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-metrics fade-in">
        <div class="card-metric bg-metric-primary">
            <h3>{{ $totalMascotas ?? 0 }}</h3>
            <p>Mis mascotas</p>
        </div>
        <div class="card-metric bg-metric-success">
            <h3>{{ $totalCitas ?? 0 }}</h3>
            <p>Citas agendadas</p>
        </div>
        <div class="card-metric bg-metric-warning">
            <h3>${{ number_format($totalGastado ?? 0, 2) }}</h3>
            <p>Total gastado</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card fade-in">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-paw"></i> Panel de Cliente
                </div>
                <div class="card-body">
                    <h3>¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">Administra tus mascotas y citas fácilmente</p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-dog fa-3x text-primary mb-2"></i>
                                    <h5>Mis Mascotas</h5>
                                    <a href="{{ route('mascotas.index') }}" class="btn btn-primary">Ver mascotas</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card text-center h-100">
                                <div class-card-body">
                                    <i class="fas fa-calendar-check fa-3x text-success mb-2"></i>
                                    <h5>Mis Citas</h5>
                                    <a href="{{ route('citas.index') }}" class="btn btn-success">Ver citas</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-shopping-cart fa-3x text-warning mb-2"></i>
                                    <h5>Tienda / Productos</h5>
                                    <a href="{{ route('productos.index') }}" class="btn btn-warning">Ver productos</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-info mb-2"></i>
                                    <h5>Mis Facturas</h5>
                                    <a href="{{ route('facturas.index') }}" class="btn btn-info">Ver facturas</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-bell fa-3x text-secondary mb-2"></i>
                                    <h5>Mis Notificaciones</h5>
                                    <a href="{{ route('notificaciones.index') }}" class="btn btn-secondary">Ver notificaciones</a>
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