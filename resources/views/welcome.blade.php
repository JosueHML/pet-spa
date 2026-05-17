<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pet Spa') }} | El mejor cuidado para tu mascota</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #333;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #667eea;
            transform: translateY(-2px);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-register {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
        }

        .hero-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 10;
            padding: 100px 0;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-buttons .btn {
            margin: 10px;
            padding: 12px 35px;
            font-weight: 600;
            border-radius: 50px;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .btn-hero-primary {
            background: white;
            color: #667eea;
            border: none;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .btn-hero-secondary {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        /* Features Section */
        .features {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .feature-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .feature-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
            padding: 30px;
        }

        .stat-item i {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .stat-item h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-item p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Testimonios Section */
        .testimonials-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .testimonial-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .testimonial-card i.fa-quote-left {
            color: #667eea;
            margin-bottom: 20px;
        }

        .testimonial-card p {
            color: #555;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .stars {
            color: #ffc107;
            margin-bottom: 15px;
        }

        .testimonial-card h6 {
            font-weight: 600;
            color: #333;
            margin-top: 10px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
            color: white;
            padding: 50px 0 20px;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            .hero-subtitle {
                font-size: 1rem;
            }
            .stat-item h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-paw"></i> Pet Spa
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                    @auth
                        @if(Auth::user()->id_rol == 1)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @elseif(Auth::user()->id_rol == 2)
                            <li class="nav-item"><a class="nav-link" href="{{ route('cajero.dashboard') }}">Dashboard</a></li>
                        @elseif(Auth::user()->id_rol == 3)
                            <li class="nav-item"><a class="nav-link" href="{{ route('groomer.dashboard') }}">Dashboard</a></li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('cliente.dashboard') }}">Dashboard</a></li>
                        @endif
                    @endauth
                </ul>
                @guest
                    <div class="ms-3">
                        <a href="{{ route('login') }}" class="btn btn-login me-2">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-register">Registrarse</a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">¡El mejor cuidado para tu mascota!</h1>
                <p class="hero-subtitle">Sistema de gestión integral para spa y tienda de mascotas.<br>
                Agenda citas, compra productos y cuida a tu mejor amigo con nosotros.</p>
                <div class="hero-buttons">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-hero-primary">Regístrate Ahora</a>
                        <a href="{{ route('login') }}" class="btn btn-hero-secondary">Iniciar Sesión</a>
                    @else
                        @if(Auth::user()->id_rol == 1)
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-hero-primary">Ir al Dashboard</a>
                        @elseif(Auth::user()->id_rol == 2)
                            <a href="{{ route('cajero.dashboard') }}" class="btn btn-hero-primary">Ir al Dashboard</a>
                        @elseif(Auth::user()->id_rol == 3)
                            <a href="{{ route('groomer.dashboard') }}" class="btn btn-hero-primary">Ir al Dashboard</a>
                        @else
                            <a href="{{ route('cliente.dashboard') }}" class="btn btn-hero-primary">Ir al Dashboard</a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>¿Qué ofrecemos?</h2>
                <p>Servicios de calidad para el bienestar de tu mascota</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h4>Grooming Profesional</h4>
                        <p>Baño, corte, uñas, oídos y más. Todo el cuidado que tu mascota necesita.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h4>Tienda de Productos</h4>
                        <p>Alimentos, juguetes, accesorios y productos de higiene para mascotas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Agenda en Línea</h4>
                        <p>Agenda tus citas fácilmente y recibe recordatorios automáticos por email.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-smile"></i>
                        <h2 class="counter" data-target="500">0</h2>
                        <p>Mascotas felices</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-scissors"></i>
                        <h2 class="counter" data-target="1000">0</h2>
                        <p>Servicios realizados</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <h2 class="counter" data-target="200">0</h2>
                        <p>Clientes registrados</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <i class="fas fa-star"></i>
                        <h2 class="counter" data-target="5" data-decimals="1">0.0</h2>
                        <p>Calificación promedio</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonios Section -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Lo que dicen nuestros clientes</h2>
                <p>Más de 500 mascotas felices nos respaldan</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-left fa-2x"></i>
                        <p>"Excelente servicio, mi perrito quedó hermoso. Muy profesionales y atentos. Definitivamente volveré."</p>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h6>- María González</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-left fa-2x"></i>
                        <p>"La agenda online es muy práctica. Los recordatorios por email me ayudan a no olvidar las citas de mi mascota."</p>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h6>- Carlos Rodríguez</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <i class="fas fa-quote-left fa-2x"></i>
                        <p>"Los productos de la tienda son de excelente calidad y el pedido por WhatsApp es súper rápido."</p>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h6>- Ana Laura</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-paw"></i> Pet Spa</h5>
                    <p>El mejor cuidado para tu mascota. Profesionales en grooming y venta de productos.</p>
                </div>
                <div class="col-md-4">
                    <h5>Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home">Inicio</a></li>
                        <li><a href="#features">Servicios</a></li>
                        <li><a href="#testimonials">Testimonios</a></li>
                        @guest
                            <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                            <li><a href="{{ route('register') }}">Registrarse</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> +591 69866825</li>
                        <li><i class="fas fa-envelope"></i> info@petspa.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> La Paz - Bolivia</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Pet Spa. Todos los derechos reservados.</p>
                <p>Desarrollado por: [JOSUE HUGO MAMANI LAGUNA]</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // 1. Animación de tarjetas al hacer scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observar tarjetas de características
        document.querySelectorAll('.feature-card').forEach(card => {
            observer.observe(card);
        });

        // Observar tarjetas de testimonios
        document.querySelectorAll('.testimonial-card').forEach(card => {
            observer.observe(card);
        });

        // 2. Contadores animados
        function animateCounter(element) {
            const target = parseFloat(element.getAttribute('data-target'));
            const hasDecimals = element.getAttribute('data-decimals') === '1';
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    if (hasDecimals) {
                        element.innerText = target.toFixed(1);
                    } else {
                        element.innerText = Math.floor(target);
                    }
                    clearInterval(timer);
                } else {
                    if (hasDecimals) {
                        element.innerText = current.toFixed(1);
                    } else {
                        element.innerText = Math.floor(current);
                    }
                }
            }, 16);
        }

        // Activar contadores cuando sean visibles
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.counter').forEach(counter => {
            counterObserver.observe(counter);
        });
    </script>
</body>
</html>