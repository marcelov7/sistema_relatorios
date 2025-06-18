@extends('admin.layout')

@section('title', 'Editar Usuário')

@php
    $pageTitle = 'Editar Usuário';
    
@endphp

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    Editar Usuário: {{ $usuario->name }}
                </h5>
                <a href="{{ route('admin.users.show', $usuario) }}" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-eye me-1"></i>
                    Visualizar
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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
                                       id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $usuario->username) }}" 
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
                                       id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cargo" class="form-label">Cargo</label>
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" 
                                       id="cargo" name="cargo" value="{{ old('cargo', $usuario->cargo) }}" 
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
                                    <option value="manutencao" {{ old('departamento', $usuario->departamento) == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                                    <option value="producao" {{ old('departamento', $usuario->departamento) == 'producao' ? 'selected' : '' }}>Produção</option>
                                    <option value="qualidade" {{ old('departamento', $usuario->departamento) == 'qualidade' ? 'selected' : '' }}>Qualidade</option>
                                    <option value="engenharia" {{ old('departamento', $usuario->departamento) == 'engenharia' ? 'selected' : '' }}>Engenharia</option>
                                    <option value="administracao" {{ old('departamento', $usuario->departamento) == 'administracao' ? 'selected' : '' }}>Administração</option>
                                    <option value="ti" {{ old('departamento', $usuario->departamento) == 'ti' ? 'selected' : '' }}>TI</option>
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
                                Alterar Senha (opcional)
                            </h6>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Deixe em branco para manter a senha atual.
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password">
                                <small class="form-text text-muted">Mínimo 8 caracteres</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation">
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
                                    <option value="admin" {{ old('role', $usuario->roles->first()?->name) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="supervisor" {{ old('role', $usuario->roles->first()?->name) == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="usuario" {{ old('role', $usuario->roles->first()?->name) == 'usuario' ? 'selected' : '' }}>Usuário</option>
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
                                    <option value="1" {{ old('ativo', $usuario->ativo) == '1' ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo', $usuario->ativo) == '0' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Telefone -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone', $usuario->telefone) }}" 
                                       placeholder="(11) 99999-9999">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Último Acesso</label>
                                <p class="form-control-plaintext">
                                    @if($usuario->ultimo_acesso)
                                        {{ $usuario->ultimo_acesso->format('d/m/Y H:i') }}
                                    @else
                                        Nunca acessou
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botões -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <a href="{{ route('admin.users.show', $usuario) }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-left me-1"></i>
                                Voltar
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info">
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
    // Máscara para telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length <= 11) {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
            value = value.replace(/(\d{2})/, '($1');
        }
        
        e.target.value = value;
    });
    
    // Validação de senha em tempo real
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');
    
    passwordConfirm.addEventListener('input', function() {
        if (password.value && password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('As senhas não coincidem');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    });
</script>
@endpush 