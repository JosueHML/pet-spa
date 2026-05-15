@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    Factura N° {{ $factura->numero_factura }}
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Fecha de emisión:</strong> {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            @if($factura->estado_pago == 'PAGADO')
                                <span class="badge bg-success">PAGADO</span>
                            @else
                                <span class="badge bg-warning">{{ $factura->estado_pago }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cliente:</strong> {{ $factura->cliente->user->name ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Cajero:</strong> {{ $factura->cajero->user->name ?? 'N/A' }}
                        </div>
                    </div>

                    @if($factura->cita)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mascota:</strong> {{ $factura->cita->mascota->nombre_mascota ?? 'N/A' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Servicio:</strong> {{ $factura->cita->servicio->nombre_servicio ?? 'N/A' }}
                        </div>
                    </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h3>Total: ${{ number_format($factura->total, 2) }}</h3>
                            <p><strong>Método de pago:</strong> {{ $factura->metodo_pago ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center">
                        <a href="{{ route('facturas.index') }}" class="btn btn-secondary">Volver</a>
                        <button onclick="window.print()" class="btn btn-primary">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection