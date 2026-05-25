@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <i class="fas fa-key"></i> Cambiar Contraseña
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cambiar.password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo</small>
                        </div>

                        <div class="mb-3">
                            <label>Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Cambiar Contraseña
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection