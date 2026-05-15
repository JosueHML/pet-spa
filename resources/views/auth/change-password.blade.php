@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">Cambiar Contraseña</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="alert alert-info">
                        <strong>⚠️ Es obligatorio cambiar tu contraseña para continuar.</strong>
                    </div>
                    
                    <form method="POST" action="{{ route('password.change.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nueva Contraseña *</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            <small class="text-muted">Mínimo 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cambiar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection