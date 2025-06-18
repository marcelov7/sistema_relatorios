@extends('admin.layout')

@section('title', 'Editar Role')

@php
    $pageTitle = 'Editar Role';
    
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    Editar Role: {{ ucfirst($role->name) }}
                </h5>
                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-eye me-1"></i>
                    Visualizar
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Informações da Role
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome da Role *</label>
                                @if(in_array($role->name, ['admin', 'supervisor', 'usuario']))
                                    <input type="text" class="form-control" value="{{ $role->name }}" disabled>
                                    <input type="hidden" name="name" value="{{ $role->name }}">
                                    <small class="form-text text-muted">Role do sistema - não pode ser alterada</small>
                                @else
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $role->name) }}" required 
                                           placeholder="Ex: operador">
                                    <small class="form-text text-muted">Apenas letras minúsculas, sem espaços</small>
                                @endif
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Nome de Exibição</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" name="display_name" 
                                       value="{{ old('display_name', $role->display_name ?? ucfirst($role->name)) }}" 
                                       placeholder="Ex: Operador">
                                <small class="form-text text-muted">Nome amigável para exibição</small>
                                @error('display_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Descreva as responsabilidades desta role...">{{ old('description', $role->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Permissões -->
                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-key me-1"></i>
                                Permissões
                                <span class="badge bg-info ms-2">{{ $role->permissions->count() }} selecionadas</span>
                            </h6>
                        </div>
                        
                        <div class="col-12">
                            @if($permissions->count() > 0)
                                <div class="row">
                                    @php
                                        $permissionGroups = $permissions->groupBy(function($permission) {
                                            return explode('_', $permission->name)[0];
                                        });
                                        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
                                    @endphp
                                    
                                    @foreach($permissionGroups as $group => $groupPermissions)
                                        @php
                                            $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
                                            $selectedInGroup = array_intersect($rolePermissionIds, $groupPermissionIds);
                                        @endphp
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0 text-capitalize">
                                                        <i class="bi bi-folder me-1"></i>
                                                        {{ ucfirst($group) }}
                                                        <span class="badge bg-secondary ms-2">{{ count($selectedInGroup) }}/{{ count($groupPermissionIds) }}</span>
                                                        <div class="form-check form-switch float-end">
                                                            <input class="form-check-input group-toggle" 
                                                                   type="checkbox" 
                                                                   data-group="{{ $group }}"
                                                                   {{ count($selectedInGroup) === count($groupPermissionIds) ? 'checked' : '' }}>
                                                            <label class="form-check-label text-muted">
                                                                Selecionar todos
                                                            </label>
                                                        </div>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    @foreach($groupPermissions as $permission)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}" 
                                                                   id="permission_{{ $permission->id }}"
                                                                   data-group="{{ $group }}"
                                                                   {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @error('permissions')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Nenhuma permissão disponível. Execute primeiro o seeder de permissões.
                                </div>
                            @endif
                        </div>
                        
                        <!-- Informações Adicionais -->
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Usuários com esta Role</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info">{{ $role->users->count() }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Criada em</label>
                                        <p class="form-control-plaintext">
                                            {{ $role->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-left me-1"></i>
                                Voltar
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-info">
                                <i class="bi bi-list me-1"></i>
                                Lista
                            </a>
                        </div>
                        
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Restaurar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle group permissions
    document.querySelectorAll('.group-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const group = this.dataset.group;
            const checkboxes = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
            
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = toggle.checked;
            });
            
            updateGroupCounter(group);
        });
    });
    
    // Update group toggle when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const group = this.dataset.group;
            updateGroupToggle(group);
            updateGroupCounter(group);
        });
    });
    
    function updateGroupToggle(group) {
        const groupCheckboxes = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
        const groupToggle = document.querySelector(`input[data-group="${group}"].group-toggle`);
        
        const checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
        const totalCount = groupCheckboxes.length;
        
        if (checkedCount === 0) {
            groupToggle.checked = false;
            groupToggle.indeterminate = false;
        } else if (checkedCount === totalCount) {
            groupToggle.checked = true;
            groupToggle.indeterminate = false;
        } else {
            groupToggle.checked = false;
            groupToggle.indeterminate = true;
        }
    }
    
    function updateGroupCounter(group) {
        const groupCheckboxes = document.querySelectorAll(`input[data-group="${group}"].permission-checkbox`);
        const badge = document.querySelector(`[data-group="${group}"]`).closest('.card-header').querySelector('.badge');
        
        const checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
        const totalCount = groupCheckboxes.length;
        
        badge.textContent = `${checkedCount}/${totalCount}`;
    }
    
    // Convert role name to lowercase and remove spaces (only for custom roles)
    const nameInput = document.getElementById('name');
    if (nameInput && !nameInput.disabled) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
        });
    }
    
    // Initialize group toggles
    document.querySelectorAll('.group-toggle').forEach(function(toggle) {
        updateGroupToggle(toggle.dataset.group);
    });
});
</script>
@endpush 