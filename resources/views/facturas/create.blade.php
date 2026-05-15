@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">Generar Factura</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('facturas.store') }}">
                        @csrf
                        <input type="hidden" name="id_cita" value="{{ $cita->id_cita }}">
                        <input type="hidden" name="id_cliente" value="{{ $cliente->id_cliente }}">
                        <input type="hidden" name="numero_factura" value="{{ $numeroFactura }}">

                        <div class="mb-3">
                            <label>N° Factura</label>
                            <input type="text" class="form-control" value="{{ $numeroFactura }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Cliente</label>
                            <input type="text" class="form-control" value="{{ $cliente->user->name ?? 'N/A' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Mascota</label>
                            <input type="text" class="form-control" value="{{ $cita->mascota->nombre_mascota }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Servicio</label>
                            <input type="text" class="form-control" value="{{ $cita->servicio->nombre_servicio }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Monto Total</label>
                            <input type="number" name="monto_total" class="form-control" value="{{ $monto }}" step="0.01" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Método de Pago *</label>
                            <select name="metodo_pago" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="QR">QR</option>
                                <option value="TRANSFERENCIA">Transferencia</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Generar Factura</button>
                        <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection