@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-cut"></i> Panel de Groomer
                </div>
                <div class="card-body">
                    <h4>¡Bienvenido Groomer, {{ Auth::user()->name }}!</h4>
                    <p class="text-muted">Aquí puedes gestionar:</p>

                    <div class="row mt-4">
                        <!-- Mi agenda de citas -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-calendar-alt fa-3x text-info"></i>
                                    <h5 class="mt-2">📅 Mi agenda de citas</h5>
                                    <a href="{{ route('citas.index') }}" class="btn btn-info btn-sm mt-2">
                                        Ver agenda
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Fichas de grooming activas -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-clipboard-list fa-3x text-warning"></i>
                                    <h5 class="mt-2">📋 Fichas activas</h5>
                                    <a href="{{ route('grooming.fichas.activas') }}" class="btn btn-warning btn-sm mt-2">
                                        Ver fichas
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Checklist de servicios -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                    <h5 class="mt-2">✅ Checklist completados</h5>
                                    <a href="{{ route('grooming.checklist') }}" class="btn btn-success btn-sm mt-2">
                                        Ver checklist
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Fotos antes/después -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-camera fa-3x text-primary"></i>
                                    <h5 class="mt-2">📸 Galería de fotos</h5>
                                    <a href="{{ route('grooming.fotos') }}" class="btn btn-primary btn-sm mt-2">
                                        Ver fotos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de citas pendientes del día -->
                    <div class="mt-4">
                        <h5>📋 Citas pendientes de hoy</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Mascota</th>
                                    <th>Servicio</th>
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
                                    <td>{{ \Carbon\Carbon::parse($cita->fecha_inicio)->format('H:i') }}</td>
                                    <td>{{ $cita->mascota->nombre_mascota }}</td>
                                    <td>{{ $cita->servicio->nombre_servicio }}</td>
                                    <td>
                                        <a href="{{ route('grooming.show', $cita->id_cita) }}" class="btn btn-info btn-sm">
                                            📸 Abrir ficha
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay citas pendientes para hoy</td>
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
@endsection