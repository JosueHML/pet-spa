@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-success">Checklist</div>
        <div class="card-body">
            <a href="{{ route('groomer.dashboard') }}" class="btn btn-secondary mb-3">Volver</a>
            <p>Revisa los checklist en: <a href="{{ route('citas.index') }}">Mis Citas</a></p>
        </div>
    </div>
</div>
@endsection