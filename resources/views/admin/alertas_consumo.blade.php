@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <i class="fas fa-exclamation-triangle"></i> ⚠️ Alertas de Consumo Elevado
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($alertas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>No hay alertas pendientes</h4>
                    <p>Todos los consumos están dentro del límite normal</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Groomer</th>
                                <th>Mensaje</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertas as $alerta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($alerta->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ explode(' ', $alerta->mensaje)[5] ?? 'N/A' }}</td>
                                <td>{{ Str::limit($alerta->mensaje, 80) }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $alerta->estado == 'PENDIENTE' ? 'warning' : ($alerta->estado == 'APROBADA' ? 'success' : 'danger') }}">
                                        {{ $alerta->estado }}
                                    </span>
                                </td>
                                <td>
                                    @if($alerta->estado == 'PENDIENTE')
                                    <form action="{{ route('admin.alertas.actualizar', $alerta->id_notificacion) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" name="accion" value="aprobar" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="submit" name="accion" value="rechazar" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-muted">Procesado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection