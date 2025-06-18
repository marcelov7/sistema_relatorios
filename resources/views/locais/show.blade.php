@extends('layouts.app')

@section('title', ($local->nome ?? 'Local') . ' - Locais')

@section('content')
<div class="container">



    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-geo-alt me-2"></i>
                        {{ $local->nome }}
                        @if($local->ativo)
                            <span class="badge bg-success ms-2">Ativo</span>
                        @else
                            <span class="badge bg-danger ms-2">Inativo</span>
                        @endif
                    </h1>
                    <p class="text-muted mb-0">{{ $local->descricao }}</p>
                    @if($local->endereco)
                        <p class="text-muted small">
                            <i class="bi bi-pin-map me-1"></i>
                            {{ $local->endereco }}
                        </p>
                    @endif
                </div>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('locais.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>
                    
                    @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor')
                        <a href="{{ route('locais.edit', $local) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>
                            Editar Local
                        </a>
                        
                        <a href="{{ route('locais.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>
                            Novo Local
                        </a>
                        
                        <form method="POST" action="{{ route('locais.toggle-status', $local) }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-{{ $local->ativo ? 'warning' : 'success' }}" 
                                    title="{{ $local->ativo ? 'Desativar' : 'Ativar' }} local">
                                <i class="bi bi-{{ $local->ativo ? 'eye-slash' : 'eye' }} me-1"></i>
                                {{ $local->ativo ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        
                        @if(auth()->user()->papel === 'admin')
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="confirmarExclusao({{ $local->id }}, '{{ $local->nome }}')">
                                <i class="bi bi-trash me-1"></i>
                                Excluir
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <!-- Informações do Local -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informações do Local
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Nome:</label>
                        <p class="mb-0 fs-5">{{ $local->nome }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Descrição:</label>
                        <p class="mb-0">{{ $local->descricao }}</p>
                    </div>
                    
                    @if($local->endereco)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Endereço:</label>
                            <p class="mb-0">
                                <i class="bi bi-pin-map text-primary me-1"></i>
                                {{ $local->endereco }}
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Status:</label>
                        <p class="mb-0">
                            @if($local->ativo)
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Ativo
                                </span>
                            @else
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Inativo
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Data de Criação:</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar3 text-primary me-1"></i>
                            {{ $local->data_criacao ? $local->data_criacao->format('d/m/Y H:i') : 'Não informada' }}
                        </p>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted">Total de Relatórios:</label>
                        <p class="mb-0">
                            <i class="bi bi-file-earmark-text text-primary me-1"></i>
                            {{ $local->relatorios->count() }} relatório(s)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatórios Recentes -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Relatórios Recentes
                        <span class="badge bg-primary ms-2">{{ $local->relatorios->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($relatoriosRecentes && $relatoriosRecentes->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatoriosRecentes as $relatorio)
                                <div class="list-group-item px-0 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $relatorio->titulo ?? 'Título não informado' }}</h6>
                                            <p class="mb-1 text-muted small">{{ strlen($relatorio->descricao ?? 'Descrição não informada') > 80 ? substr($relatorio->descricao ?? 'Descrição não informada', 0, 80) . '...' : ($relatorio->descricao ?? 'Descrição não informada') }}</p>
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                {{ $relatorio->usuario->nome ?? 'Usuário não informado' }} • 
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $relatorio->data_criacao ? $relatorio->data_criacao->format('d/m/Y H:i') : 'Data não informada' }}
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            @switch($relatorio->status ?? 'indefinido')
                                                @case('pendente')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock me-1"></i>Pendente
                                                    </span>
                                                    @break
                                                @case('em_andamento')
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-gear me-1"></i>Em Andamento
                                                    </span>
                                                    @break
                                                @case('resolvido')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Resolvido
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($relatorio->status ?? 'Indefinido') }}</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($local->relatorios->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('relatorios.index', ['local_id' => $local->id]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver todos os {{ $local->relatorios->count() }} relatórios
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-text fs-1 text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Nenhum relatório encontrado</h6>
                            <p class="text-muted small">Este local ainda não possui relatórios registrados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.75rem;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Responsividade dos botões */
    @media (max-width: 768px) {
        .d-flex.gap-2.flex-wrap .btn {
            margin-bottom: 0.5rem;
            width: 100%;
        }
        
        .d-flex.gap-2.flex-wrap form {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function confirmarExclusao(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o local "${nome}"?\n\nEsta ação não pode ser desfeita!`)) {
        // Criar formulário dinâmico para DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/locais/${id}`;
        
        // Token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // Submeter
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
