@extends('admin.layout')

@section('title', 'Visualizar Usuário')

@php
    $pageTitle = 'Visualizar Usuário';
    $pageActions = '<a href="' . route('admin.users.edit', $usuario) . '" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Editar
                    </a>';
    
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Informações Básicas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Informações Básicas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nome Completo</label>
                            <p class="fw-bold">{{ $usuario->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-bold">{{ $usuario->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Cargo</label>
                            <p class="fw-bold">{{ $usuario->cargo ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Departamento</label>
                            <p class="fw-bold">
                                @if($usuario->departamento)
                                    <span class="badge bg-info">
                                        {{ $departamentos[$usuario->departamento] ?? $usuario->departamento }}
                                    </span>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Telefone</label>
                            <p class="fw-bold">{{ $usuario->telefone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p>
                                @if($usuario->ativo)
                                    <span class="badge badge-status-ativo">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Ativo
                                    </span>
                                @else
                                    <span class="badge badge-status-inativo">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Inativo
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações de Sistema -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Informações do Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Cadastrado em</label>
                            <p class="fw-bold">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Última atualização</label>
                            <p class="fw-bold">{{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Último acesso</label>
                            <p class="fw-bold">
                                @if($usuario->ultimo_acesso)
                                    {{ $usuario->ultimo_acesso->format('d/m/Y H:i') }}
                                @else
                                    Nunca acessou
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email verificado</label>
                            <p class="fw-bold">
                                @if($usuario->email_verified_at)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Verificado
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Não verificado
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Roles e Permissões -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    Roles e Permissões
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Roles Atribuídas</label>
                    @if($usuario->roles->count() > 0)
                        @foreach($usuario->roles as $role)
                            <div class="mb-2">
                                <span class="badge bg-primary me-2">{{ ucfirst($role->name) }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Nenhuma role atribuída</p>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Permissões</label>
                    @if($usuario->getAllPermissions()->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($usuario->getAllPermissions() as $permission)
                                <span class="badge bg-secondary">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Nenhuma permissão específica</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Estatísticas
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-12 mb-3">
                        <h4 class="text-primary">0</h4>
                        <small class="text-muted">Relatórios Criados</small>
                    </div>
                    <div class="col-12 mb-3">
                        <h4 class="text-success">0</h4>
                        <small class="text-muted">Relatórios Concluídos</small>
                    </div>
                    <div class="col-12">
                        <h4 class="text-warning">0</h4>
                        <small class="text-muted">Pendentes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Ações
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $usuario) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Editar Usuário
                    </a>
                    
                    @if($usuario->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $usuario) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-{{ $usuario->ativo ? 'warning' : 'success' }} w-100"
                                    onclick="return confirm('Confirma {{ $usuario->ativo ? 'desativação' : 'ativação' }} do usuário?')">
                                <i class="bi bi-{{ $usuario->ativo ? 'pause' : 'play' }} me-1"></i>
                                {{ $usuario->ativo ? 'Desativar' : 'Ativar' }} Usuário
                            </button>
                        </form>
                        
                        <hr>
                        
                        <form action="{{ route('admin.users.destroy', $usuario) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                <i class="bi bi-trash me-1"></i>
                                Excluir Usuário
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Voltar -->
<div class="d-flex justify-content-start mt-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar para Lista
    </a>
</div>
@endsection 