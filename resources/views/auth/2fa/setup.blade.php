@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">Configurar 2FA</div>
                <div class="card-body text-center">
                    <p>Escanea el siguiente código QR con Google Authenticator:</p>
                    {!! QrCode::size(200)->generate($qrCode) !!}
                    <p class="mt-3"><strong>Código secreto:</strong> <code>{{ Auth::user()->google2fa_secret }}</code></p>
                    <hr>
                    <form method="POST" action="{{ route('2fa.enable') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Ingresa el código de 6 dígitos</label>
                            <input type="text" name="code" class="form-control" placeholder="000000" maxlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Activar 2FA</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection