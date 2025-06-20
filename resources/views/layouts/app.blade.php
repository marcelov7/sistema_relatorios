<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema de Relatórios')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Acessibilidade CSS -->
    <link href="{{ asset('css/accessibility.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6f42c1;
            --primary-dark: #5a359a;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --border-radius: 0.75rem;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Modern Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            padding: 1rem 0;
        }

        .navbar-modern .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .navbar-modern .navbar-brand:hover {
            color: #f8f9fa !important;
        }

        .navbar-modern .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        .navbar-modern .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
            transform: translateY(-1px);
        }

        .navbar-modern .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            margin-top: 0.5rem;
            z-index: 9999;
        }

        .navbar-modern .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.125rem;
            transition: all 0.3s ease;
        }

        .navbar-modern .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 88px);
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        /* Buttons */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        /* Remover sublinhados dos links */
        a {
            text-decoration: none !important;
        }
        
        a:hover {
            text-decoration: none !important;
        }
        
        /* Manter sublinhado apenas em links de texto corrido quando necessário */
        .content a,
        .card-text a,
        .description a {
            text-decoration: underline;
        }
        
        .content a:hover,
        .card-text a:hover,
        .description a:hover {
            text-decoration: underline;
        }

        /* Dropdown Z-Index Fix */
        .dropdown-menu {
            z-index: 9999 !important;
        }

        .dropdown-menu.show {
            z-index: 9999 !important;
        }

        /* Fix para modais e outros componentes */
        .modal {
            z-index: 10000;
        }

        .modal-backdrop {
            z-index: 9999;
        }

        .toast {
            z-index: 10001;
        }

        .tooltip {
            z-index: 10002;
        }

        .popover {
            z-index: 10003;
        }

        /* Cards e outros componentes */
        .card {
            position: relative;
            z-index: 1;
        }

        .navbar {
            z-index: 1030;
        }

        /* Bootstrap overrides */
        .btn-group .dropdown-menu,
        .nav-item .dropdown-menu,
        .dropdown .dropdown-menu {
            z-index: 9999 !important;
        }

        /* Menu collapse sempre oculto - hambúrguer em todas as telas */
        .navbar-collapse {
            display: none !important;
        }

        .navbar-collapse.show {
            display: block !important;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 1rem;
            z-index: 9999;
        }

        /* Botão Voltar ao Topo - Apenas Mobile */
        .back-to-top {
            display: none; /* Oculto por padrão */
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
        }

        .back-to-top.show {
            opacity: 1;
            transform: translateY(0);
        }

        .back-to-top:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #7c3aed 100%);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(111, 66, 193, 0.4);
        }

        .back-to-top:active {
            transform: translateY(-1px);
        }

        /* Mostrar apenas em telas móveis (até 768px) */
        @media (max-width: 768px) {
            .back-to-top {
                display: block;
            }
        }

        /* Estilo para informações do usuário no menu */
        .navbar-collapse .nav-link {
            padding: 0.75rem 0 !important;
            margin: 0.25rem 0;
        }

        .navbar-collapse .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(255,255,255,0.2);
        }

        /* Link de sair com cor vermelha */
        .navbar-collapse .nav-link.text-danger {
            color: #ff6b6b !important;
        }

        .navbar-collapse .nav-link.text-danger:hover {
            background: rgba(255,107,107,0.1);
            color: #ff5252 !important;
        }

        /* Botão hambúrguer sempre visível */
        .navbar-toggler {
            display: block !important;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: var(--border-radius);
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255,255,255,0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
            
            .navbar-modern {
                padding: 0.75rem 0;
            }
            
            .navbar-modern .navbar-brand {
                font-size: 1.25rem;
            }
            
            /* Mobile dropdown fix */
            .dropdown-menu {
                position: absolute !important;
                z-index: 9999 !important;
                transform: none !important;
            }
        }

        .system-footer {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            padding: 15px 0;
            margin-top: 50px;
        }

        .system-footer .text-muted:hover {
            color: #495057 !important;
        }

        @media (max-width: 768px) {
            .system-footer {
                margin-top: 30px;
                padding: 10px 0;
            }
            
            .system-footer .col-md-6 {
                text-align: center !important;
                margin-bottom: 5px;
            }
            
            .system-footer .col-md-6:last-child {
                margin-bottom: 0;
            }
        }
    </style>

    @stack('styles')
    <link rel="manifest" href="/sistema-relatorios/public/manifest.json">
    <meta name="theme-color" content="#0d6efd">
</head>
<body>
    <div id="app">
        <!-- Modern Navbar -->
        <nav class="navbar navbar-modern">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-gear-wide-connected me-2"></i>
                    Sistema de Relatórios
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <i class="bi bi-house me-1"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('relatorios.index') }}">
                                    <i class="bi bi-file-text me-1"></i>
                                    Relatórios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('analytics.dashboard') }}">
                                    <i class="bi bi-graph-up me-1"></i>
                                    Analytics
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pdf.index') }}">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    PDFs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('locais.index') }}">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    Locais
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('equipamentos.index') }}">
                                    <i class="bi bi-gear-wide-connected me-1"></i>
                                    Equipamentos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('analisadores.index') }}">
                                    <i class="bi bi-graph-up me-1"></i>
                                    Analisadores
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('motores.index') }}">
                                    <i class="bi bi-cpu me-1"></i>
                                    Motores
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('inspecoes-gerador.index') }}">
                                    <i class="bi bi-lightning-charge me-1"></i>
                                    Inspeções de Gerador
                                </a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        Login
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus me-1"></i>
                                        Registrar
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- Admin Access -->
                            @if(hasRole(['admin', 'supervisor']))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-gear me-1"></i>
                                        Admin
                                    </a>
                                </li>
                            @endif

                            <!-- Separador -->
                            <li class="nav-item">
                                <hr class="dropdown-divider my-2" style="border-color: rgba(255,255,255,0.2);">
                            </li>

                            <!-- User Info -->
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('profile.show') }}">
                                    <div class="avatar me-2" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-person-circle text-white"></i>
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                            </li>

                            <!-- User Actions -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <i class="bi bi-house me-1"></i>
                                    Dashboard
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-1"></i>
                                    Meu Perfil
                                </a>
                            </li>
                            
                            @if(hasRole(['admin', 'supervisor']))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-gear me-1"></i>
                                        Admin Panel
                                    </a>
                                </li>
                            @endif
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('system.info') }}">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Sistema
                                </a>
                            </li>
                            
                            @if(hasRole(['admin', 'supervisor']))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('system.upload-debug') }}">
                                        <i class="bi bi-bug me-1"></i>
                                        Debug Upload
                                    </a>
                                </li>
                            @endif
                            
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Sair
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container">
                <!-- Breadcrumb Dinâmico -->
                @include('components.breadcrumb')
            </div>
            @yield('content')
        </main>
    </div>

    <!-- Botão Voltar ao Topo (Apenas Mobile) -->
    <button class="back-to-top" id="backToTop" aria-label="Voltar ao topo">
        <i class="bi bi-chevron-up"></i>
    </button>

    <!-- Footer com Versão -->
    <footer class="system-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-gear text-muted me-2"></i>
                        <span class="text-muted small">
                            {{ config('app.name', 'Sistema de Relatórios') }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <a href="{{ route('system.info') }}" class="text-decoration-none text-muted small me-3" title="Informações do Sistema">
                            <i class="bi bi-info-circle me-1"></i>
                            v{{ app_version() }}
                        </a>
                        <span class="text-muted small">
                            Build #{{ app_build() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dropdown Z-Index Fix Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix para dropdowns que podem ficar atrás de outros elementos
            const dropdowns = document.querySelectorAll('.dropdown');
            
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('show.bs.dropdown', function() {
                    const menu = this.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.style.zIndex = '9999';
                        menu.style.position = 'absolute';
                    }
                });
                
                dropdown.addEventListener('hidden.bs.dropdown', function() {
                    const menu = this.querySelector('.dropdown-menu');
                    if (menu) {
                        menu.style.zIndex = '';
                        menu.style.position = '';
                    }
                });
            });
            
            // Fix específico para dropdowns no navbar
            const navbarDropdowns = document.querySelectorAll('.navbar .dropdown');
            navbarDropdowns.forEach(function(dropdown) {
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                
                if (toggle && menu) {
                    toggle.addEventListener('click', function() {
                        setTimeout(function() {
                            if (menu.classList.contains('show')) {
                                menu.style.zIndex = '9999';
                                menu.style.position = 'absolute';
                            }
                        }, 10);
                    });
                }
            });

            // Botão Voltar ao Topo - Apenas Mobile
            const backToTopButton = document.getElementById('backToTop');
            
            if (backToTopButton) {
                // Mostrar/ocultar botão baseado no scroll
                function toggleBackToTopButton() {
                    if (window.innerWidth <= 768) { // Apenas em mobile
                        if (window.pageYOffset > 300) {
                            backToTopButton.classList.add('show');
                        } else {
                            backToTopButton.classList.remove('show');
                        }
                    } else {
                        backToTopButton.classList.remove('show');
                    }
                }

                // Event listeners
                window.addEventListener('scroll', toggleBackToTopButton);
                window.addEventListener('resize', toggleBackToTopButton);

                // Clique no botão
                backToTopButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Scroll suave para o topo
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    
                    // Efeito visual de clique
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });

                // Verificar posição inicial
                toggleBackToTopButton();
            }
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sistema-relatorios/public/service-worker.js');
        }
    </script>

    @stack('scripts')
</body>
</html>
