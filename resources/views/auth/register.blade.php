@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                <div class="progress mt-2" style="height: 5px;">
                                    <div id="password-strength" class="progress-bar" style="width: 0%;"></div>
                                </div>
                                <small id="password-text" class="text-muted">Ingresa una contraseña segura</small>
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('password').addEventListener('input', function() {
        var password = this.value;
        var strength = 0;
        var bar = document.getElementById('password-strength');
        var text = document.getElementById('password-text');
        
        if (password.length >= 8) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 25;
        if (password.match(/[@$!%*?&]/)) strength += 25;
        
        bar.style.width = Math.min(strength, 100) + '%';
        
        if (strength < 25) {
            bar.className = 'progress-bar bg-danger';
            text.innerHTML = 'Muy débil';
        } else if (strength < 50) {
            bar.className = 'progress-bar bg-warning';
            text.innerHTML = 'Débil';
        } else if (strength < 75) {
            bar.className = 'progress-bar bg-info';
            text.innerHTML = 'Regular';
        } else if (strength < 100) {
            bar.className = 'progress-bar bg-primary';
            text.innerHTML = 'Fuerte';
        } else {
            bar.className = 'progress-bar bg-success';
            text.innerHTML = 'Muy fuerte';
        }
    });
</script>
@endsection