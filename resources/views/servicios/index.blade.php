@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>Gestión de Servicios</span>
            <a href="{{ route('servicios.create') }}" class="btn btn-light btn-sm">+ Nuevo Servicio</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Duración (min)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicios as $servicio)
                    <tr>
                        <td>{{ $servicio->id_servicio }}</td>
                        <td>{{ $servicio->nombre_servicio }}</td>
                        <td>{{ $servicio->descripcion ?? '-' }}</td>
                        <td>${{ number_format($servicio->precio, 2) }}</td>
                        <td>{{ $servicio->duracion_minutos }} min</td>
                        <td>
                            <a href="{{ route('servicios.edit', $servicio->id_servicio) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('servicios.destroy', $servicio->id_servicio) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este servicio?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection