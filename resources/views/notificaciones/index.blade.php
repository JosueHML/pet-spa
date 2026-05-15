@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-bell"></i> Mis Notificaciones
            @if(Auth::user()->id_rol == 1)
                <a href="{{ route('admin.recordatorios.enviar') }}" class="btn btn-warning btn-sm float-end">⏰ Enviar Recordatorios Ahora</a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($notificaciones->isEmpty())
                <div class="text-center">No tienes notificaciones.</div>
            @else
                <div class="list-group">
                    @foreach($notificaciones as $notif)
                        <div class="list-group-item {{ $notif->estado == 'PENDIENTE' ? 'list-group-item-warning' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>
                                        @if($notif->tipo == 'RECORDATORIO_24H')
                                            🔔 Recordatorio 24 horas
                                        @elseif($notif->tipo == 'RECORDATORIO_2H')
                                            ⏰ Recordatorio 2 horas
                                        @elseif($notif->tipo == 'LISTO_RECOGER')
                                            🐾 Mascota lista para recoger
                                        @elseif($notif->tipo == 'COMPRA_PRODUCTOS')
                                            🛒 Compra confirmada
                                        @else
                                            {{ $notif->tipo }}
                                        @endif
                                    </strong>
                                    <p class="mb-0">{{ $notif->mensaje }}</p>
                                    <small class="text-muted">Recibido: {{ $notif->created_at->format('d/m/Y H:i') }}</small>
                                    <br>
                                    <small><strong>Canal:</strong> {{ $notif->canal }}</small>
                                </div>
                                @if($notif->estado == 'PENDIENTE')
                                    <a href="{{ route('notificacion.marcar-leida', $notif->id_notificacion) }}" class="btn btn-sm btn-success">Marcar como leída</a>
                                @else
                                    <span class="badge bg-secondary">Leída</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $notificaciones->links() }}
            @endif
        </div>
    </div>
</div>
@endsection