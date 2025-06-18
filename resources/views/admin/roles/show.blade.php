@extends('admin.layout')

@section('title', 'Detalhes da Role')

@php
    $pageTitle = 'Detalhes da Role';
    $pageActions = '<a href="' . route('admin.roles.edit', $role) . '" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Editar
                    </a>';
    
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Informações da Role -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Informações da Role
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nome da Role</label>
                            <p class="fw-bold">{{ $role->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nome de Exibição</label>
                            <p class="fw-bold">{{ $role->display_name ?? ucfirst($role->name) }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Descrição</label>
                            <p class="fw-bold">{{ $role->description ?? 'Nenhuma descrição fornecida.' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Guard</label>
                            <p class="fw-bold">
                                <span class="badge bg-secondary">{{ $role->guard_name }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Tipo</label>
                            <p class="fw-bold">
                                @if(in_array($role->name, ['admin', 'supervisor', 'usuario']))
                                    <span class="badge bg-primary">Role do Sistema</span>
                                @else
                                    <span class="badge bg-info">Role Personalizada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissões da Role -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    Permissões Atribuídas
                    <span class="badge bg-success ms-2">{{ $role->permissions->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    @php
                        $permissionGroups = $role->permissions->groupBy(function($permission) {
                            return explode('_', $permission->name)[0];
                        });
                    @endphp
                    
                    <div class="row">
                        @foreach($permissionGroups as $group => $permissions)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-capitalize">
                                            <i class="bi bi-folder me-1"></i>
                                            {{ ucfirst($group) }}
                                            <span class="badge bg-primary ms-2">{{ $permissions->count() }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($permissions as $permission)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <span>{{ $permission->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-key text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Esta role não possui permissões atribuídas.</p>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Atribuir Permissões
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Usuários com esta Role -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    Usuários com esta Role
                    <span class="badge bg-info ms-2">{{ $role->users->count() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Email</th>
                                    <th>Departamento</th>
                                    <th>Status</th>
                                    <th>Último Acesso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-2 text-muted"></i>
                                                <div>
                                                    <div class="fw-bold">
                                                        <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                                            {{ $user->name }}
                                                        </a>
                                                    </div>
                                                    @if($user->cargo)
                                                        <small class="text-muted">{{ $user->cargo }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->departamento)
                                                <span class="badge bg-info">{{ $user->departamento }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->ativo)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->ultimo_acesso)
                                                <small class="text-muted">
                                                    {{ $user->ultimo_acesso->format('d/m/Y H:i') }}
                                                </small>
                                            @else
                                                <small class="text-muted">Nunca</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Nenhum usuário possui esta role ainda.</p>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>
                            Gerenciar Usuários
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
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
                        <h4 class="text-primary">{{ $role->permissions->count() }}</h4>
                        <small class="text-muted">Permissões</small>
                    </div>
                    <div class="col-12 mb-3">
                        <h4 class="text-success">{{ $role->users->where('ativo', true)->count() }}</h4>
                        <small class="text-muted">Usuários Ativos</small>
                    </div>
                    <div class="col-12">
                        <h4 class="text-warning">{{ $role->users->where('ativo', false)->count() }}</h4>
                        <small class="text-muted">Usuários Inativos</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Sistema -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informações do Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Criada em</label>
                    <p class="fw-bold">{{ $role->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Última atualização</label>
                    <p class="fw-bold">{{ $role->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">ID</label>
                    <p class="fw-bold">{{ $role->id }}</p>
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
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Editar Role
                    </a>
                    
                    <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" class="btn btn-info">
                        <i class="bi bi-people me-1"></i>
                        Ver Usuários desta Role
                    </a>
                    
                    @if(!in_array($role->name, ['admin', 'supervisor', 'usuario']))
                        <hr>
                        
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Tem certeza que deseja excluir esta role?')">
                                <i class="bi bi-trash me-1"></i>
                                Excluir Role
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
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Voltar para Lista
    </a>
</div>
@endsection 