@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dashboard-metrics fade-in">
        <!-- Tarjetas de métricas -->
        <div class="card-metric bg-metric-primary">
            <h3>{{ $totalUsuarios ?? 0 }}</h3>
            <p>Usuarios registrados</p>
        </div>
        <div class="card-metric bg-metric-success">
            <h3>{{ $totalCitas ?? 0 }}</h3>
            <p>Citas realizadas</p>
        </div>
        <div class="card-metric bg-metric-warning">
            <h3>${{ number_format($totalIngresos ?? 0, 2) }}</h3>
            <p>Ingresos totales</p>
        </div>
        <div class="card-metric bg-metric-info">
            <h3>{{ $totalProductos ?? 0 }}</h3>
            <p>Productos en tienda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card fade-in">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-cog"></i> Panel de Administración
                </div>
                <div class="card-body">
                    <h3>¡Bienvenido Administrador, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted">Sistema de gestión integral para Pet Spa</p>
                    <hr>
                    
                    <div class="row">
                        <!-- Tarjetas principales -->
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-cut fa-2x text-danger mb-2"></i>
                                    <h5>Servicios</h5>
                                    <a href="{{ route('servicios.index') }}" class="btn btn-sm btn-danger">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-box-open fa-2x text-success mb-2"></i>
                                    <h5>Productos</h5>
                                    <a href="{{ route('productos.index') }}" class="btn btn-sm btn-success">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <!-- INSUMOS - TARJETA CORREGIDA (dentro del row) -->
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-flask fa-2x text-info mb-2"></i>
                                    <h5>Insumos</h5>
                                    <a href="{{ route('insumos.index') }}" class="btn btn-sm btn-info">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <h5>Personal</h5>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                                    <h5>Citas</h5>
                                    <a href="{{ route('citas.index') }}" class="btn btn-sm btn-warning">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h5>Reportes</h5>
                                    <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-info">Ver</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-shield-alt fa-2x text-secondary mb-2"></i>
                                    <h5>Seguridad</h5>
                                    <a href="{{ route('2fa.setup') }}" class="btn btn-sm btn-secondary">Configurar 2FA</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-history fa-2x text-secondary mb-2"></i>
                                    <h5>Log de Auditoría</h5>
                                    <a href="{{ route('admin.logs') }}" class="btn btn-sm btn-secondary">Ver logs</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-file-invoice fa-2x text-secondary mb-2"></i>
                                    <h5>Solicitudes de Factura</h5>
                                    <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-sm btn-secondary">Ver solicitudes</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-tags fa-2x text-secondary mb-2"></i>
                                    <h5>Promociones y Descuentos</h5>
                                    <a href="{{ route('admin.promociones.index') }}" class="btn btn-sm btn-secondary">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-cash-register fa-2x text-secondary mb-2"></i>
                                    <h5>Cierre de Caja</h5>
                                    <a href="{{ route('admin.cierres.index') }}" class="btn btn-sm btn-secondary">Gestionar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-truck fa-2x text-secondary mb-2"></i>
                                    <h5>Compras a Proveedores</h5>
                                    <a href="{{ route('admin.compras.index') }}" class="btn btn-sm btn-secondary">Gestionar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel de Pruebas -->
                    <hr>
                    <div class="alert alert-info">
                        <strong>🧪 Panel de Pruebas:</strong>
                        <div class="btn-group mt-2" role="group">
                            <a href="{{ route('admin.pruebas') }}" class="btn btn-warning">🧪 Ir al Panel de Pruebas</a>
                            <a href="{{ route('notificaciones.index') }}" class="btn btn-info">🔔 Ver Notificaciones</a>
                        </div>
                    </div>

                    <!-- Panel de Productividad -->
                    <div class="alert alert-success mt-3">
                        <strong>📈 Reportes Avanzados:</strong>
                        <div class="btn-group mt-2" role="group">
                            <a href="{{ route('admin.productividad.groomer') }}" class="btn btn-success">
                                <i class="fas fa-chart-line"></i> 📊 Productividad por Groomer
                            </a>
                        </div>
                    </div>

                    <!-- Alertas de Consumo -->
                    <div class="alert alert-warning mt-3">
                        <strong>⚠️ Alertas de Inventario:</strong>
                        <div class="btn-group mt-2" role="group">
                            <a href="{{ route('admin.alertas.consumo') }}" class="btn btn-warning">
                                <i class="fas fa-exclamation-triangle"></i> ⚠️ Alertas de Consumo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection