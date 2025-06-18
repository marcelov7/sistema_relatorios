@extends('layouts.app')

@section('title', 'Editar Local: ' . $local->nome)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit me-2"></i>
                        Editar Local
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('locais.index') }}">Locais</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('locais.show', $local) }}">{{ $local->nome }}</a>
                            </li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('locais.show', $local) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar
                    </a>
                    <a href="{{ route('locais.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>
                        Lista
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Dados do Local
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('locais.update', $local) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- Nome -->
                                    <div class="col-md-8 mb-3">
                                        <label for="nome" class="form-label">
                                            Nome do Local <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('nome') is-invalid @enderror" 
                                               id="nome" 
                                               name="nome" 
                                               value="{{ old('nome', $local->nome) }}"
                                               required
                                               maxlength="255"
                                               placeholder="Ex: Fábrica Principal, Escritório Central...">
                                        @error('nome')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="ativo" class="form-label">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('ativo') is-invalid @enderror" 
                                                id="ativo" 
                                                name="ativo" 
                                                required>
                                            <option value="1" {{ old('ativo', $local->ativo) == '1' ? 'selected' : '' }}>
                                                Ativo
                                            </option>
                                            <option value="0" {{ old('ativo', $local->ativo) == '0' ? 'selected' : '' }}>
                                                Inativo
                                            </option>
                                        </select>
                                        @error('ativo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div class="mb-3">
                                    <label for="descricao" class="form-label">
                                        Descrição <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                              id="descricao" 
                                              name="descricao" 
                                              rows="4"
                                              required
                                              placeholder="Descreva as características e finalidade deste local...">{{ old('descricao', $local->descricao) }}</textarea>
                                    @error('descricao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Endereço -->
                                <div class="mb-4">
                                    <label for="endereco" class="form-label">
                                        Endereço
                                        <small class="text-muted">(opcional)</small>
                                    </label>
                                    <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                              id="endereco" 
                                              name="endereco" 
                                              rows="3"
                                              maxlength="500"
                                              placeholder="Endereço completo do local (rua, número, bairro, cidade...)">{{ old('endereco', $local->endereco) }}</textarea>
                                    @error('endereco')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        O endereço ajuda na identificação e localização do local.
                                    </div>
                                </div>

                                <!-- Informações sobre relatórios vinculados -->
                                @if($local->relatorios()->count() > 0)
                                <div class="alert alert-warning" role="alert">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Atenção
                                    </h6>
                                    <p class="mb-0">
                                        Este local possui <strong>{{ $local->relatorios()->count() }} relatório(s)</strong> vinculado(s). 
                                        Se você desativar este local, ele não aparecerá mais como opção ao criar novos relatórios.
                                    </p>
                                </div>
                                @endif

                                <!-- Informações importantes -->
                                <div class="alert alert-info" role="alert">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informações Importantes
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Os locais são usados para organizar relatórios e equipamentos</li>
                                        <li>Apenas locais ativos aparecem na seleção durante a criação de relatórios</li>
                                        <li>As alterações não afetam relatórios já existentes</li>
                                    </ul>
                                </div>

                                <!-- Botões -->
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('locais.show', $local) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>
                                            Cancelar
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        @if(hasRole('admin') && $local->podeSerExcluido())
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluir">
                                            <i class="fas fa-trash me-2"></i>
                                            Excluir
                                        </button>
                                        @endif
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            Salvar Alterações
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
@if(hasRole('admin') && $local->podeSerExcluido())
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcluirLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o local <strong>"{{ $local->nome }}"</strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta ação não pode ser desfeita!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <form action="{{ route('locais.destroy', $local) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Sim, Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize do textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            autoResize(this);
        });
        
        // Ajuste inicial
        autoResize(textarea);
    });
    
    // Validação em tempo real
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    });
});
</script>
@endpush 