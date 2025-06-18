@extends('layouts.app')

@section('title', 'Equipamentos')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-cpu me-2"></i>
                        Equipamentos
                    </h1>
                    <p class="text-muted mb-0">Gerencie os equipamentos do sistema</p>
                </div>
                @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel)
                <div class="d-flex gap-2">
                    <a href="{{ route('equipamentos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Equipamento
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
                                <a class="dropdown-item" href="{{ route('equipamentos.index', ['status_operacional' => 'operando']) }}">
                                    <i class="bi bi-play-circle text-success me-2"></i>
                                    Apenas Operando
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('equipamentos.index', ['status_operacional' => 'manutencao']) }}">
                                    <i class="bi bi-tools text-warning me-2"></i>
                                    Em Manutenção
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('equipamentos.index', ['ativo' => '0']) }}">
                                    <i class="bi bi-x-circle text-danger me-2"></i>
                                    Inativos
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('equipamentos.index') }}">
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
                    <i class="bi bi-cpu fs-1 text-primary mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['total'] }}</h5>
                    <p class="card-text text-muted small">Total de Equipamentos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-play-circle fs-1 text-success mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['operando'] }}</h5>
                    <p class="card-text text-muted small">Operando</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-tools fs-1 text-warning mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['manutencao'] }}</h5>
                    <p class="card-text text-muted small">Em Manutenção</p>
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
            <form method="GET" action="{{ route('equipamentos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="busca" class="form-label">Buscar</label>
                    <input type="text" 
                           class="form-control" 
                           id="busca" 
                           name="busca" 
                           value="{{ $busca }}"
                           placeholder="Nome, código ou fabricante...">
                </div>
                
                <div class="col-md-2">
                    <label for="status_operacional" class="form-label">Status Operacional</label>
                    <select class="form-select" id="status_operacional" name="status_operacional">
                        <option value="">Todos</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $status_operacional == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="ativo" class="form-label">Status</label>
                    <select class="form-select" id="ativo" name="ativo">
                        <option value="">Todos</option>
                        <option value="1" {{ $ativo == '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ $ativo == '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="local_id" class="form-label">Local</label>
                    <select class="form-select" id="local_id" name="local_id">
                        <option value="">Todos os locais</option>
                        @foreach($locais as $local)
                            <option value="{{ $local->id }}" {{ $local_id == $local->id ? 'selected' : '' }}>
                                {{ $local->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
               
                
                <!-- Botões de ação centralizados -->
                <div class="col-12 d-flex justify-content-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('equipamentos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Equipamentos -->
    @if($equipamentos->count() > 0)
        <div class="row">
            @foreach($equipamentos as $equipamento)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-cpu me-1"></i>
                                {{ $equipamento->nome }}
                            </h6>
                            <div class="d-flex gap-1">
                                @if($equipamento->status_operacional === 'operando')
                                    <span class="badge bg-success">Operando</span>
                                @elseif($equipamento->status_operacional === 'manutencao')
                                    <span class="badge bg-warning">Manutenção</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                                
                                @if($equipamento->ativo)
                                    <span class="badge bg-primary">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted small mb-2">{{ strlen($equipamento->descricao) > 60 ? substr($equipamento->descricao, 0, 60) . '...' : $equipamento->descricao }}</p>
                            
                            @if($equipamento->codigo)
                                <p class="card-text small mb-2">
                                    <i class="bi bi-upc-scan me-1"></i>
                                    {{ $equipamento->codigo }}
                                </p>
                            @endif
                            
                            @if($equipamento->fabricante || $equipamento->modelo)
                                <p class="card-text small mb-2">
                                    <i class="bi bi-gear me-1"></i>
                                    {{ $equipamento->fabricante }}{{ $equipamento->fabricante && $equipamento->modelo ? ' - ' : '' }}{{ $equipamento->modelo }}
                                </p>
                            @endif

                            @if($equipamento->local)
                                <p class="card-text small mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ $equipamento->local->nome }}
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-file-earmark-text me-1"></i>
                                    {{ $equipamento->relatorios_count }} relatório(s)
                                </small>
                                <small class="text-muted">
                                    @if($equipamento->data_instalacao)
                                        {{ $equipamento->data_instalacao->format('d/m/Y') }}
                                    @elseif($equipamento->data_criacao)
                                        {{ $equipamento->data_criacao->format('d/m/Y') }}
                                    @else
                                        Data não informada
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <!-- Layout responsivo dos botões -->
                            <div class="d-flex flex-column gap-2">
                                <!-- Botão Ver Detalhes (sempre visível, destaque principal) -->
                                <a href="{{ route('equipamentos.show', $equipamento) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver Detalhes
                                </a>
                                
                                <!-- Botões de Ação (admin/supervisor) -->
                                @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel)
                                    <!-- Primeira linha: Editar + Ativar/Desativar -->
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('equipamentos.edit', $equipamento) }}" 
                                           class="btn btn-outline-secondary btn-sm flex-fill"
                                           title="Editar equipamento">
                                            <i class="bi bi-pencil me-1 d-none d-sm-inline"></i>
                                            <i class="bi bi-pencil d-sm-none"></i>
                                            <span class="d-none d-sm-inline">Editar</span>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('equipamentos.toggle-status', $equipamento) }}" class="flex-fill">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-outline-{{ $equipamento->ativo ? 'warning' : 'success' }} btn-sm w-100" 
                                                    title="{{ $equipamento->ativo ? 'Desativar' : 'Ativar' }} equipamento">
                                                <i class="bi bi-{{ $equipamento->ativo ? 'pause' : 'play' }} me-1 d-none d-sm-inline"></i>
                                                <i class="bi bi-{{ $equipamento->ativo ? 'pause' : 'play' }} d-sm-none"></i>
                                                <span class="d-none d-sm-inline">{{ $equipamento->ativo ? 'Desativar' : 'Ativar' }}</span>
                                                <span class="d-sm-none">{{ $equipamento->ativo ? 'Off' : 'On' }}</span>
                                            </button>
                                        </form>
                                        
                                        @if(auth()->user()->papel === 'admin' || !auth()->user()->papel)
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm flex-shrink-0"
                                                    onclick="confirmarExclusao({{ $equipamento->id }}, '{{ $equipamento->nome }}')"
                                                    title="Excluir equipamento">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($equipamentos->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <!-- Informações da paginação -->
                            <div class="mb-3 mb-md-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                <small class="text-muted me-3 mb-2 mb-sm-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mostrando {{ $equipamentos->firstItem() }} a {{ $equipamentos->lastItem() }} 
                                    de {{ $equipamentos->total() }} resultados
                                    @if($busca || $status_operacional || $ativo !== null || $local_id)
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
                                {{ $equipamentos->withQueryString()->links('pagination::bootstrap-4') }}
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
                            Mostrando {{ $equipamentos->count() }} de {{ $equipamentos->total() }} resultados
                            @if($busca || $status_operacional || $ativo !== null || $local_id)
                                <span class="badge bg-light text-dark ms-1">
                                    <i class="bi bi-funnel me-1"></i>Filtrado
                                </span>
                            @endif
                            @if($equipamentos->total() > 12)
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
            <i class="bi bi-cpu fs-1 text-muted mb-3"></i>
            <h5 class="text-muted">Nenhum equipamento encontrado</h5>
            <p class="text-muted">
                @if($busca || $status_operacional || $ativo !== null || $local_id)
                    Tente ajustar os filtros de busca.
                @else
                    Que tal criar o primeiro equipamento?
                @endif
            </p>
            @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel)
                <a href="{{ route('equipamentos.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Criar Primeiro Equipamento
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
    
    /* Paginação */
    .pagination-wrapper .pagination {
        margin: 0;
    }
    
    .pagination-wrapper .page-link {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        min-width: auto;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }
    
    .pagination-wrapper .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background-color: #6f42c1;
        border-color: #6f42c1;
        color: white;
    }
    
    .pagination-wrapper .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }
    
    /* Botões de navegação (Previous/Next) */
    .pagination-wrapper .page-item:first-child .page-link,
    .pagination-wrapper .page-item:last-child .page-link {
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
    }
    
    /* Números das páginas */
    .pagination-wrapper .page-item:not(:first-child):not(:last-child) .page-link {
        min-width: 38px;
        padding: 0.5rem 0.75rem;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .d-flex.gap-1 {
            gap: 0.25rem !important;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            min-height: 36px; /* Tamanho mínimo para touch */
        }
        
        /* Card footer com melhor espaçamento */
        .card-footer .d-flex.flex-column {
            gap: 0.5rem !important;
        }
        
        /* Botão Ver Detalhes em destaque */
        .card-footer .btn-primary {
            font-weight: 500;
            min-height: 40px;
        }
        
        /* Botões de ação menores mas tocáveis */
        .card-footer .d-flex.gap-1 .btn {
            min-height: 36px;
            font-size: 0.75rem;
        }
        
        /* Botão de excluir com largura fixa */
        .card-footer .btn-outline-danger {
            min-width: 40px;
            padding: 0.25rem 0.5rem;
        }
        
        /* Paginação mobile */
        .pagination-wrapper .page-link {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
            height: 32px;
            min-width: auto;
        }
        
        /* Botões de navegação mobile */
        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            padding: 0.375rem 0.625rem;
            font-size: 0.75rem;
        }
        
        /* Números das páginas mobile */
        .pagination-wrapper .page-item:not(:first-child):not(:last-child) .page-link {
            min-width: 32px;
            padding: 0.375rem 0.5rem;
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
    // Melhor formatação da mensagem de confirmação
    const mensagem = `⚠️ ATENÇÃO - EXCLUSÃO DE EQUIPAMENTO ⚠️\n\n` +
                    `Equipamento: "${nome}"\n` +
                    `ID: ${id}\n\n` +
                    `Esta ação irá EXCLUIR PERMANENTEMENTE o equipamento e:\n` +
                    `• Todos os dados associados\n` +
                    `• Histórico de manutenções\n` +
                    `• Relatórios vinculados\n\n` +
                    `❌ ESTA AÇÃO NÃO PODE SER DESFEITA! ❌\n\n` +
                    `Tem CERTEZA ABSOLUTA que deseja continuar?`;

    if (confirm(mensagem)) {
        // Segunda confirmação para ações críticas
        const confirmacaoFinal = `🛑 CONFIRMAÇÃO FINAL 🛑\n\n` +
                               `Você está prestes a excluir o equipamento:\n` +
                               `"${nome}"\n\n` +
                               `Digite "EXCLUIR" para confirmar a exclusão:`;
        
        const confirmacao = prompt(confirmacaoFinal);
        
        if (confirmacao === 'EXCLUIR') {
            // Criar formulário dinâmico para DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.origin + '/Larvel_relatorio/sistema-relatorios/public/equipamentos/' + id;
            
            // Token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Debug
            console.log('Form action:', form.action);
            console.log('CSRF Token:', csrfToken.value);
            
            // Method DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            // Feedback visual
            const button = document.querySelector(`button[onclick*="confirmarExclusao(${id}"]`);
            if (button) {
                button.disabled = true;
                button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Excluindo...';
                button.className = 'btn btn-outline-danger btn-sm disabled';
            }
            
            // Submeter
            document.body.appendChild(form);
            form.submit();
        } else if (confirmacao !== null) {
            alert('❌ Exclusão cancelada!\n\nTexto incorreto. Para excluir, digite exatamente: EXCLUIR');
        }
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