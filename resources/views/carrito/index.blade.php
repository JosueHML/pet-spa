@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            🛒 Mi Carrito de Compras
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($items->isEmpty())
                <div class="text-center">
                    <p>Tu carrito está vacío</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-primary">Ver productos</a>
                </div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->producto->nombre_producto }}</td>
                            <td>${{ number_format($item->precio_unitario, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.update', $item->id_item) }}" method="POST" style="display:inline-flex; gap:5px;">
                                    @csrf @method('PUT')
                                    <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" style="width:70px;">
                                    <button type="submit" class="btn btn-sm btn-warning">Actualizar</button>
                                </form>
                            </td>
                            <td>${{ number_format($item->cantidad * $item->precio_unitario, 2) }}</td>
                            <td>
                                <form action="{{ route('carrito.remove', $item->id_item) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">❌</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong>${{ number_format($total, 2) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                    <form action="{{ route('carrito.vaciar') }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">🗑️ Vaciar carrito</button>
                    </form>
                    
                    <a href="{{ route('carrito.whatsapp') }}" class="btn btn-success" target="_blank">
                        📱 Pedir por WhatsApp
                    </a>
                    
                    <form action="{{ route('carrito.solicitar-factura') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">📄 Solicitar Factura</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection