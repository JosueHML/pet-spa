@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Nueva Promoción</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.promociones.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nombre de la promoción *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Tipo de descuento *</label>
                                    <select name="tipo" class="form-control" required>
                                        <option value="PORCENTAJE">Porcentaje (%)</option>
                                        <option value="MONTO_FIJO">Monto fijo ($)</option>
                                        <option value="COMPRA_XXX">Compra mínima</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Valor del descuento *</label>
                                    <input type="number" name="valor_descuento" step="0.01" min="0.01" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Compra mínima (opcional)</label>
                            <input type="number" name="compra_minima" step="0.01" min="0" class="form-control" value="0">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Fecha de inicio *</label>
                                    <input type="date" name="fecha_inicio" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Fecha de fin *</label>
                                    <input type="date" name="fecha_fin" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Aplica a *</label>
                            <select name="aplica_a" class="form-control" required>
                                <option value="PRODUCTOS">Solo Productos</option>
                                <option value="SERVICIOS">Solo Servicios</option>
                                <option value="AMBOS">Productos y Servicios</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-danger">Guardar Promoción</button>
                        <a href="{{ route('admin.promociones.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection