@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Cierre de Caja Diario</div>
                <div class="card-body">
                    @if($cierreExistente)
                        <div class="alert alert-warning">
                            ⚠️ Ya existe un cierre de caja para hoy ({{ now()->format('d/m/Y') }}).
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <strong>📅 Fecha:</strong> {{ now()->format('d/m/Y') }}
                    </div>

                    <form method="POST" action="{{ route('admin.cierres.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>💰 Total Efectivo</label>
                                    <input type="number" name="total_efectivo" step="0.01" class="form-control" value="{{ $totalEfectivo }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>📱 Total QR</label>
                                    <input type="number" name="total_qr" step="0.01" class="form-control" value="{{ $totalQR }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>🏦 Total Transferencia</label>
                                    <input type="number" name="total_transferencia" step="0.01" class="form-control" value="{{ $totalTransferencia }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>💵 Total General</label>
                                    <input type="number" name="total_general" step="0.01" class="form-control" value="{{ $totalGeneral }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3"></textarea>
                        </div>

                        @if(!$cierreExistente)
                            <button type="submit" class="btn btn-danger">Realizar Cierre de Caja</button>
                        @endif
                        <a href="{{ route('admin.cierres.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection