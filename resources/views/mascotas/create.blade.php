@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Registrar Mascota</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('mascotas.store') }}">
                        @csrf
                        <div class="mb-3"><label>Nombre *</label><input type="text" name="nombre" class="form-control" required></div>
                        <div class="mb-3"><label>Raza</label><input type="text" name="raza" class="form-control"></div>
                        <div class="mb-3">
                            <label>Tamaño *</label>
                            <select name="tamanio" class="form-control" required>
                                <option value="PEQUEÑO">Pequeño</option>
                                <option value="MEDIANO">Mediano</option>
                                <option value="GRANDE">Grande</option>
                                <option value="EXTRA_GRANDE">Extra Grande</option>
                            </select>
                        </div>
                        <div class="mb-3"><label>Edad (meses)</label><input type="number" name="edad_meses" class="form-control"></div>
                        <div class="mb-3"><label>Alergias</label><textarea name="alergias" class="form-control" rows="2"></textarea></div>
                        <div class="mb-3"><label>Vacunas</label><textarea name="vacunas" class="form-control" rows="2"></textarea></div>
                        <div class="mb-3"><label>Restricciones</label><textarea name="restricciones" class="form-control" rows="2"></textarea></div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection