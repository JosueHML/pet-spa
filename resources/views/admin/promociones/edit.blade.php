@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">Editar Promoción</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.promociones.update', $promocion->id_promocion) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Nombre de la promoción *</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $promocion->nombre }}" required>
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2">{{ $promocion->descripcion }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Tipo de descuento *</label>
                                    <select name="tipo" class="form-control" required>
                                        <option value="PORCENTAJE" {{ $promocion->tipo == 'PORCENTAJE' ? 'selected' : '' }}>Porcentaje (%)</option>
                                        <option value="MONTO_FIJO" {{ $promocion->tipo == 'MONTO_FIJO' ? 'selected' : '' }}>Monto fijo ($)</option>
                                        <option value="COMPRA_XXX" {{ $promocion->tipo == 'COMPRA_XXX' ? 'selected' : '' }}>Compra mínima</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Valor del descuento *</label>
                                    <input type="number" name="valor_descuento" step="0.01" class="form-control" value="{{ $promocion->valor_descuento }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Compra mínima (opcional)</label>
                            <input type="number" name="compra_minima" step="0.01" class="form-control" value="{{ $promocion->compra_minima }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Fecha de inicio *</label>
                                    <input type="date" name="fecha_inicio" class="form-control" value="{{ $promocion->fecha_inicio }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Fecha de fin *</label>
                                    <input type="date" name="fecha_fin" class="form-control" value="{{ $promocion->fecha_fin }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Aplica a *</label>
                            <select name="aplica_a" class="form-control" required>
                                <option value="PRODUCTOS" {{ $promocion->aplica_a == 'PRODUCTOS' ? 'selected' : '' }}>Solo Productos</option>
                                <option value="SERVICIOS" {{ $promocion->aplica_a == 'SERVICIOS' ? 'selected' : '' }}>Solo Servicios</option>
                                <option value="AMBOS" {{ $promocion->aplica_a == 'AMBOS' ? 'selected' : '' }}>Productos y Servicios</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning">Actualizar Promoción</button>
                        <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection