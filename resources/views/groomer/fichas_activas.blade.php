@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-warning">Fichas Activas</div>
        <div class="card-body">
            <a href="{{ route('groomer.dashboard') }}" class="btn btn-secondary mb-3">Volver</a>
            <p>Las fichas se ven en: <a href="{{ route('citas.index') }}">Mis Citas</a> → Botón "Ficha"</p>
        </div>
    </div>
</div>
@endsection