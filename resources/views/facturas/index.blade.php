@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success text-white">
            Mis Facturas
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N° Factura</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Método Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                    <tr>
                        <td>{{ $factura->numero_factura }}</td>
                        <td>{{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}</td>
                        <td>${{ number_format($factura->total, 2) }}</td>
                        <td>{{ $factura->metodo_pago ?? '-' }}</td>
                        <td>
                            @if($factura->estado_pago == 'PAGADO')
                                <span class="badge bg-success">PAGADO</span>
                            @else
                                <span class="badge bg-warning">{{ $factura->estado_pago }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('facturas.show', $factura->id_factura) }}" class="btn btn-sm btn-info">Ver</a>
                        </td>
                    </tr>
                    @empty
                        <td><td colspan="6" class="text-center">No hay facturas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $facturas->links() }}
        </div>
    </div>
</div>
@endsection