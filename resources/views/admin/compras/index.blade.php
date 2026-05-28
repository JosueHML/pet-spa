@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-truck"></i> Compras a Proveedores
            </div>
            <a href="{{ route('admin.compras.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Nueva Compra
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($compras->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-truck fa-4x text-muted mb-3"></i>
                    <p>No hay compras registradas.</p>
                    <a href="{{ route('admin.compras.create') }}" class="btn btn-primary">Registrar primera compra</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Proveedor</th>
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                            <tr>
                                <td>{{ $compra->id_compra }}</td>
                                <td>{{ $compra->proveedor->nombre }}</td>
                                <td>{{ $compra->numero_factura ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
                                <td>${{ number_format($compra->total, 2) }}</span></td>
                                <td>
                                    @if($compra->estado == 'PENDIENTE')
                                        <span class="badge bg-warning">PENDIENTE</span>
                                    @elseif($compra->estado == 'RECIBIDO')
                                        <span class="badge bg-success">RECIBIDO</span>
                                    @else
                                        <span class="badge bg-danger">CANCELADO</span>
                                    @endif
                                </span>
                                <td>
                                    <a href="{{ route('admin.compras.show', $compra->id_compra) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    @if($compra->estado == 'PENDIENTE')
                                        <form action="{{ route('admin.compras.recibir', $compra->id_compra) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('¿Marcar como recibido? El stock se actualizará automáticamente.')">
                                                <i class="fas fa-check"></i> Recibir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $compras->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection