@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header da Página -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1 fw-bold">
                        <i class="bi bi-person-circle me-2"></i>
                        Meu Perfil
                    </h2>
                    <p class="text-muted mb-0">Gerencie suas informações pessoais e configurações de conta</p>
                </div>
            </div>

            <div class="row">
                <!-- Informações do Perfil -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-lines-fill me-2"></i>
                                Informações Pessoais
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nome Completo</label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Nome de Usuário</label>
                                        <input type="text" 
                                               class="form-control @error('username') is-invalid @enderror" 
                                               id="username" 
                                               name="username" 
                                               value="{{ old('username', $user->username) }}"
                                               placeholder="Ex: joao.silva">
                                        <div class="form-text">Opcional. Use apenas letras, números, pontos, hífens e sublinhados.</div>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="departamento" class="form-label">Departamento</label>
                                        <input type="text" 
                                               class="form-control @error('departamento') is-invalid @enderror" 
                                               id="departamento" 
                                               name="departamento" 
                                               value="{{ old('departamento', $user->departamento) }}"
                                               placeholder="Ex: TI, Manutenção, Operações">
                                        @error('departamento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Alterar Senha -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-lock me-2"></i>
                                Alterar Senha
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('profile.password.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Senha Atual</label>
                                    <input type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Nova Senha</label>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required>
                                        <div class="form-text">Mínimo de 8 caracteres.</div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Alterar Senha
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar com Informações -->
                <div class="col-lg-4">
                    <!-- Informações da Conta -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Informações da Conta
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Cargo/Função</small>
                                <div class="fw-semibold">{{ $user->getRoleNameAttribute() }}</div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Status da Conta</small>
                                <div>
                                    @if($user->ativo)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Ativa
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Inativa
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Membro desde</small>
                                <div class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>

                            @if($user->ultimo_acesso)
                            <div class="mb-0">
                                <small class="text-muted">Último acesso</small>
                                <div class="fw-semibold">{{ $user->ultimo_acesso->format('d/m/Y H:i') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Avatar -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-person-circle me-2"></i>
                                Avatar
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar-large mb-3" style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #6f42c1, #8b5cf6); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="bi bi-person-circle text-white" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 