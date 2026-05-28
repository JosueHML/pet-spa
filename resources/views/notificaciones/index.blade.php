@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-bell"></i> Mis Notificaciones
            </div>
            @if(Auth::user()->id_rol == 1)
                <a href="{{ url('/admin/recordatorios/enviar') }}" class="btn btn-warning btn-sm">                    <i class="fas fa-clock"></i> ⏰ Enviar Recordatorios Ahora
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @php
                use App\Models\Notificacion;
                $notificaciones = Notificacion::where('id_usuario', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
            @endphp

            @if($notificaciones->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <p class="lead">No tienes notificaciones</p>
                    <small class="text-muted">Las notificaciones aparecerán aquí cuando tengas actividad</small>
                </div>
            @else
                <div class="list-group">
                    @foreach($notificaciones as $notificacion)
                        <div class="list-group-item list-group-item-{{ $notificacion->leida ? 'secondary' : 'warning' }} list-group-item-action">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <!-- Icono según tipo -->
                                        @if($notificacion->tipo == 'RECORDATORIO_24H' || $notificacion->tipo == 'RECORDATORIO_2H')
                                            <i class="fas fa-clock text-primary me-2"></i>
                                        @elseif($notificacion->tipo == 'LISTO_RECOGER')
                                            <i class="fas fa-paw text-success me-2"></i>
                                        @elseif($notificacion->tipo == 'CONSUMO_ELEVADO')
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        @elseif($notificacion->tipo == 'RESPUESTA_CONSUMO')
                                            <i class="fas fa-check-circle text-info me-2"></i>
                                        @elseif($notificacion->tipo == 'COMPRA_PRODUCTOS')
                                            <i class="fas fa-shopping-cart text-success me-2"></i>
                                        @else
                                            <i class="fas fa-bell text-secondary me-2"></i>
                                        @endif
                                        <strong>
                                            @if($notificacion->tipo == 'RECORDATORIO_24H')
                                                🔔 Recordatorio 24 horas
                                            @elseif($notificacion->tipo == 'RECORDATORIO_2H')
                                                ⏰ Recordatorio 2 horas
                                            @elseif($notificacion->tipo == 'LISTO_RECOGER')
                                                🐾 Mascota lista para recoger
                                            @elseif($notificacion->tipo == 'CONSUMO_ELEVADO')
                                                ⚠️ Alerta de consumo elevado
                                            @elseif($notificacion->tipo == 'RESPUESTA_CONSUMO')
                                                📋 Respuesta del Administrador
                                            @elseif($notificacion->tipo == 'COMPRA_PRODUCTOS')
                                                🛒 Compra confirmada
                                            @else
                                                {{ $notificacion->tipo }}
                                            @endif
                                        </strong>
                                    </div>
                                    <p class="mb-1">{{ $notificacion->mensaje }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($notificacion->created_at)->format('d/m/Y H:i') }}
                                        <i class="fas fa-envelope ms-2"></i> Canal: {{ $notificacion->canal }}
                                    </small>
                                </div>
                                <div class="ms-3">
                                    @if(!$notificacion->leida)
                                        <a href="{{ route('notificacion.marcar-leida', $notificacion->id_notificacion) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Marcar como leída
                                        </a>
                                    @else
                                        <span class="badge bg-secondary p-2">
                                            <i class="fas fa-check-circle"></i> Leída
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notificaciones->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection