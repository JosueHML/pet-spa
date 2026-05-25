@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-calendar-alt fa-2x"></i>
                <span class="fs-3 fw-bold ms-2">Mis Citas</span>
            </div>
            <!-- ✅ BOTÓN NUEVA CITA - SIN CONDICIONES, SIEMPRE VISIBLE -->
            <a href="{{ route('citas.create') }}" class="btn btn-light btn-lg shadow-sm">
                <i class="fas fa-plus-circle fa-lg me-2"></i> Nueva Cita
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($citas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                    <h4>No tienes citas agendadas</h4>
                    <p class="text-muted">Agenda tu primera cita para cuidar a tu mascota</p>
                    <a href="{{ route('citas.create') }}" class="btn btn-success btn-lg mt-2 shadow-sm">
                        <i class="fas fa-plus-circle"></i> Agendar primera cita
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-dog"></i> Mascota</th>
                                <th><i class="fas fa-cut"></i> Servicio</th>
                                <th><i class="fas fa-calendar-day"></i> Fecha</th>
                                <th><i class="fas fa-clock"></i> Hora</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-success text-white text-center me-2 shadow-sm" style="width: 35px; height: 35px; line-height: 35px;">
                                            {{ strtoupper(substr($cita->mascota->nombre_mascota, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $cita->mascota->nombre_mascota }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $cita->mascota->raza ?? 'Sin raza' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ $cita->servicio->nombre_servicio }}</strong>
                                    <br>
                                    <small class="text-success fw-bold">${{ number_format($cita->servicio->precio, 2) }}</small>
                                </td>
                                <td class="text-nowrap align-middle">
                                    <i class="fas fa-calendar-alt text-success me-1"></i>
                                    {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('d/m/Y') }}
                                </td>
                                <td class="text-nowrap align-middle">
                                    <i class="fas fa-clock text-primary me-1"></i>
                                    {{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('H:i') }} hrs
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-hourglass-half"></i> 
                                        {{ \Carbon\Carbon::parse($cita->fecha_inicio)->diffInMinutes($cita->fecha_fin) }} min
                                    </small>
                                </td>
                                <td class="align-middle">
                                    @php
                                        $badgeClass = 'secondary';
                                        $badgeIcon = 'fa-question-circle';
                                        if($cita->estado == 'PENDIENTE') {
                                            $badgeClass = 'warning';
                                            $badgeIcon = 'fa-hourglass-half';
                                        } elseif($cita->estado == 'CONFIRMADA') {
                                            $badgeClass = 'info';
                                            $badgeIcon = 'fa-check-circle';
                                        } elseif($cita->estado == 'COMPLETADA') {
                                            $badgeClass = 'success';
                                            $badgeIcon = 'fa-check-double';
                                        } elseif($cita->estado == 'CANCELADA') {
                                            $badgeClass = 'danger';
                                            $badgeIcon = 'fa-times-circle';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} p-2 px-3">
                                        <i class="fas {{ $badgeIcon }}"></i> {{ $cita->estado }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- FACTURAR: Solo Admin o Cajero -->
                                        @if(in_array(Auth::user()->id_rol, [1, 2]) && !in_array($cita->estado, ['COMPLETADA', 'CANCELADA']))
                                            <a href="{{ route('facturas.create.cita', $cita->id_cita) }}" 
                                               class="btn btn-success" title="Facturar">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                        @endif
                                        
                                        <!-- FICHA GROOMING: Solo Admin o Groomer -->
                                        @if(in_array(Auth::user()->id_rol, [1, 3]) && $cita->estado != 'CANCELADA')
                                            <a href="{{ route('grooming.show', $cita->id_cita) }}" 
                                               class="btn btn-info" title="Ficha de Grooming">
                                                <i class="fas fa-clipboard-list"></i>
                                            </a>
                                        @endif
                                        
                                        <!-- VER DETALLE -->
                                        <a href="{{ route('citas.show', $cita->id_cita) }}" 
                                           class="btn btn-primary" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- EDITAR: Admin, Cajero, Groomer -->
                                        @if(in_array(Auth::user()->id_rol, [1, 2, 3]) && !in_array($cita->estado, ['COMPLETADA', 'CANCELADA']))
                                            <a href="{{ route('citas.edit', $cita->id_cita) }}" 
                                               class="btn btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        <!-- CANCELAR: Solo si está pendiente -->
                                        @if($cita->estado == 'PENDIENTE')
                                            <a href="{{ route('citas.cancel', $cita->id_cita) }}" 
                                               class="btn btn-danger" 
                                               title="Cancelar cita"
                                               onclick="return confirm('¿Cancelar esta cita?')">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                        @endif
                                        
                                        <!-- ELIMINAR: Solo Admin -->
                                        @if(Auth::user()->id_rol == 1)
                                            <form action="{{ route('citas.destroy', $cita->id_cita) }}" 
                                                  method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-dark" 
                                                        title="Eliminar permanentemente"
                                                        onclick="return confirm('¿Eliminar permanentemente?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Resumen de citas -->
                <div class="alert alert-info mt-3">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h5 class="mb-0">{{ $citas->where('estado', 'PENDIENTE')->count() }}</h5>
                            <small>Citas Pendientes</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-0">{{ $citas->where('estado', 'CONFIRMADA')->count() }}</h5>
                            <small>Citas Confirmadas</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-0">{{ $citas->where('estado', 'COMPLETADA')->count() }}</h5>
                            <small>Citas Completadas</small>
                        </div>
                        <div class="col-md-3">
                            <h5 class="mb-0">{{ $citas->where('estado', 'CANCELADA')->count() }}</h5>
                            <small>Citas Canceladas</small>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                @if(method_exists($citas, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $citas->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.08);
    transition: all 0.3s ease;
}
.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}
.btn-group .btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}
.table th, .table td {
    vertical-align: middle;
}
.card {
    border-radius: 15px;
    overflow: hidden;
}
.card-header {
    padding: 20px 25px;
}
.rounded-circle {
    font-weight: bold;
    font-size: 1rem;
}
</style>
@endsection