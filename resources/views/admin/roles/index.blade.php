@extends('admin.layout')

@section('title', 'Roles & Permissões')

@php
    $pageTitle = 'Roles & Permissões';
    $pageActions = '<a href="' . route('admin.roles.create') . '" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>
                        Nova Role
                    </a>';

@endphp

@section('content')
<div class="row">
    <!-- Roles -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Roles do Sistema
                </h5>
            </div>
            <div class="card-body">
                @if($roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Usuários</th>
                                    <th>Permissões</th>
                                    <th>Status</th>
                                    <th>Criado</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-check me-2 text-primary"></i>
                                            <div>
                                                <div class="fw-bold text-capitalize">{{ $role->name }}</div>
                                                <small class="text-muted">{{ $role->guard_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->users->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $role->permissions->count() }}</span>
                                    </td>
                                    <td>
                                        @if(isset($role->ativo) && !$role->ativo)
                                            <span class="badge bg-warning">Inativa</span>
                                        @else
                                            <span class="badge bg-success">Ativa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $role->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.roles.show', $role) }}" 
                                               class="btn btn-sm btn-outline-info" title="Visualizar">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.roles.edit', $role) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if(!in_array($role->name, ['admin', 'supervisor', 'usuario']))
                                                <form action="{{ route('admin.roles.destroy', $role) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            title="Excluir"
                                                            onclick="return confirm('Tem certeza que deseja excluir esta role?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="btn btn-sm btn-outline-secondary disabled" title="Role do sistema">
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
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-shield-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Nenhuma role encontrada.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissões -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    Permissões do Sistema
                </h5>
            </div>
            <div class="card-body">
                @if($permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Permissão</th>
                                    <th>Roles</th>
                                    <th>Criado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-unlock me-2 text-success"></i>
                                            <div>
                                                <div class="fw-bold">{{ $permission->name }}</div>
                                                <small class="text-muted">{{ $permission->guard_name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $permission->roles->count() }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $permission->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-key text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Nenhuma permissão encontrada.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detalhes das Roles -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Matriz de Permissões por Role
                </h5>
            </div>
            <div class="card-body">
                @if($roles->count() > 0 && $permissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Permissão</th>
                                    @foreach($roles as $role)
                                        <th class="text-center text-capitalize">{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                <tr>
                                    <td class="fw-bold">{{ $permission->name }}</td>
                                    @foreach($roles as $role)
                                        <td class="text-center">
                                            @if($role->hasPermissionTo($permission->name))
                                                <i class="bi bi-check-circle text-success" title="Permitido"></i>
                                            @else
                                                <i class="bi bi-x-circle text-danger" title="Negado"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-table text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Matriz indisponível - Execute o seeder de permissões.</p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Execute: <code>php artisan db:seed --class=RolePermissionSeeder</code>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas Resumo -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $roles->count() }}</h3>
                <p class="mb-0">Roles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $permissions->count() }}</h3>
                <p class="mb-0">Permissões</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $totalUsuariosComRoles }}</h3>
                <p class="mb-0">Usuários com Roles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>{{ $usuariosSemRoles }}</h3>
                <p class="mb-0">Usuários sem Roles</p>
            </div>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Ações do Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <button type="button" class="btn btn-primary btn-lg w-100" onclick="executarSeeder()">
                            <i class="bi bi-arrow-clockwise d-block mb-2" style="font-size: 2rem;"></i>
                            Recriar Permissions
                        </button>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-people d-block mb-2" style="font-size: 2rem;"></i>
                            Gerenciar Usuários
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-info btn-lg w-100">
                            <i class="bi bi-speedometer2 d-block mb-2" style="font-size: 2rem;"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function executarSeeder() {
    if (confirm('Isso irá recriar todas as permissões e roles. Continuar?')) {
        // Aqui você pode implementar uma chamada AJAX para executar o seeder
        // ou redirecionar para uma rota específica
        alert('Funcionalidade será implementada em breve!');
    }
}
</script>
@endpush 