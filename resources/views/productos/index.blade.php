@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>🏪 Tienda de Productos</span>
            <div>
                <a href="{{ route('carrito.index') }}" class="btn btn-light btn-sm me-2">
                    🛒 Ver Carrito
                </a>
                @auth
                    @if(Auth::user()->id_rol == 1)
                        <a href="{{ url('/admin/productos/crear') }}" class="btn btn-light btn-sm">
                            + Nuevo Producto
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                @foreach($productos as $producto)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $producto->nombre_producto }}</h5>
                                <p class="card-text mb-1"><strong>SKU:</strong> {{ $producto->sku }}</p>
                                <p class="card-text mb-1"><strong>Categoría:</strong> {{ $producto->categoria ?? '-' }}</p>
                                <p class="card-text mb-1"><strong>Stock:</strong> 
                                    @if($producto->stock_actual <= $producto->stock_minimo)
                                        <span class="badge bg-danger">¡Stock bajo! ({{ $producto->stock_actual }})</span>
                                    @else
                                        {{ $producto->stock_actual }}
                                    @endif
                                </p>
                                <h4 class="text-success mt-2">${{ number_format($producto->precio, 2) }}</h4>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex gap-2 flex-wrap">
                                    <!-- Comprar / Agregar al carrito -->
                                    <form action="{{ route('carrito.add') }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">
                                        <input type="number" name="cantidad" value="1" min="1" class="form-control" style="width:70px;">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            🛒 Comprar
                                        </button>
                                    </form>

                                    <!-- Botones de ADMIN (solo visibles para administrador) -->
                                    @auth
                                        @if(Auth::user()->id_rol == 1)
                                            <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning btn-sm">
                                                ✏️ Editar
                                            </a>
                                            <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Botón para ir al carrito (flotante) -->
            <div class="position-fixed bottom-0 end-0 m-4">
                <a href="{{ route('carrito.index') }}" class="btn btn-success btn-lg rounded-circle shadow">
                    🛒 <span class="badge bg-danger">{{ \App\Models\Carrito::where('id_cliente', Auth::user()->cliente->id_cliente ?? 0)->first()?->items()->count() ?? 0 }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection