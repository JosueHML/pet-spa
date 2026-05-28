@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-white">Editar Producto</div>
                <div class="card-body">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="nombre_producto" class="form-control" value="{{ old('nombre_producto', $producto->nombre_producto) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>SKU *</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $producto->sku) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Categoría</label>
                            <input type="text" name="categoria" class="form-control" value="{{ old('categoria', $producto->categoria) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label>Stock *</label>
                            <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Precio *</label>
                            <input type="number" name="precio" step="0.01" class="form-control" value="{{ old('precio', $producto->precio) }}" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">Actualizar Producto</button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection