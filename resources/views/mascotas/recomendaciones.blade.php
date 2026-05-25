@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
            <i class="fas fa-robot"></i> 🤖 Recomendaciones IA para {{ $mascota->nombre_mascota }}
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>🧠 Basado en el perfil de tu mascota:</strong>
                <ul class="mt-2">
                    <li>📏 Tamaño: {{ ucfirst($mascota->tamanio) }}</li>
                    <li>😊 Temperamento: {{ ucfirst($mascota->temperamento ?? 'Tranquilo') }}</li>
                    <li>🐕 Raza: {{ $mascota->raza ?? 'No especificada' }}</li>
                </ul>
            </div>

            @if(count($recomendaciones) > 0)
                <div class="row">
                    @foreach($recomendaciones as $producto)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 text-center shadow-sm">
                            <div class="card-body">
                                <i class="fas fa-paw fa-3x text-primary"></i>
                                <h5 class="mt-2">{{ $producto['nombre'] }}</h5>
                                <p class="text-success"><strong>${{ number_format($producto['precio'], 0) }}</strong></p>
                                <a href="#" class="btn btn-primary btn-sm">
                                    <i class="fas fa-shopping-cart"></i> Agregar
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-info-circle fa-3x text-muted"></i>
                    <p class="mt-2">No hay recomendaciones específicas para esta mascota.</p>
                </div>
            @endif

            <div class="mt-4 text-center">
                <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a mis mascotas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection