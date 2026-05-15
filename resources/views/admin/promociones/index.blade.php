@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>🎁 Gestión de Promociones</span>
            <a href="{{ route('admin.promociones.create') }}" class="btn btn-light btn-sm">+ Nueva Promoción</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($promociones->isEmpty())
                <div class="text-center">No hay promociones registradas.</div>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Descuento</th>
                            <th>Vigencia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($promociones as $promo)
                        <tr>
                            <td>{{ $promo->id_promocion }}</td>
                            <td>{{ $promo->nombre }}</td>
                            <td>
                                @if($promo->tipo == 'PORCENTAJE') % Descuento
                                @elseif($promo->tipo == 'MONTO_FIJO') Monto fijo
                                @else Compra mínima
                                @endif
                            </td>
                            <td>
                                @if($promo->tipo == 'PORCENTAJE')
                                    {{ $promo->valor_descuento }}%
                                @else
                                    ${{ number_format($promo->valor_descuento, 2) }}
                                @endif
                            </td>
                            <td>{{ $promo->fecha_inicio->format('d/m/Y') }} - {{ $promo->fecha_fin->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $promo->activo ? 'success' : 'danger' }}">
                                    {{ $promo->activo ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.promociones.edit', $promo->id_promocion) }}" class="btn btn-warning btn-sm">Editar</a>
                                <a href="{{ route('admin.promociones.toggle', $promo->id_promocion) }}" class="btn btn-sm btn-{{ $promo->activo ? 'secondary' : 'success' }}">
                                    {{ $promo->activo ? 'Desactivar' : 'Activar' }}
                                </a>
                                <form action="{{ route('admin.promociones.destroy', $promo->id_promocion) }}" method="POST" style="display:inline-block;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta promoción?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $promociones->links() }}
            @endif
        </div>
    </div>
</div>
@endsection