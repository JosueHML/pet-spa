@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Nuevo Producto</div>
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

                    <form method="POST" action="{{ url('/admin/productos/guardar') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label>Nombre *</label>
                            <input type="text" name="nombre_producto" class="form-control" value="{{ old('nombre_producto') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>SKU *</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Categoría</label>
                            <input type="text" name="categoria" class="form-control" value="{{ old('categoria') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label>Stock *</label>
                            <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Precio *</label>
                            <input type="number" name="precio" step="0.01" class="form-control" value="{{ old('precio') }}" required>
                        </div>
                        
                        <button type="submit" class="btn btn-danger">Guardar Producto</button>
                        <a href="{{ url('/productos') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection