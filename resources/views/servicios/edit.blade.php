@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Editar Servicio</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('servicios.update', $servicio->id_servicio) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Nombre del servicio *</label>
                            <input type="text" name="nombre_servicio" class="form-control @error('nombre_servicio') is-invalid @enderror" value="{{ old('nombre_servicio', $servicio->nombre_servicio) }}" required>
                            @error('nombre_servicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Precio *</label>
                            <input type="number" name="precio" step="0.01" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio', $servicio->precio) }}" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Duración (minutos) *</label>
                            <input type="number" name="duracion_minutos" class="form-control @error('duracion_minutos') is-invalid @enderror" value="{{ old('duracion_minutos', $servicio->duracion_minutos) }}" required>
                            @error('duracion_minutos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-warning">Actualizar Servicio</button>
                            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection