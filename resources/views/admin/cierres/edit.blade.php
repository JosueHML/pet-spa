@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-edit"></i> Editar Cierre de Caja #{{ $cierre->id_cierre }}
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>📅 Fecha del cierre:</strong> {{ $cierre->fecha_cierre->format('d/m/Y H:i') }}
                        <br>
                        <small class="text-muted">Solo puedes editar el cierre del día actual o ser Administrador.</small>
                    </div>

                    <form method="POST" action="{{ route('admin.cierres.update', $cierre->id_cierre) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>💰 Total Efectivo</label>
                                    <input type="number" name="total_efectivo" step="0.01" class="form-control" value="{{ $cierre->total_efectivo }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>📱 Total QR</label>
                                    <input type="number" name="total_qr" step="0.01" class="form-control" value="{{ $cierre->total_qr }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>🏦 Total Transferencia</label>
                                    <input type="number" name="total_transferencia" step="0.01" class="form-control" value="{{ $cierre->total_transferencia }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>💵 Total General</label>
                                    <input type="number" name="total_general" step="0.01" class="form-control" value="{{ $cierre->total_general }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3">{{ $cierre->observaciones }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning">Actualizar Cierre</button>
                        <a href="{{ route('admin.cierres.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection