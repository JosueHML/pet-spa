@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <i class="fas fa-plus"></i> Nuevo Insumo
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('insumos.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Nombre del Insumo *</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Stock Actual *</label>
                            <input type="number" name="stock_actual" class="form-control" value="0" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Stock Mínimo *</label>
                            <input type="number" name="stock_minimo" class="form-control" value="5" required>
                            <small class="text-muted">Cuando el stock baje a este nivel, se mostrará alerta</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Precio Unitario</label>
                            <input type="number" step="0.01" name="precio" class="form-control" placeholder="Opcional">
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Insumo
                    </button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection