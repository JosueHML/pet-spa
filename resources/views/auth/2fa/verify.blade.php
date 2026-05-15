@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Verificación 2FA</div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <p>Ingresa el código de 6 dígitos de Google Authenticator:</p>
                    <form method="POST" action="{{ route('2fa.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="code" class="form-control" placeholder="000000" maxlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Verificar</button>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection