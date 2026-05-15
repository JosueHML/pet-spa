@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Nuevo Producto</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('productos.store') }}">
                        @csrf
                        <div class="mb-3"><label>Nombre *</label><input type="text" name="nombre_producto" class="form-control" required></div>
                        <div class="mb-3"><label>SKU *</label><input type="text" name="sku" class="form-control" required></div>
                        <div class="mb-3"><label>Categoría</label><input type="text" name="categoria" class="form-control"></div>
                        <div class="mb-3"><label>Stock *</label><input type="number" name="stock_actual" class="form-control" required></div>
                        <div class="mb-3"><label>Precio *</label><input type="number" name="precio" step="0.01" class="form-control" required></div>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection