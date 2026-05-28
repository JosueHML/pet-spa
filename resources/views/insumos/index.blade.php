@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-flask"></i> Gestión de Insumos
            </div>
            <a href="{{ route('insumos.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Nuevo Insumo
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($insumos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-flask fa-4x text-muted mb-3"></i>
                    <p>No hay insumos registrados.</p>
                    <a href="{{ route('insumos.create') }}" class="btn btn-info">Crear primer insumo</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($insumos as $insumo)
                            <tr>
                                <td>{{ $insumo->id_insumo }}</span></td>
                                <td><strong>{{ $insumo->nombre }}</strong></span></td>
                                <td class="text-end">{{ $insumo->stock_actual }} unidades</span></span></td>
                                <td class="text-end">{{ $insumo->stock_minimo }} unidades</span></span></td>
                                <td class="text-end">${{ number_format($insumo->precio ?? 0, 2) }}</span></span></td>
                                <td>
                                    @if($insumo->stock_actual <= $insumo->stock_minimo)
                                        <span class="badge bg-danger">⚠️ Stock bajo</span>
                                    @else
                                        <span class="badge bg-success">Stock normal</span>
                                    @endif
                                </span>
                                <td class="text-center">
                                    <a href="{{ route('insumos.edit', $insumo->id_insumo) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este insumo?')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $insumos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection