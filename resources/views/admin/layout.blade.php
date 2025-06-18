<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistema de Relatórios') - Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6f42c1;
            --primary-dark: #5a359a;
            --sidebar-bg: #1a1d29;
            --sidebar-hover: #2d3142;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --border-radius: 0.75rem;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #2d3142 100%);
            min-height: 100vh;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h5 {
            color: #fff;
            font-weight: 600;
            margin: 0;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
        }

        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            background: #f8fafc;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: #fff;
            box-shadow: var(--card-shadow);
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 0;
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Menu hambúrguer sempre visível */
        #sidebarToggle {
            display: block !important;
            border: 1px solid #dee2e6;
            background: #fff;
            color: #495057;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            transition: all 0.3s ease;
        }

        #sidebarToggle:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
            color: #212529;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            padding: 0.5rem 1rem;
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

        /* Tables */
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .table th {
            background: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }

        .table-responsive {
            border-radius: var(--border-radius);
        }

        /* Badges */
        .badge {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .badge-status-ativo {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .badge-status-inativo {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
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

        /* Bootstrap overrides */
        .btn-group .dropdown-menu,
        .nav-item .dropdown-menu,
        .dropdown .dropdown-menu {
            z-index: 9999 !important;
        }

        /* Sidebar sempre oculta - Menu hambúrguer em todas as telas */
        .sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            z-index: 1050;
            width: 280px;
            height: 100vh;
            overflow-y: auto;
            transition: left 0.3s ease;
        }

        .sidebar.show {
            left: 0;
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
            
            /* Dropdown fix para mobile/tablet */
            .dropdown-menu {
                position: absolute !important;
                z-index: 9999 !important;
                transform: none !important;
            }
        }

        /* Ajustes específicos para tablets (768px - 1199px) */
        @media (min-width: 768px) and (max-width: 1199px) {
            .content-wrapper {
                padding: 1rem !important;
            }

            .page-header {
                margin-bottom: 1rem !important;
            }

            .top-navbar {
                padding: 0.75rem 0;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

        /* Ajustes específicos para mobile (< 768px) */
        @media (max-width: 767px) {
            .content-wrapper {
                padding: 0.75rem !important;
            }

            .page-header {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start !important;
                margin-bottom: 1rem !important;
            }

            .page-actions {
                align-self: stretch;
            }

            /* Compact mobile navbar */
            .top-navbar {
                padding: 0.5rem 0;
            }

            .navbar-brand {
                font-size: 1.1rem !important;
            }

            /* Hide unnecessary spacing */
            .card {
                margin-bottom: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.5rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
            color: white;
            border-radius: var(--border-radius);
        }

        .stats-card .card-body {
            padding: 1.5rem;
        }

        /* Loading Animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                    <div class="sidebar-brand text-center">
                        <h5>
                            <i class="bi bi-gear-fill me-2"></i>
                            Admin Panel
                        </h5>
                    </div>
                    
                    <nav class="nav flex-column py-3">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                        
                        @if(hasRole(['admin', 'supervisor']))
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people"></i>
                            Usuários
                        </a>
                        @endif
                        
                        @if(hasRole('admin'))
                        <a class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}" 
                           href="{{ route('admin.roles.index') }}">
                            <i class="bi bi-shield-lock"></i>
                            Roles & Permissões
                        </a>
                        @endif
                        
                        <hr class="text-white-50 mx-3">
                        
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="bi bi-house"></i>
                            Voltar ao Site
                        </a>
                    </nav>
                </div>
            
            <!-- Main Content -->
            <div class="col">
                <div class="main-content">
                    <!-- Top Navbar -->
                    <nav class="navbar navbar-expand-lg top-navbar">
                        <div class="container-fluid">
                            <!-- Menu hambúrguer -->
                            <button class="btn btn-outline-secondary me-3" type="button" id="sidebarToggle">
                                <i class="bi bi-list fs-4"></i>
                            </button>
                            
                            <!-- Page title -->
                            <span class="navbar-brand mb-0 fw-bold fs-5">
                                @yield('page-title', 'Admin')
                            </span>
                            
                            <!-- User menu -->
                            <div class="navbar-nav ms-auto">
                                <div class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <i class="bi bi-person-circle fs-5"></i>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                                <small class="text-muted">{{ auth()->user()->getRoleNameAttribute() }}</small>
                                            </div>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow">
                                        <li>
                                            <h6 class="dropdown-header">
                                                <i class="bi bi-person me-2"></i>
                                                {{ auth()->user()->name }}
                                            </h6>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('home') }}">
                                                <i class="bi bi-house me-2"></i>
                                                Dashboard Principal
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-box-arrow-right me-2"></i>
                                                    Sair
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                    <!-- Page Content -->
                    <div class="content-wrapper p-2 p-md-4">
                        <!-- Breadcrumb Dinâmico -->
                        @include('components.breadcrumb')
                        
                        <!-- Page Header -->
                        @if(isset($pageTitle))
                        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 page-header">
                            <div>
                                <h2 class="mb-1 fw-bold fs-4 fs-md-2">{{ $pageTitle }}</h2>
                                @if(isset($pageDescription))
                                    <p class="text-muted mb-0 d-none d-xl-block">{{ $pageDescription }}</p>
                                @endif
                            </div>
                            @if(isset($pageActions))
                                <div class="page-actions">
                                    {!! $pageActions !!}
                                </div>
                            @endif
                        </div>
                        @endif
                        
                        <!-- Alerts -->
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Sucesso!</strong> {{ session('success') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Erro!</strong> {{ session('error') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>
                                    <strong>Erro!</strong> Corrija os seguintes problemas:
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        
                        <!-- Main Content -->
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        });
    </script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.querySelector('.btn-close')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html> 