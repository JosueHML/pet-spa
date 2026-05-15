@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Editar Cita</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('citas.update', $cita->id_cita) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Mascota *</label>
                            <select name="id_mascota" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id_mascota }}" {{ $cita->id_mascota == $mascota->id_mascota ? 'selected' : '' }}>
                                        {{ $mascota->nombre_mascota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Servicio *</label>
                            <select name="id_servicio" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id_servicio }}" {{ $cita->id_servicio == $servicio->id_servicio ? 'selected' : '' }}>
                                        {{ $servicio->nombre_servicio }} - ${{ number_format($servicio->precio, 2) }} ({{ $servicio->duracion_minutos }} min)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Groomer *</label>
                            <select name="id_groomer" class="form-control" required>
                                <option value="">Seleccione</option>
                                @foreach($groomers as $groomer)
                                    <option value="{{ $groomer->id_groomer }}" {{ $cita->id_groomer == $groomer->id_groomer ? 'selected' : '' }}>
                                        {{ $groomer->user->name ?? 'Groomer' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Fecha y Hora *</label>
                            <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($cita->fecha_inicio)) }}" required>
                        </div>

                        <button type="submit" class="btn btn-warning">Actualizar Cita</button>
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection