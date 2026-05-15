@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">Panel de Cajero</div>
                <div class="card-body">
                    <h3>¡Bienvenido Cajero, {{ Auth::user()->name }}!</h3>
                    <p>Aquí puedes gestionar:</p>
                    <ul>
                        <li><a href="{{ route('facturas.index') }}">💰 Facturación</a></li>
                        <li><a href="{{ route('citas.index') }}">📅 Ver Citas</a></li>
                        <li><a href="{{ route('productos.index') }}">📦 Productos</a></li>
                        <li><a href="{{ route('admin.solicitudes.index') }}">📋 Solicitudes de Factura</a></li>
                        <li><a href="{{ route('admin.cierres.index') }}">💰 Cierre de Caja</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection