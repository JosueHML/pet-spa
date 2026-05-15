@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            Detalle de Solicitud #{{ $solicitud->id_solicitud }}
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $solicitud->cliente->user->name }}</p>
            <p><strong>Email:</strong> {{ $solicitud->cliente->user->email }}</p>
            <p><strong>Fecha:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total:</strong> ${{ number_format($solicitud->total, 2) }}</p>

            <h5>Productos:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach($solicitud->carrito_data as $item)
                    <tr>
                        <td>{{ $item['producto'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>${{ number_format($item['precio_unitario'], 2) }}</td>
                        <td>${{ number_format($item['subtotal'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex gap-2 mt-3">
                <form action="{{ route('admin.solicitudes.aprobar', $solicitud->id_solicitud) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">✅ Aprobar Factura</button>
                </form>
                <form action="{{ route('admin.solicitudes.rechazar', $solicitud->id_solicitud) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Rechazar esta solicitud?')">❌ Rechazar</button>
                </form>
                <a href="{{ route('admin.solicitudes.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection