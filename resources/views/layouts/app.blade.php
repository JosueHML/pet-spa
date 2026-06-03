<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pet Spa') }} | El mejor cuidado para tu mascota</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">

    <!-- Bootstrap CSS (CDN - funciona en celular) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
        }
        
        .navbar-brand i {
            margin-right: 8px;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }
        
        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            margin-top: 8px;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #5a67d8;
        }
        
        .badge-notification {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
        }
        
        .nav-item {
            position: relative;
        }
        
        /* Tarjetas de métricas */
        .dashboard-metrics {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .card-metric {
            flex: 1;
            min-width: 200px;
            padding: 20px;
            border-radius: 15px;
            color: white;
            text-align: center;
        }
        .bg-metric-primary { background: linear-gradient(135deg, #667eea, #764ba2); }
        .bg-metric-success { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .bg-metric-warning { background: linear-gradient(135deg, #f093fb, #f5576c); }
        .bg-metric-info { background: linear-gradient(135deg, #4facfe, #00f2fe); }
        .card-metric h3 { font-size: 2rem; margin-bottom: 5px; }
        .card-metric p { margin: 0; opacity: 0.9; }
    </style>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(() => console.log('Service Worker registrado correctamente'));
        }
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="container">
                <a class="navbar-brand text-white fw-bold" href="{{ url('/') }}">
                    <i class="fas fa-paw"></i> {{ config('app.name', 'Pet Spa') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(Auth::user()->id_rol == 1)
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                            @elseif(Auth::user()->id_rol == 2)
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('cajero.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                            @elseif(Auth::user()->id_rol == 3)
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('groomer.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('cliente.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('carrito.index') }}">
                                    <i class="fas fa-shopping-cart"></i> 🛒 Carrito
                                </a>
                            </li>
                            <li class="nav-item position-relative">
                                <a class="nav-link text-white" href="{{ route('notificaciones.index') }}">
                                    <i class="fas fa-bell"></i> Notificaciones
                                    @php
                                        $notificacionesNoLeidas = App\Models\Notificacion::where('id_usuario', Auth::id())
                                            ->where('leida', 0)
                                            ->count();
                                    @endphp
                                    @if($notificacionesNoLeidas > 0)
                                        <span class="badge bg-danger rounded-pill badge-notification">{{ $notificacionesNoLeidas }}</span>
                                    @endif
                                </a>
                            </li>
                        @endauth

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> Registrarse
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-lg">
                                    @if(Auth::user()->id_rol == 1)
                                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                            <i class="fas fa-users"></i> Gestionar Personal
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.reports') }}">
                                            <i class="fas fa-chart-line"></i> Reportes
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.logs') }}">
                                            <i class="fas fa-history"></i> Log de Auditoría
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('password.change') }}">
                                        <i class="fas fa-key"></i> Cambiar Contraseña
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 fade-in">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS (CDN - funciona en celular) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>