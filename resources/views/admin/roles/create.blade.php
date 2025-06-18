@extends('admin.layout')

@section('title', 'Nova Role')

@php
    $pageTitle = 'Nova Role';
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-plus me-2"></i>
                    Criar Nova Role
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
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
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required 
                                       placeholder="Ex: operador">
                                <small class="form-text text-muted">Apenas letras minúsculas, sem espaços</small>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Nome de Exibição</label>
                                <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                       id="display_name" name="display_name" value="{{ old('display_name') }}" 
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
                                          placeholder="Descreva as responsabilidades desta role...">{{ old('description') }}</textarea>
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
                            </h6>
                        </div>
                        
                        <div class="col-12">
                            @if($permissions->count() > 0)
                                <div class="row">
                                    @php
                                        $permissionGroups = $permissions->groupBy(function($permission) {
                                            return explode('_', $permission->name)[0];
                                        });
                                    @endphp
                                    
                                    @foreach($permissionGroups as $group => $groupPermissions)
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0 text-capitalize">
                                                        <i class="bi bi-folder me-1"></i>
                                                        {{ ucfirst($group) }}
                                                        <div class="form-check form-switch float-end">
                                                            <input class="form-check-input group-toggle" 
                                                                   type="checkbox" 
                                                                   data-group="{{ $group }}">
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
                                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                    </div>
                    
                    <!-- Botões -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Voltar
                        </a>
                        
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                Criar Role
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
        });
    });
    
    // Update group toggle when individual permissions change
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const group = this.dataset.group;
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
        });
    });
    
    // Convert role name to lowercase and remove spaces
    document.getElementById('name').addEventListener('input', function() {
        this.value = this.value.toLowerCase().replace(/[^a-z0-9_]/g, '');
    });
});
</script>
@endpush 