@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            🧪 Panel de Pruebas - Administrador
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-plus fa-3x text-primary mb-2"></i>
                            <h5>Crear Cita de Prueba</h5>
                            <p>Genera una cita para 24 horas después</p>
                            <form method="POST" action="{{ route('admin.pruebas.crear-cita') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">📅 Crear Cita de Prueba</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-bell fa-3x text-success mb-2"></i>
                            <h5>Enviar Recordatorios</h5>
                            <p>Ejecuta el envío de recordatorios de citas</p>
                            <form method="POST" action="{{ route('admin.pruebas.enviar-recordatorios') }}">
                                @csrf
                                <button type="submit" class="btn btn-success">⏰ Enviar Recordatorios Ahora</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>📋 Últimas citas creadas:</strong>
                        <table class="table table-sm mt-2">
                            <thead>
                                <tr><th>ID</th><th>Mascota</th><th>Fecha</th><th>Estado</th></tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasCitas as $cita)
                                <tr>
                                    <td>{{ $cita->id_cita }}</td>
                                    <td>{{ $cita->mascota->nombre_mascota ?? 'N/A' }}</td>
                                    <td>{{ $cita->fecha_inicio->format('d/m/Y H:i') }}</td>
                                    <td>{{ $cita->estado }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection