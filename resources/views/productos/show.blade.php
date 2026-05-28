@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white">
            <i class="fas fa-box-open"></i> Detalle del Producto
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre:</strong> {{ $producto->nombre_producto }}</p>
                    <p><strong>SKU:</strong> {{ $producto->sku }}</p>
                    <p><strong>Categoría:</strong> {{ $producto->categoria ?? 'Sin categoría' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Stock Actual:</strong> {{ $producto->stock_actual }} unidades</p>
                    <p><strong>Stock Mínimo:</strong> {{ $producto->stock_minimo }} unidades</p>
                    <p><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
                <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning">Editar</a>
            </div>
        </div>
    </div>
</div>
@endsection