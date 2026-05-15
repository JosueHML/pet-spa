@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Crear Personal</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Contraseña *</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label>Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Rol *</label>
                            <select name="rol" id="rol" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="GROOMER">GROOMER (Peluquero)</option>
                                <option value="CAJERO">CAJERO</option>
                            </select>
                        </div>

                        <div id="groomerFields" style="display:none;">
                            <div class="mb-3">
                                <label>Especialidad</label>
                                <input type="text" name="especialidad" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Max Citas por Día</label>
                                <input type="number" name="max_citas_diarias" class="form-control" value="6">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger">Crear Personal</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('rol').addEventListener('change', function() {
        var groomerFields = document.getElementById('groomerFields');
        if (this.value === 'GROOMER') {
            groomerFields.style.display = 'block';
        } else {
            groomerFields.style.display = 'none';
        }
    });
</script>
@endsection