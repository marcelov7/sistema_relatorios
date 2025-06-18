@extends('layouts.app')

@section('title', 'Locais - Sistema de Relatórios')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-geo-alt me-2"></i>
                        Locais
                    </h1>
                    <p class="text-muted mb-0">Gerencie os locais do sistema</p>
                </div>
                @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor')
                <div class="d-flex gap-2">
                    <a href="{{ route('locais.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Local
                    </a>
                    
                    <!-- Botão dropdown para ações rápidas -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear me-1"></i>
                            Ações
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('locais.index', ['status' => '1']) }}">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Ver Apenas Ativos
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('locais.index', ['status' => '0']) }}">
                                    <i class="bi bi-x-circle text-danger me-2"></i>
                                    Ver Apenas Inativos
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('locais.index') }}">
                                    <i class="bi bi-arrow-clockwise text-primary me-2"></i>
                                    Resetar Filtros
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-geo-alt fs-1 text-primary mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['total'] }}</h5>
                    <p class="card-text text-muted small">Total de Locais</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['ativos'] }}</h5>
                    <p class="card-text text-muted small">Locais Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-x-circle fs-1 text-danger mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['inativos'] }}</h5>
                    <p class="card-text text-muted small">Locais Inativos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text fs-1 text-info mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['com_relatorios'] }}</h5>
                    <p class="card-text text-muted small">Com Relatórios</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('locais.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="busca" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="busca" name="busca" 
                           value="{{ $busca }}" placeholder="Nome, descrição ou endereço...">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
               
                
                <!-- Botões de ação centralizados -->
                <div class="col-12 d-flex justify-content-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('locais.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Locais -->
    @if($locais->count() > 0)
        <div class="row">
            @foreach($locais as $local)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $local->nome }}
                            </h6>
                            <div>
                                @if($local->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted small mb-2">{{ $local->descricao }}</p>
                            
                            @if($local->endereco)
                                <p class="card-text small mb-2">
                                    <i class="bi bi-pin-map me-1"></i>
                                    {{ $local->endereco }}
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="bi bi-file-earmark-text me-1"></i>
                                    {{ $local->relatorios_count }} relatório(s)
                                </small>
                                <small class="text-muted">
                                    {{ $local->data_criacao ? $local->data_criacao->format('d/m/Y') : 'Data não informada' }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <!-- Botão Ver Detalhes (sempre visível) -->
                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ route('locais.show', $local) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver Detalhes
                                </a>
                            </div>
                            
                            <!-- Botões de Ação (admin/supervisor) -->
                            @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor')
                                <div class="d-flex gap-1">
                                    <a href="{{ route('locais.edit', $local) }}" 
                                       class="btn btn-outline-secondary btn-sm flex-fill"
                                       title="Editar local">
                                        <i class="bi bi-pencil me-1"></i>
                                        Editar
                                    </a>
                                    
                                    <form method="POST" action="{{ route('locais.toggle-status', $local) }}" class="d-inline flex-fill">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-outline-{{ $local->ativo ? 'warning' : 'success' }} btn-sm w-100" 
                                                title="{{ $local->ativo ? 'Desativar' : 'Ativar' }} local">
                                            <i class="bi bi-{{ $local->ativo ? 'eye-slash' : 'eye' }} me-1"></i>
                                            {{ $local->ativo ? 'Desativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                    
                                    @if(auth()->user()->papel === 'admin')
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="confirmarExclusao({{ $local->id }}, '{{ $local->nome }}')"
                                                title="Excluir local">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($locais->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <!-- Informações da paginação -->
                            <div class="mb-3 mb-md-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                <small class="text-muted me-3 mb-2 mb-sm-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mostrando {{ $locais->firstItem() }} a {{ $locais->lastItem() }} 
                                    de {{ $locais->total() }} resultados
                                    @if($busca || $status !== null)
                                        <span class="badge bg-light text-dark ms-1">
                                            <i class="bi bi-funnel me-1"></i>Filtrado
                                        </span>
                                    @endif
                                </small>
                                
                                <!-- Seletor de itens por página -->
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Itens por página:</small>
                                    <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                        <option value="6" {{ request('per_page', 12) == 6 ? 'selected' : '' }}>6</option>
                                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                        <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24</option>
                                        <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Links de paginação -->
                            <div class="d-flex justify-content-center">
                                {{ $locais->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Informações quando não há paginação -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Mostrando {{ $locais->count() }} de {{ $locais->total() }} resultados
                            @if($busca || $status !== null)
                                <span class="badge bg-light text-dark ms-1">
                                    <i class="bi bi-funnel me-1"></i>Filtrado
                                </span>
                            @endif
                            @if($locais->total() > 12)
                                <span class="badge bg-primary ms-1">
                                    {{ request('per_page', 12) }} por página
                                </span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="bi bi-geo-alt fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Nenhum local encontrado</h5>
            <p class="text-muted">
                @if($busca || $status !== null)
                    Tente ajustar os filtros de busca.
                @else
                    Que tal criar o primeiro local?
                @endif
            </p>
            @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor')
                <a href="{{ route('locais.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Criar Primeiro Local
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .card-footer {
        border-top: 1px solid rgba(0,0,0,.125);
        background-color: #f8f9fa !important;
    }
    
    .btn-sm {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .d-flex.gap-1 {
            gap: 0.25rem !important;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Função para alterar itens por página
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset para primeira página
    window.location.href = url.toString();
}

// Smooth scroll para o topo quando mudar de página
document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Scroll suave para o topo
            setTimeout(() => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });
});

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

// Feedback visual para ações de toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleForms = document.querySelectorAll('form[action*="toggle-status"]');
    toggleForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button');
            button.disabled = true;
            button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Processando...';
        });
    });
});
</script>
@endpush 