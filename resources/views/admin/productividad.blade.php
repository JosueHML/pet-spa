@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-gradient-danger text-white" style="background: linear-gradient(135deg, #dc3545, #c82333);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-line fa-2x"></i>
                    <h3 class="d-inline-block ml-3 mb-0">📊 Reporte de Productividad por Groomer</h3>
                </div>
                <div>
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-calendar-alt"></i> {{ now()->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            @if($productividad->isEmpty())
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h5>No hay datos de productividad aún</h5>
                    <p>Completa algunas citas para ver estadísticas de rendimiento.</p>
                </div>
            @else
                <!-- Tarjetas de resumen -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-scissors fa-2x mb-2"></i>
                                <h2 class="mb-0">{{ $productividad->sum('total_citas') }}</h2>
                                <p class="mb-0">Total Citas Atendidas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                                <h2 class="mb-0">${{ number_format($productividad->sum('total_ingresos'), 0) }}</h2>
                                <p class="mb-0">Total Ingresos Generados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-tachometer-alt fa-2x mb-2"></i>
                                <h2 class="mb-0">{{ $productividad->count() }}</h2>
                                <p class="mb-0">Groomers Activos</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de productividad -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tablaProductividad">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-user-circle"></i> Groomer</th>
                                <th><i class="fas fa-calendar-check"></i> Citas atendidas</th>
                                <th><i class="fas fa-stopwatch"></i> Tiempo promedio</th>
                                <th><i class="fas fa-chart-line"></i> Ingresos generados</th>
                                <th><i class="fas fa-percent"></i> Rendimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $maxCitas = $productividad->max('total_citas');
                            @endphp
                            @foreach($productividad as $groomer)
                            <tr>
                                <td>
                                    <strong>{{ $groomer->groomer_name }}</strong>
                                    <small class="d-block text-muted">ID: {{ $groomer->id_groomer }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        {{ $groomer->total_citas }} citas
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $promedio = $groomer->tiempo_promedio ?? 0;
                                        $colorPromedio = $promedio <= 60 ? 'success' : ($promedio <= 90 ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge bg-{{ $colorPromedio }} rounded-pill px-3 py-2">
                                        <i class="fas fa-hourglass-half"></i> {{ $promedio }} min
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-success">${{ number_format($groomer->total_ingresos ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $porcentaje = $maxCitas > 0 ? round(($groomer->total_citas / $maxCitas) * 100) : 0;
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: {{ $porcentaje }}%;" 
                                             aria-valuenow="{{ $porcentaje }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $porcentaje }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th><strong>TOTALES</strong></th>
                                <th class="text-center"><strong>{{ $productividad->sum('total_citas') }}</strong></th>
                                <th>-</th>
                                <th class="text-end"><strong>${{ number_format($productividad->sum('total_ingresos'), 2) }}</strong></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Gráfico de barras simple -->
                <div class="mt-5">
                    <h5><i class="fas fa-chart-bar"></i> Comparativa de Productividad</h5>
                    <div class="progress-stacked" style="height: 30px;">
                        @php
                            $maxIngresos = $productividad->max('total_ingresos');
                        @endphp
                        @foreach($productividad as $groomer)
                            @php
                                $widthIngresos = $maxIngresos > 0 ? round(($groomer->total_ingresos / $maxIngresos) * 100) : 0;
                            @endphp
                            <div class="progress-bar bg-{{ $loop->index % 2 == 0 ? 'danger' : 'warning' }}" 
                                 role="progressbar" 
                                 style="width: {{ $widthIngresos }}%"
                                 title="{{ $groomer->groomer_name }}: ${{ number_format($groomer->total_ingresos ?? 0, 2) }}">
                                {{ $groomer->groomer_name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="mt-4 text-center">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Dashboard
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Imprimir Reporte
                </button>
                <button onclick="exportarExcel()" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportarExcel() {
    var tabla = document.getElementById('tablaProductividad');
    var html = tabla.outerHTML;
    var blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'productividad_groomers.xls';
    link.click();
}
</script>

<style>
.card-header {
    border-bottom: none;
}
.table-hover tbody tr:hover {
    background-color: rgba(220, 53, 69, 0.1);
    cursor: pointer;
}
.progress {
    border-radius: 20px;
    overflow: hidden;
}
.progress-stacked .progress-bar {
    border-radius: 0;
    color: white;
    font-size: 12px;
    line-height: 30px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 0 5px;
}
@media print {
    .btn, .card-header .badge, .text-center .btn {
        display: none !important;
    }
    .card {
        border: none !important;
    }
}
</style>
@endsection