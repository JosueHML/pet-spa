@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-info text-white" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cut fa-2x"></i>
                            <h3 class="d-inline-block ml-3 mb-0">Panel de Groomer</h3>
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
                        <i class="fas fa-user-circle"></i> <strong>¡Bienvenido Groomer, {{ Auth::user()->name }}!</strong>
                        <p class="mb-0 mt-1">Gestiona tu agenda, fichas técnicas y servicios.</p>
                    </div>

                    <!-- Tarjetas de métricas -->
                    <div class="row mt-4">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-primary text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Cita::where('id_groomer', Auth::user()->groomer->id_groomer ?? 0)->whereDate('fecha_inicio', today())->count() }}</h2>
                                    <p class="mb-0">Citas Hoy</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-warning text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-spinner fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Cita::where('id_groomer', Auth::user()->groomer->id_groomer ?? 0)->where('estado', 'PENDIENTE')->count() }}</h2>
                                    <p class="mb-0">Pendientes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-success text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\Cita::where('id_groomer', Auth::user()->groomer->id_groomer ?? 0)->where('estado', 'COMPLETADA')->count() }}</h2>
                                    <p class="mb-0">Completadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-center bg-info text-white h-100">
                                <div class="card-body">
                                    <i class="fas fa-folder-open fa-3x mb-2"></i>
                                    <h2 class="mb-0">{{ App\Models\FichaGrooming::whereHas('cita', function($q) { $q->where('id_groomer', Auth::user()->groomer->id_groomer ?? 0); })->where('estado_ficha', 'ABIERTA')->count() }}</h2>
                                    <p class="mb-0">Fichas Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-3x text-info"></i>
                                    <h5 class="mt-2">📅 Mi Agenda</h5>
                                    <p class="text-muted">Ver citas del día</p>
                                    <a href="{{ route('citas.index') }}" class="btn btn-info btn-sm mt-2">
                                        <i class="fas fa-arrow-right"></i> Ver agenda
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-clipboard-list fa-3x text-warning"></i>
                                    <h5 class="mt-2">📋 Fichas Activas</h5>
                                    <p class="text-muted">Servicios en proceso</p>
                                    <a href="{{ route('grooming.fichas.activas') }}" class="btn btn-warning btn-sm mt-2">
                                        <i class="fas fa-arrow-right"></i> Ver fichas
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <h5 class="mt-2">✅ Checklist</h5>
                                    <p class="text-muted">Servicios completados</p>
                                    <a href="{{ route('grooming.checklist') }}" class="btn btn-success btn-sm mt-2">
                                        <i class="fas fa-arrow-right"></i> Ver checklist
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card text-center h-100 shadow-sm">
                                <div class="card-body">
                                    <i class="fas fa-camera fa-3x text-primary"></i>
                                    <h5 class="mt-2">📸 Galería</h5>
                                    <p class="text-muted">Fotos de servicios</p>
                                    <a href="{{ route('grooming.fotos') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-arrow-right"></i> Ver fotos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de citas pendientes del día -->
                    <div class="mt-4">
                        <h5><i class="fas fa-list"></i> 📋 Citas pendientes de hoy</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Hora</th>
                                        <th>Mascota</th>
                                        <th>Servicio</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $citasPendientes = App\Models\Cita::with(['mascota', 'servicio'])
                                            ->where('id_groomer', Auth::user()->groomer->id_groomer ?? 0)
                                            ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA'])
                                            ->whereDate('fecha_inicio', now())
                                            ->orderBy('fecha_inicio')
                                            ->get();
                                    @endphp
                                    
                                    @forelse($citasPendientes as $cita)
                                    <tr>
                                        <td><span class="badge bg-primary">{{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('H:i') }}</span></td>
                                        <td>
                                            <strong>{{ $cita->mascota->nombre_mascota }}</strong><br>
                                            <small class="text-muted">{{ $cita->mascota->raza ?? 'Sin raza' }}</small>
                                        </td>
                                        <td>{{ $cita->servicio->nombre_servicio }}</td>
                                        <td>
                                            <span class="badge bg-{{ $cita->estado == 'PENDIENTE' ? 'warning' : 'info' }}">
                                                {{ $cita->estado }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('grooming.show', $cita->id_cita) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Abrir ficha
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                                No hay citas pendientes para hoy
                                            </td>
                                        </tr>
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