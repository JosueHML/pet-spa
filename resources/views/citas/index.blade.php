@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span>Mis Citas</span>
            <a href="{{ route('citas.create') }}" class="btn btn-light btn-sm">+ Nueva Cita</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($citas->isEmpty())
                <div class="text-center">No tienes citas agendadas.</div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mascota</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                        <tr>
                            <td>{{ $cita->mascota->nombre_mascota }}</td>
                            <td>{{ $cita->servicio->nombre_servicio }}</td>
                            <td>{{ $cita->fecha_inicio->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $cita->estado == 'PENDIENTE' ? 'warning' : ($cita->estado == 'CONFIRMADA' ? 'info' : ($cita->estado == 'COMPLETADA' ? 'success' : 'danger')) }}">
                                    {{ $cita->estado }}
                                </span>
                            </td>
                            <td class="d-flex gap-1">
                                @if($cita->estado != 'COMPLETADA')
                                    <a href="{{ route('facturas.create.cita', $cita->id_cita) }}" class="btn btn-success btn-sm">Facturar</a>
                                @endif
                                
                                <a href="{{ route('citas.edit', $cita->id_cita) }}" class="btn btn-warning btn-sm">Editar</a>
                                
                                @if($cita->estado == 'PENDIENTE')
                                    <a href="{{ route('citas.cancel', $cita->id_cita) }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar esta cita?')">Cancelar</a>
                                @endif
                                
                                <a href="{{ route('grooming.show', $cita->id_cita) }}" class="btn btn-info btn-sm">📸 Ficha</a>
                                
                                <form action="{{ route('citas.destroy', $cita->id_cita) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-dark btn-sm" onclick="return confirm('¿Eliminar permanentemente?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection