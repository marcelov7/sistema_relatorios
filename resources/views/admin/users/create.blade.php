@extends('admin.layout')

@section('title', 'Novo Usuário')

@php
    $pageTitle = 'Novo Usuário';
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    Cadastrar Novo Usuário
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Informações Básicas -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-person me-1"></i>
                                Informações Básicas
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Ex: João da Silva">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" 
                                       placeholder="Ex: joao.silva">
                                <small class="form-text text-muted">Usado para login (opcional)</small>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="joao.silva@empresa.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone') }}" 
                                       placeholder="(11) 99999-9999">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" 
                                       id="cargo" name="cargo" value="{{ old('cargo') }}" 
                                       placeholder="Ex: Técnico em Manutenção">
                                @error('cargo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="departamento" class="form-label">Departamento</label>
                                <select class="form-select @error('departamento') is-invalid @enderror" 
                                        id="departamento" name="departamento">
                                    <option value="">Selecione um departamento</option>
                                    @foreach($departamentos as $key => $value)
                                        <option value="{{ $key }}" {{ old('departamento') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departamento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Informações de Acesso -->
                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-key me-1"></i>
                                Informações de Acesso
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="bi bi-eye" id="passwordConfirmIcon"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Digite a senha novamente</small>
                            </div>
                        </div>
                        
                        <!-- Permissões e Status -->
                        <div class="col-12 mt-3">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-shield-lock me-1"></i>
                                Permissões e Status
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role/Função *</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Selecione uma role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ativo" class="form-label">Status</label>
                                <select class="form-select @error('ativo') is-invalid @enderror" 
                                        id="ativo" name="ativo">
                                    <option value="1" {{ old('ativo', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
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
                                Salvar Usuário
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
    // Auto-gerar username baseado no nome
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    
    nameInput.addEventListener('input', function() {
        if (!usernameInput.value || usernameInput.dataset.autoGenerated === 'true') {
            let username = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // Remove acentos
                .replace(/\s+/g, '.') // Substitui espaços por pontos
                .replace(/[^a-z0-9.]/g, '') // Remove caracteres especiais
                .replace(/\.+/g, '.') // Remove pontos duplicados
                .replace(/^\.+|\.+$/g, ''); // Remove pontos do início e fim
            
            usernameInput.value = username;
            usernameInput.dataset.autoGenerated = 'true';
        }
    });
    
    // Marcar como editado manualmente se o usuário alterar
    usernameInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
    
    // Toggle para mostrar/ocultar senha
    function setupPasswordToggle(buttonId, inputId, iconId) {
        const toggleButton = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        const passwordIcon = document.getElementById(iconId);
        
        if (toggleButton && passwordInput && passwordIcon) {
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    passwordIcon.className = 'bi bi-eye-slash';
                } else {
                    passwordIcon.className = 'bi bi-eye';
                }
            });
        }
    }
    
    setupPasswordToggle('togglePassword', 'password', 'passwordIcon');
    setupPasswordToggle('togglePasswordConfirm', 'password_confirmation', 'passwordConfirmIcon');
    
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            }
            e.target.value = value;
        });
    }
    
    // Validação em tempo real da confirmação de senha
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordInput = document.getElementById('password');
    
    if (passwordConfirmInput && passwordInput) {
        function validatePasswordMatch() {
            if (passwordConfirmInput.value && passwordInput.value) {
                if (passwordConfirmInput.value !== passwordInput.value) {
                    passwordConfirmInput.setCustomValidity('As senhas não coincidem');
                    passwordConfirmInput.classList.add('is-invalid');
                } else {
                    passwordConfirmInput.setCustomValidity('');
                    passwordConfirmInput.classList.remove('is-invalid');
                }
            }
        }
        
        passwordConfirmInput.addEventListener('input', validatePasswordMatch);
        passwordInput.addEventListener('input', validatePasswordMatch);
    }
});
</script>
@endpush 