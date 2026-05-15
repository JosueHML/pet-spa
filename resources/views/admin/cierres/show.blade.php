@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-info text-white">
            Detalle del Cierre #{{ $cierre->id_cierre }}
        </div>
        <div class="card-body">
            <p><strong>Cajero:</strong> {{ $cierre->cajero->user->name }}</p>
            <p><strong>Fecha:</strong> {{ $cierre->fecha_cierre->format('d/m/Y H:i') }}</p>
            
            <hr>
            <h5>Resumen de ventas:</h5>
            <table class="table table-bordered">
                <tr><th>Efectivo</th><td>${{ number_format($cierre->total_efectivo, 2) }}</td></tr>
                <tr><th>QR</th><td>${{ number_format($cierre->total_qr, 2) }}</td></tr>
                <tr><th>Transferencia</th><td>${{ number_format($cierre->total_transferencia, 2) }}</td></tr>
                <tr class="table-active"><th>TOTAL</th><td><strong>${{ number_format($cierre->total_general, 2) }}</strong></td></tr>
            </table>

            @if($cierre->observaciones)
                <p><strong>Observaciones:</strong> {{ $cierre->observaciones }}</p>
            @endif

            <a href="{{ route('admin.cierres.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection