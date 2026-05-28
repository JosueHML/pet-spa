@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-white">
            <i class="fas fa-edit"></i> Editar Insumo: {{ $insumo->nombre }}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('insumos.update', $insumo->id_insumo) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Nombre del Insumo *</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $insumo->nombre }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Stock Actual *</label>
                            <input type="number" name="stock_actual" class="form-control" value="{{ $insumo->stock_actual }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Stock Mínimo *</label>
                            <input type="number" name="stock_minimo" class="form-control" value="{{ $insumo->stock_minimo }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Precio Unitario</label>
                            <input type="number" step="0.01" name="precio" class="form-control" value="{{ $insumo->precio }}">
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar Insumo
                    </button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection