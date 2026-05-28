@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white">
            <i class="fas fa-file-invoice"></i> Detalle de Compra #{{ $compra->id_compra }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Proveedor:</strong> {{ $compra->proveedor->nombre }}</p>
                    <p><strong>N° Factura:</strong> {{ $compra->numero_factura ?? 'N/A' }}</p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        <span class="badge bg-{{ $compra->estado == 'PENDIENTE' ? 'warning' : 'success' }}">
                            {{ $compra->estado }}
                        </span>
                    </p>
                    <p><strong>Registrado:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <hr>
            <h5>Detalle de Productos</h5>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo</th>
                        <th>Producto/Insumo</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compra->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->tipo_producto }}</span></td>
                        <td>
                            @if($detalle->tipo_producto == 'PRODUCTO')
                                {{ $detalle->producto->nombre ?? 'N/A' }}
                            @else
                                {{ $detalle->insumo->nombre ?? 'N/A' }}
                            @endif
                        </span></td>
                        <td>{{ $detalle->cantidad }}</span></td>
                        <td>${{ number_format($detalle->precio_unitario, 2) }}</span></td>
                        <td>${{ number_format($detalle->subtotal, 2) }}</span></span></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                        <td>${{ number_format($compra->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>IVA (13%):</strong></td>
                        <td>${{ number_format($compra->impuesto, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                        <td><strong>${{ number_format($compra->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>

            @if($compra->observaciones)
                <div class="alert alert-secondary">
                    <strong>Observaciones:</strong> {{ $compra->observaciones }}
                </div>
            @endif

            <div class="mt-3">
                <a href="{{ route('admin.compras.index') }}" class="btn btn-secondary">Volver</a>
                @if($compra->estado == 'PENDIENTE')
                    <form action="{{ route('admin.compras.recibir', $compra->id_compra) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('¿Marcar como recibido? El stock se actualizará automáticamente.')">
                            <i class="fas fa-check"></i> Marcar como Recibido
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection