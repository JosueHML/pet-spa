@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-history"></i> Historial de Cierres de Caja</span>
            <a href="{{ route('admin.cierres.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Nuevo Cierre
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($cierres->isEmpty())
                <div class="text-center">No hay cierres de caja registrados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Cajero</th>
                                <th>Fecha</th>
                                <th>Efectivo</th>
                                <th>QR</th>
                                <th>Transferencia</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cierres as $cierre)
                            <tr>
                                <td>{{ $cierre->id_cierre }}</td>
                                <td>{{ $cierre->cajero->user->name }}</td>
                                <td>{{ $cierre->fecha_cierre->format('d/m/Y H:i') }}</td>
                                <td class="text-end">${{ number_format($cierre->total_efectivo, 2) }}</td>
                                <td class="text-end">${{ number_format($cierre->total_qr, 2) }}</td>
                                <td class="text-end">${{ number_format($cierre->total_transferencia, 2) }}</td>
                                <td class="text-end"><strong>${{ number_format($cierre->total_general, 2) }}</strong></td>
                                <td class="text-center">
                                    {{-- Botón Ver --}}
                                    <a href="{{ route('admin.cierres.show', $cierre->id_cierre) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    
                                    {{-- Botón Editar (solo Admin o si es el cierre del día actual) --}}
                                    @if(Auth::user()->id_rol == 1 || $cierre->fecha_cierre->toDateString() == now()->toDateString())
                                        <a href="{{ route('admin.cierres.edit', $cierre->id_cierre) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    @endif
                                    
                                    {{-- Botón Eliminar (solo Admin) --}}
                                    @if(Auth::user()->id_rol == 1)
                                        <form action="{{ route('admin.cierres.destroy', $cierre->id_cierre) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este cierre de caja? Esta acción no se puede deshacer.')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                 <td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end"><strong>TOTAL GENERAL:</strong> ontd
                                <td class="text-end"><strong>${{ number_format($cierres->sum('total_general'), 2) }}</strong> ontd
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{ $cierres->links() }}
            @endif
        </div>
    </div>
</div>
@endsection