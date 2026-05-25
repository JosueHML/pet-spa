@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-calendar-alt"></i> Detalle de la Cita
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-dog"></i> Mascota:</strong> {{ $cita->mascota->nombre_mascota }}</p>
                            <p><strong><i class="fas fa-cut"></i> Servicio:</strong> {{ $cita->servicio->nombre_servicio }}</p>
                            <p><strong><i class="fas fa-user"></i> Groomer:</strong> {{ $cita->groomer->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-calendar"></i> Fecha:</strong> {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y') }}</p>
                            <p><strong><i class="fas fa-clock"></i> Hora:</strong> {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('H:i') }} hrs</p>
                            <p><strong><i class="fas fa-hourglass-half"></i> Duración:</strong> {{ \Carbon\Carbon::parse($cita->fecha_inicio)->diffInMinutes($cita->fecha_fin) }} minutos</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong><i class="fas fa-info-circle"></i> Estado:</strong>
                        <span class="badge bg-{{ $cita->estado == 'PENDIENTE' ? 'warning' : ($cita->estado == 'CONFIRMADA' ? 'info' : ($cita->estado == 'COMPLETADA' ? 'success' : 'danger')) }}">
                            {{ $cita->estado }}
                        </span>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        @if($cita->estado == 'PENDIENTE')
                            <a href="{{ route('citas.cancel', $cita->id_cita) }}" class="btn btn-danger" onclick="return confirm('¿Cancelar esta cita?')">
                                <i class="fas fa-times-circle"></i> Cancelar Cita
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection