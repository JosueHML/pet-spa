@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Nuevo Servicio</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('servicios.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nombre del servicio *</label>
                            <input type="text" name="nombre_servicio" class="form-control @error('nombre_servicio') is-invalid @enderror" required>
                            @error('nombre_servicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3"></textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Precio *</label>
                            <input type="number" name="precio" step="0.01" class="form-control @error('precio') is-invalid @enderror" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Duración (minutos) *</label>
                            <input type="number" name="duracion_minutos" class="form-control @error('duracion_minutos') is-invalid @enderror" required>
                            @error('duracion_minutos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-danger">Guardar Servicio</button>
                            <a href="{{ route('servicios.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection