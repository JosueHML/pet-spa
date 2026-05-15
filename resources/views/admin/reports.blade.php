@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            📊 Reportes y Estadísticas
        </div>
        <div class="card-body">
            
            <!-- Tarjetas de resumen -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Citas</h5>
                            <h3>{{ $totalCitas }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Ingresos Totales</h5>
                            <h3>${{ number_format($totalFacturas, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Ticket Promedio</h5>
                            <h3>${{ number_format($ticketPromedio ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Total Clientes</h5>
                            <h3>{{ $totalClientes }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Top Servicios -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">🏆 Top Servicios Más Solicitados</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Servicio</th><th>Citas</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topServicios as $servicio)
                                    <tr>
                                        <td>{{ $servicio->nombre_servicio }}</td>
                                        <td>{{ $servicio->total }}</td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="2">No hay datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Productos -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">🏆 Top Productos Más Vendidos</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Producto</th><th>Unidades</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($topProductos as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre_producto }}</td>
                                        <td>{{ $producto->total_vendidos }}</td>
                                    </tr>
                                    @empty
                                        <td><td colspan="2">No hay datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Ocupación de Groomers -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">✂️ Ocupación de Groomers (Mes Actual)</div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Groomer</th><th>Citas atendidas</th></tr>
                                </thead>
                                <tbody>
                                    @forelse($ocupacionGroomers as $groomer)
                                    <tr>
                                        <td>{{ $groomer->groomer_name }}</td>
                                        <td>{{ $groomer->total_citas }}</td>
                                    </tr>
                                    @empty
                                        <td><td colspan="2">No hay datos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection