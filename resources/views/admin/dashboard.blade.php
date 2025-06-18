@extends('admin.layout')

@section('title', 'Dashboard Administrativo')
@section('page-title', 'Dashboard')

@php
    $pageTitle = 'Dashboard Administrativo';
    $pageDescription = 'Visão geral do sistema e estatísticas importantes';
@endphp

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1rem;
        color: white;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .stats-card.success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stats-card.warning {
        background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
    }

    .stats-card.info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .stats-label {
        font-size: 0.875rem;
        opacity: 0.9;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-icon {
        font-size: 3rem;
        opacity: 0.3;
    }

    .chart-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background: #f1f3f4;
        overflow: hidden;
    }

    .progress-bar-custom {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    .department-item, .role-item {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .department-item:hover, .role-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .recent-users-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .stats-number {
            font-size: 1.75rem;
        }
        
        .stats-icon {
            font-size: 1.75rem;
        }
        
        .chart-card {
            margin-bottom: 1rem;
        }

        .stats-card .card-body {
            padding: 1rem;
        }

        .stats-label {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Stats Cards Row -->
<div class="row g-3 g-md-4 mb-3 mb-md-4">
    <div class="col-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center position-relative">
                <div class="flex-grow-1">
                    <div class="stats-label mb-2">Total de Usuários</div>
                    <div class="stats-number">{{ $totalUsuarios }}</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card stats-card success h-100">
            <div class="card-body d-flex align-items-center position-relative">
                <div class="flex-grow-1">
                    <div class="stats-label mb-2">Usuários Ativos</div>
                    <div class="stats-number">{{ $usuariosAtivos }}</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card stats-card warning h-100">
            <div class="card-body d-flex align-items-center position-relative">
                <div class="flex-grow-1">
                    <div class="stats-label mb-2">Usuários Inativos</div>
                    <div class="stats-number">{{ $usuariosInativos }}</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-person-x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card stats-card info h-100">
            <div class="card-body d-flex align-items-center position-relative">
                <div class="flex-grow-1">
                    <div class="stats-label mb-2">Total de Roles</div>
                    <div class="stats-number">{{ $totalRoles }}</div>
                </div>
                <div class="stats-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-3 g-md-4 mb-3 mb-md-4">
    <!-- Usuários por Departamento -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building text-primary me-2"></i>
                    Usuários por Departamento
                </h5>
            </div>
            <div class="card-body">
                @if($usuariosPorDepartamento->count() > 0)
                    @foreach($usuariosPorDepartamento as $departamento => $total)
                        <div class="department-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-capitalize">
                                    {{ $departamentos[$departamento] ?? ucfirst($departamento) ?? 'Não informado' }}
                                </span>
                                <span class="badge bg-primary rounded-pill">{{ $total }}</span>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar progress-bar-custom" 
                                     style="width: {{ $totalUsuarios > 0 ? ($total / $totalUsuarios) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Nenhum departamento cadastrado ainda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Usuários por Role -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header border-0 pb-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-badge text-success me-2"></i>
                    Usuários por Role
                </h5>
            </div>
            <div class="card-body">
                @if($usuariosPorRole->count() > 0)
                    @foreach($usuariosPorRole as $role => $total)
                        <div class="role-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-capitalize">{{ $role }}</span>
                                <span class="badge bg-success rounded-pill">{{ $total }}</span>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $totalUsuarios > 0 ? ($total / $totalUsuarios) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Nenhum role atribuído ainda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Users Row -->
<div class="row">
    <div class="col-12">
        <div class="card recent-users-card">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history text-info me-2"></i>
                    Últimos Usuários Cadastrados
                </h5>
                @if(hasRole(['admin', 'supervisor']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye me-1"></i>
                    Ver Todos
                </a>
                @endif
            </div>
            <div class="card-body">
                @if($ultimosUsuarios->count() > 0)
                    <!-- Desktop Table -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Departamento</th>
                                        <th>Status</th>
                                        <th>Cadastrado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ultimosUsuarios as $usuario)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $usuario->name }}</div>
                                                    <small class="text-muted">{{ $usuario->cargo ?? 'Não informado' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $usuario->roles->first()?->name ?? 'Sem role' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-capitalize">
                                                {{ $departamentos[$usuario->departamento] ?? $usuario->departamento ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($usuario->ativo)
                                                <span class="badge badge-status-ativo">
                                                    <i class="bi bi-check-circle me-1"></i>Ativo
                                                </span>
                                            @else
                                                <span class="badge badge-status-inativo">
                                                    <i class="bi bi-x-circle me-1"></i>Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $usuario->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="d-md-none">
                        @foreach($ultimosUsuarios as $usuario)
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="user-avatar me-3">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">{{ $usuario->name }}</h6>
                                        <p class="card-text small text-muted mb-2">{{ $usuario->email }}</p>
                                        
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-primary rounded-pill small">
                                                {{ $usuario->roles->first()?->name ?? 'Sem role' }}
                                            </span>
                                            @if($usuario->ativo)
                                                <span class="badge badge-status-ativo small">Ativo</span>
                                            @else
                                                <span class="badge badge-status-inativo small">Inativo</span>
                                            @endif
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Departamento:</small><br>
                                                <small class="fw-semibold">
                                                    {{ $departamentos[$usuario->departamento] ?? $usuario->departamento ?? '-' }}
                                                </small>
                                            </div>
                                            <div class="col-6 text-end">
                                                <small class="text-muted">Cadastrado:</small><br>
                                                <small class="fw-semibold">{{ $usuario->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">Nenhum usuário cadastrado ainda</h5>
                        <p class="text-muted">Quando houver usuários no sistema, eles aparecerão aqui.</p>
                        @if(hasRole(['admin']))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>
                            Cadastrar Primeiro Usuário
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate progress bars
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });
    });

    // Animate count numbers
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const target = parseInt(number.textContent);
            const increment = target / 50;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current);
            }, 30);
        });
    }

    // Start animations after page load
    window.addEventListener('load', animateNumbers);
</script>
@endpush 