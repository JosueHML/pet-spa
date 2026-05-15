@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>¡Bienvenido, {{ Auth::user()->name }}!</h3>
                    <p>Has iniciado sesión correctamente.</p>
                    <p>Tu cuenta está: <strong>{{ Auth::user()->account_status }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection