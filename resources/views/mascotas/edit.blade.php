@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Editar Mascota</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('mascotas.update', $mascota->id_mascota) }}">
                        @csrf @method('PUT')
                        
                        <div class="mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $mascota->nombre_mascota }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Raza</label>
                            <input type="text" name="raza" class="form-control" value="{{ $mascota->raza }}">
                        </div>
                        
                        <div class="mb-3">
                            <label>Tamaño *</label>
                            <select name="tamanio" class="form-control" required>
                                <option value="PEQUEÑO" {{ $mascota->tamanio == 'PEQUEÑO' ? 'selected' : '' }}>Pequeño</option>
                                <option value="MEDIANO" {{ $mascota->tamanio == 'MEDIANO' ? 'selected' : '' }}>Mediano</option>
                                <option value="GRANDE" {{ $mascota->tamanio == 'GRANDE' ? 'selected' : '' }}>Grande</option>
                                <option value="EXTRA_GRANDE" {{ $mascota->tamanio == 'EXTRA_GRANDE' ? 'selected' : '' }}>Extra Grande</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Edad (meses)</label>
                            <input type="number" name="edad_meses" class="form-control" value="{{ $mascota->edad_meses }}">
                        </div>
                        
                        <div class="mb-3">
                            <label>Alergias</label>
                            <textarea name="alergias" class="form-control" rows="2">{{ $mascota->alergias }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>Vacunas</label>
                            <textarea name="vacunas" class="form-control" rows="2">{{ $mascota->vacunas }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>Restricciones</label>
                            <textarea name="restricciones" class="form-control" rows="2">{{ $mascota->restricciones }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection