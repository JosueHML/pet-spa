@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Nueva Cita</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('citas.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Mascota *</label>
                            <select name="id_mascota" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id_mascota }}">{{ $mascota->nombre_mascota }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Servicio *</label>
                            <select name="id_servicio" id="servicio" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id_servicio }}" data-duracion="{{ $servicio->duracion_minutos }}">{{ $servicio->nombre_servicio }} - ${{ number_format($servicio->precio, 2) }} ({{ $servicio->duracion_minutos }} min)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Groomer *</label>
                            <select name="id_groomer" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($groomers as $groomer)
                                    <option value="{{ $groomer->id_groomer }}">{{ $groomer->user->name ?? 'Groomer' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Fecha y Hora *</label>
                            <input type="datetime-local" name="fecha_inicio" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Agendar Cita</button>
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection