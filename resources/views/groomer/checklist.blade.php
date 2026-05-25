@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <i class="fas fa-check-circle"></i> ✅ Checklist Completados
        </div>
        <div class="card-body">
            @php
                use App\Models\FichaGrooming;
                $checklists = FichaGrooming::with(['cita.mascota', 'cita.servicio'])
                    ->whereNotNull('checklist_json')
                    ->orderBy('updated_at', 'desc')
                    ->limit(30)
                    ->get();
            @endphp

            @if($checklists->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4>No hay checklist registrados</h4>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr><th>Mascota</th><th>Servicio</th><th>Checklist</th><th>Fecha</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                            @foreach($checklists as $ficha)
                            <tr>
                                <td>{{ $ficha->cita->mascota->nombre_mascota ?? 'N/A' }}</td>
                                <td>{{ $ficha->cita->servicio->nombre_servicio ?? 'N/A' }}</td>
                                <td>
                                    @php $items = is_array($ficha->checklist_json) ? $ficha->checklist_json : json_decode($ficha->checklist_json, true); @endphp
                                    @if($items)
                                        @foreach($items as $key => $value)
                                            @if($value)
                                                <span class="badge bg-success m-1">{{ ucfirst($key) }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $ficha->updated_at->format('d/m/Y H:i') }}</td>
                                <td><a href="{{ route('grooming.show', $ficha->cita->id_cita) }}" class="btn btn-info btn-sm">Ver</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <a href="{{ route('groomer.dashboard') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection