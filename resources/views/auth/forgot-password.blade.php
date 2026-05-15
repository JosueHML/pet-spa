@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Restablecer Contraseña</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="http://localhost:8000/forgot-password">
                        @csrf
                        <div class="mb-3">
                            <label>Correo electrónico</label>
                            <input type="email" name="email" class="form-control" value="admin@petspa.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar enlace</button>
                        <a href="{{ route('login') }}" class="btn btn-link">Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection