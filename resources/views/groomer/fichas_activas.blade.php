@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <i class="fas fa-clipboard-list"></i> 📋 Fichas Activas (ABIERTAS)
        </div>
        <div class="card-body">
            @php
                use App\Models\FichaGrooming;
                $fichasActivas = FichaGrooming::with(['cita.mascota', 'cita.servicio'])
                    ->where('estado_ficha', 'ABIERTA')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($fichasActivas->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>No hay fichas activas</h4>
                    <p class="text-muted">Todas las fichas están completadas</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr><th>ID</th><th>Mascota</th><th>Servicio</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            @foreach($fichasActivas as $ficha)
                            <tr>
                                <td>{{ $ficha->id_ficha }}</td>
                                <td>{{ $ficha->cita->mascota->nombre_mascota ?? 'N/A' }}</td>
                                <td>{{ $ficha->cita->servicio->nombre_servicio ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($ficha->cita->fecha_inicio)->format('d/m/Y H:i') }}</td>
                                <td><span class="badge bg-warning">{{ $ficha->estado_ficha }}</span></td>
                                <td>
                                    <a href="{{ route('grooming.show', $ficha->cita->id_cita) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i> Continuar
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('groomer.dashboard') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection