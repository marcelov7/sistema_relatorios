@extends('admin.layout')

@section('title', 'Gerenciar Usuários')
@section('page-title', 'Usuários')

@php
    $pageTitle = 'Gerenciar Usuários';
    $pageDescription = 'Visualize e gerencie todos os usuários do sistema';
    $pageActions = '<a href="' . route('admin.users.create') . '" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        <span class="d-none d-sm-inline">Novo Usuário</span>
                        <span class="d-sm-none">Novo</span>
                    </a>';

@endphp

@push('styles')
<style>
    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 1rem;
    }

    .user-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        overflow: hidden;
    }

    .user-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    .user-avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .user-status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
    }

    .action-buttons .btn {
        margin: 0.125rem;
        border-radius: 0.5rem;
    }

    .stats-row {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 1rem;
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .table-container {
        min-height: 200px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* Melhorar responsividade geral */
    .content-wrapper {
        max-height: calc(100vh - 60px);
        overflow-y: auto;
    }

    /* Scroll suave */
    .table-container {
        scroll-behavior: smooth;
    }

    /* Compactar labels em telas menores */
    @media (max-width: 1300px) {
        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .form-control, .form-select {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    /* Compactar elementos em telas menores */
    @media (max-width: 1400px) {
        .stats-row {
            padding: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .filter-card {
            margin-bottom: 0.75rem;
        }
        
        .filter-card .card-body {
            padding: 0.75rem;
        }
        
        .page-header {
            margin-bottom: 0.75rem !important;
        }
    }

    @media (max-width: 767px) {
        .filter-card .row > div {
            margin-bottom: 0.75rem;
        }
        
        .filter-card .row > div:last-child {
            margin-bottom: 0;
        }
        
        .user-card {
            margin-bottom: 0.75rem;
        }
        
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .action-buttons .btn {
            flex: 1;
            min-width: auto;
        }

        .stats-row {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .filter-card {
            margin-bottom: 0.75rem;
        }
    }

    @media (max-width: 1199px) {
        /* Ajustes para tablets e mobile - usar cards */
        .user-card {
            margin-bottom: 0.75rem;
        }
        
        .user-avatar-large {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .action-buttons .btn {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }
        
        .card-header h6 {
            font-size: 0.9rem;
        }
        
        .badge {
            font-size: 0.75rem;
        }
    }

    @media (min-width: 1200px) {
        /* Ajustes para desktop - usar tabela */
        .table th, .table td {
            padding: 0.6rem;
            font-size: 0.9rem;
        }
        
        .action-buttons .btn {
            padding: 0.3rem 0.6rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Statistics Row -->
<div class="stats-row">
    <div class="row text-center g-2">
        <div class="col-6 col-md-3">
            <div class="fw-bold text-primary fs-5 fs-md-4">{{ $usuarios->total() }}</div>
            <small class="text-muted d-block">Total</small>
        </div>
        <div class="col-6 col-md-3">
            <div class="fw-bold text-success fs-5 fs-md-4">{{ $usuarios->where('ativo', true)->count() }}</div>
            <small class="text-muted d-block">Ativos</small>
        </div>
        <div class="col-6 col-md-3">
            <div class="fw-bold text-warning fs-5 fs-md-4">{{ $usuarios->where('ativo', false)->count() }}</div>
            <small class="text-muted d-block">Inativos</small>
        </div>
        <div class="col-6 col-md-3">
            <div class="fw-bold text-info fs-5 fs-md-4">{{ $roles->count() }}</div>
            <small class="text-muted d-block">Roles</small>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card filter-card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
            <div class="row g-2 g-md-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="search" class="form-label fw-semibold">
                        <i class="bi bi-search me-1"></i>Pesquisar
                    </label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nome, email ou cargo...">
                </div>
                
                <div class="col-6 col-md-6 col-lg-2">
                    <label for="departamento" class="form-label fw-semibold">
                        <i class="bi bi-building me-1"></i>Departamento
                    </label>
                    <select class="form-select" id="departamento" name="departamento">
                        <option value="">Todos</option>
                        @foreach($departamentos as $key => $nome)
                            <option value="{{ $key }}" {{ request('departamento') == $key ? 'selected' : '' }}>
                                {{ $nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-6 col-md-6 col-lg-2">
                    <label for="role" class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Role
                    </label>
                    <select class="form-select" id="role" name="role">
                        <option value="">Todos</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-6 col-md-6 col-lg-2">
                    <label for="ativo" class="form-label fw-semibold">
                        <i class="bi bi-toggle-on me-1"></i>Status
                    </label>
                    <select class="form-select" id="ativo" name="ativo">
                        <option value="">Todos</option>
                        <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
                
                <div class="col-6 col-md-12 col-lg-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            <span class="d-none d-sm-inline">Filtrar</span>
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@if($usuarios->count() > 0)
    <!-- Desktop Table -->
    <div class="card d-none d-xl-block table-container">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="bi bi-people me-2"></i>
                Lista de Usuários
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Usuário</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Departamento</th>
                            <th>Status</th>
                            <th>Último Acesso</th>
                            <th class="text-center" width="180">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-color), #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $usuario->name }}</div>
                                        @if($usuario->cargo)
                                            <small class="text-muted">{{ $usuario->cargo }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @if($usuario->roles->count() > 0)
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $usuario->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Sem role</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->departamento)
                                    <span class="badge bg-info rounded-pill">{{ $departamentos[$usuario->departamento] ?? $usuario->departamento }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                @if($usuario->ultimo_acesso)
                                    <small class="text-muted">
                                        {{ $usuario->ultimo_acesso->format('d/m/Y H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">Nunca</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.users.show', $usuario) }}" 
                                       class="btn btn-sm btn-outline-info" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.users.edit', $usuario) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-status', $usuario) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-{{ $usuario->ativo ? 'warning' : 'success' }}" 
                                                    title="{{ $usuario->ativo ? 'Desativar' : 'Ativar' }}"
                                                    onclick="return confirm('Confirma {{ $usuario->ativo ? 'desativação' : 'ativação' }} do usuário?')">
                                                <i class="bi bi-{{ $usuario->ativo ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.users.destroy', $usuario) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Excluir"
                                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn btn-sm btn-outline-secondary disabled" title="Próprio usuário">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile/Tablet Cards -->
    <div class="d-xl-none">
        @foreach($usuarios as $usuario)
        <div class="card user-card">
            <div class="card-body position-relative">
                <!-- Status Badge -->
                <div class="user-status-badge">
                    @if($usuario->ativo)
                        <span class="badge badge-status-ativo">
                            <i class="bi bi-check-circle me-1"></i>Ativo
                        </span>
                    @else
                        <span class="badge badge-status-inativo">
                            <i class="bi bi-x-circle me-1"></i>Inativo
                        </span>
                    @endif
                </div>

                <!-- User Info -->
                <div class="d-flex align-items-start mb-3">
                    <div class="user-avatar-large me-3">
                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">{{ $usuario->name }}</h5>
                        <p class="text-muted mb-0">{{ $usuario->email }}</p>
                        @if($usuario->cargo)
                            <small class="text-muted">{{ $usuario->cargo }}</small>
                        @endif
                    </div>
                </div>

                <!-- Details -->
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Role:</small>
                        @if($usuario->roles->count() > 0)
                            <span class="badge bg-primary rounded-pill">
                                {{ $usuario->roles->first()->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary rounded-pill">Sem role</span>
                        @endif
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Departamento:</small>
                        @if($usuario->departamento)
                            <span class="badge bg-info rounded-pill">
                                {{ $departamentos[$usuario->departamento] ?? $usuario->departamento }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <small class="text-muted d-block">Último Acesso:</small>
                        @if($usuario->ultimo_acesso)
                            <small class="fw-semibold">{{ $usuario->ultimo_acesso->format('d/m/Y H:i') }}</small>
                        @else
                            <small class="text-muted">Nunca</small>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="action-buttons">
                    <a href="{{ route('admin.users.show', $usuario) }}" 
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye me-1"></i>Ver
                    </a>
                    
                    <a href="{{ route('admin.users.edit', $usuario) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    
                    @if($usuario->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $usuario) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-outline-{{ $usuario->ativo ? 'warning' : 'success' }} btn-sm"
                                    onclick="return confirm('Confirma {{ $usuario->ativo ? 'desativação' : 'ativação' }} do usuário?')">
                                <i class="bi bi-{{ $usuario->ativo ? 'pause' : 'play' }} me-1"></i>
                                {{ $usuario->ativo ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.users.destroy', $usuario) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                <i class="bi bi-trash me-1"></i>Excluir
                            </button>
                        </form>
                    @else
                        <span class="btn btn-outline-secondary btn-sm disabled">
                            <i class="bi bi-lock me-1"></i>Próprio usuário
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    @if($usuarios->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $usuarios->withQueryString()->links() }}
    </div>
    @endif

@else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Nenhum usuário encontrado</h4>
            <p class="text-muted">Não encontramos usuários com os filtros aplicados.</p>
            <div class="mt-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary me-2">
                    <i class="bi bi-arrow-clockwise me-1"></i>Limpar Filtros
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>Novo Usuário
                </a>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Auto-submit filtros quando mudarem
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('#departamento, #role, #ativo');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Submit no Enter para o campo de pesquisa
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    });

    // Confirmation messages with better UX
    function confirmAction(message, form) {
        if (confirm(message)) {
            form.closest('form').submit();
        }
    }
</script>
@endpush 