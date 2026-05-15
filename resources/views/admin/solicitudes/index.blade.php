@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            📋 Solicitudes de Factura Pendientes
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($solicitudes->isEmpty())
                <div class="text-center">No hay solicitudes pendientes.</div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->id_solicitud }}</td>
                            <td>{{ $solicitud->cliente->user->name }}</td>
                            <td>${{ number_format($solicitud->total, 2) }}</td>
                            <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.solicitudes.show', $solicitud->id_solicitud) }}" class="btn btn-info btn-sm">Ver Detalle</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection