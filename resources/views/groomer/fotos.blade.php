@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-camera"></i> 📸 Galería de Fotos
        </div>
        <div class="card-body">
            @php
                use App\Models\FichaGrooming;
                $fotos = FichaGrooming::with(['cita.mascota'])
                    ->whereNotNull('foto_antes')
                    ->orWhereNotNull('foto_despues')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($fotos->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-image fa-4x text-muted mb-3"></i>
                    <h4>No hay fotos registradas</h4>
                </div>
            @else
                <div class="row">
                    @foreach($fotos as $ficha)
                        @if($ficha->foto_antes)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-secondary text-white">ANTES - {{ $ficha->cita->mascota->nombre_mascota ?? 'Mascota' }}</div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $ficha->foto_antes) }}" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('grooming.show', $ficha->cita->id_cita) }}" class="btn btn-info btn-sm w-100">Ver ficha</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($ficha->foto_despues)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">DESPUÉS - {{ $ficha->cita->mascota->nombre_mascota ?? 'Mascota' }}</div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $ficha->foto_despues) }}" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('grooming.show', $ficha->cita->id_cita) }}" class="btn btn-info btn-sm w-100">Ver ficha</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
            <a href="{{ route('groomer.dashboard') }}" class="btn btn-secondary mt-3">Volver</a>
        </div>
    </div>
</div>
@endsection