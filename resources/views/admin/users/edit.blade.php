@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Editar Personal</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Nueva Contraseña (dejar vacío para no cambiar)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        @if($user->groomer)
                        <div class="mb-3">
                            <label>Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="{{ $user->groomer->especialidad }}">
                        </div>
                        <div class="mb-3">
                            <label>Max Citas por Día</label>
                            <input type="number" name="max_citas_diarias" class="form-control" value="{{ $user->groomer->max_citas_diarias }}">
                        </div>
                        <div class="mb-3">
                            <label>Activo</label>
                            <select name="activo" class="form-control">
                                <option value="1" {{ $user->groomer->activo == 1 ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ $user->groomer->activo == 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        @endif

                        <button type="submit" class="btn btn-warning">Actualizar</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection