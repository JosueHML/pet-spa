@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span>Mis Mascotas</span>
                    <a href="{{ route('mascotas.create') }}" class="btn btn-light btn-sm">+ Agregar Mascota</a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($mascotas->isEmpty())
                        <div class="text-center">No tienes mascotas registradas.</div>
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr><th>Nombre</th><th>Raza</th><th>Tamaño</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @foreach($mascotas as $mascota)
                                <tr>
                                    <td>{{ $mascota->nombre_mascota }}</td>  <!-- ← CAMBIADO -->
                                    <td>{{ $mascota->raza ?? '-' }}</td>
                                    <td>{{ $mascota->tamanio }}</td>
                                    <td>
                                        <a href="{{ route('mascotas.edit', $mascota->id_mascota) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('mascotas.destroy', $mascota->id_mascota) }}" method="POST" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
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
    </div>
</div>
@endsection

<a href="{{ route('mascotas.recomendaciones', $mascota->id_mascota) }}" class="btn btn-info btn-sm">
    <i class="fas fa-robot"></i> 🤖 Recomendaciones IA
</a>