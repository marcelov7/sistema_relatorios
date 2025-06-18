@extends('layouts.app')

@section('title', ($equipamento->nome ?? 'Equipamento') . ' - Equipamentos')

@section('content')
<div class="container">



    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-cpu me-2"></i>
                        {{ $equipamento->nome }}
                        @if($equipamento->status_operacional === 'operando')
                            <span class="badge bg-success ms-2">Operando</span>
                        @elseif($equipamento->status_operacional === 'manutencao')
                            <span class="badge bg-warning ms-2">Manutenção</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inativo</span>
                        @endif
                        
                        @if($equipamento->ativo)
                            <span class="badge bg-primary ms-1">Ativo</span>
                        @else
                            <span class="badge bg-danger ms-1">Inativo</span>
                        @endif
                    </h1>
                    <p class="text-muted mb-0">{{ $equipamento->descricao }}</p>
                    @if($equipamento->codigo)
                        <p class="text-muted small">
                            <i class="bi bi-upc-scan me-1"></i>
                            Código: {{ $equipamento->codigo }}
                        </p>
                    @endif
                </div>
                
                <!-- Botões organizados responsivamente -->
                <div class="d-flex flex-column flex-md-row gap-2">
                    <!-- Grupo 1: Navegação -->
                    <div class="d-flex gap-2 flex-fill">
                        <a href="{{ route('equipamentos.index') }}" class="btn btn-outline-secondary flex-fill flex-md-grow-0">
                            <i class="bi bi-arrow-left me-1"></i>
                            <span class="d-none d-sm-inline">Voltar</span>
                            <span class="d-sm-none">Voltar</span>
                        </a>
                    </div>
                    
                    @if(auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel)
                        <!-- Grupo 2: Ações Principais (Mobile: 2 por linha, Desktop: inline) -->
                        <div class="d-flex gap-2 flex-fill">
                            <a href="{{ route('equipamentos.edit', $equipamento) }}" class="btn btn-primary flex-fill flex-md-grow-0">
                                <i class="bi bi-pencil me-1"></i>
                                <span class="d-none d-sm-inline">Editar Equipamento</span>
                                <span class="d-sm-none">Editar</span>
                            </a>
                            
                            <a href="{{ route('equipamentos.create') }}" class="btn btn-success flex-fill flex-md-grow-0">
                                <i class="bi bi-plus-circle me-1"></i>
                                <span class="d-none d-sm-inline">Novo Equipamento</span>
                                <span class="d-sm-none">Novo</span>
                            </a>
                        </div>
                        
                        <!-- Grupo 3: Ações Secundárias -->
                        <div class="d-flex gap-2 flex-fill">
                            <form method="POST" action="{{ route('equipamentos.toggle-status', $equipamento) }}" class="flex-fill flex-md-grow-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $equipamento->ativo ? 'warning' : 'success' }} w-100" 
                                        title="{{ $equipamento->ativo ? 'Desativar' : 'Ativar' }} equipamento">
                                    <i class="bi bi-{{ $equipamento->ativo ? 'pause' : 'play' }} me-1"></i>
                                    <span class="d-none d-sm-inline">{{ $equipamento->ativo ? 'Desativar' : 'Ativar' }}</span>
                                    <span class="d-sm-none">{{ $equipamento->ativo ? 'Pausar' : 'Ativar' }}</span>
                                </button>
                            </form>
                            
                            @if(auth()->user()->papel === 'admin' || !auth()->user()->papel)
                                <button type="button" class="btn btn-outline-danger flex-fill flex-md-grow-0" 
                                        onclick="confirmarExclusao({{ $equipamento->id }}, '{{ $equipamento->nome }}')">
                                    <i class="bi bi-trash me-1"></i>
                                    <span class="d-none d-sm-inline">Excluir</span>
                                    <span class="d-sm-none">Excluir</span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informações do Equipamento -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Informações do Equipamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Nome:</label>
                        <p class="mb-0 fs-5">{{ $equipamento->nome }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Descrição:</label>
                        <p class="mb-0">{{ $equipamento->descricao }}</p>
                    </div>
                    
                    @if($equipamento->codigo)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Código:</label>
                            <p class="mb-0">
                                <i class="bi bi-upc-scan text-primary me-1"></i>
                                {{ $equipamento->codigo }}
                            </p>
                        </div>
                    @endif
                    
                    @if($equipamento->fabricante || $equipamento->modelo)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Fabricante/Modelo:</label>
                            <p class="mb-0">
                                <i class="bi bi-gear text-primary me-1"></i>
                                {{ $equipamento->fabricante }}{{ $equipamento->fabricante && $equipamento->modelo ? ' - ' : '' }}{{ $equipamento->modelo }}
                            </p>
                        </div>
                    @endif
                    
                    @if($equipamento->numero_serie)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Número de Série:</label>
                            <p class="mb-0">
                                <i class="bi bi-hash text-primary me-1"></i>
                                {{ $equipamento->numero_serie }}
                            </p>
                        </div>
                    @endif
                    
                    @if($equipamento->tipo)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Tipo:</label>
                            <p class="mb-0">
                                <i class="bi bi-tag text-primary me-1"></i>
                                {{ $equipamento->tipo }}
                            </p>
                        </div>
                    @endif
                    
                    @if($equipamento->local)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Local:</label>
                            <p class="mb-0">
                                <i class="bi bi-geo-alt text-primary me-1"></i>
                                <a href="{{ route('locais.show', $equipamento->local) }}" class="text-decoration-none">
                                    {{ $equipamento->local->nome }}
                                </a>
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Status Operacional:</label>
                        <p class="mb-0">
                            @if($equipamento->status_operacional === 'operando')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-play-circle me-1"></i>
                                    Operando
                                </span>
                            @elseif($equipamento->status_operacional === 'manutencao')
                                <span class="badge bg-warning px-3 py-2">
                                    <i class="bi bi-tools me-1"></i>
                                    Em Manutenção
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="bi bi-pause-circle me-1"></i>
                                    Inativo
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Status:</label>
                        <p class="mb-0">
                            @if($equipamento->ativo)
                                <span class="badge bg-primary px-3 py-2">
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
                    
                    @if($equipamento->data_instalacao)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Data de Instalação:</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar3 text-primary me-1"></i>
                                {{ $equipamento->data_instalacao->format('d/m/Y') }}
                            </p>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Data de Criação:</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar3 text-primary me-1"></i>
                            {{ $equipamento->data_criacao ? $equipamento->data_criacao->format('d/m/Y H:i') : 'Não informada' }}
                        </p>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted">Total de Relatórios:</label>
                        <p class="mb-0">
                            <i class="bi bi-file-earmark-text text-primary me-1"></i>
                            {{ $equipamento->relatorios->count() }} relatório(s)
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
                        <span class="badge bg-primary ms-2">{{ $equipamento->relatorios->count() }}</span>
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
                                    <div class="mt-2">
                                        <a href="{{ route('relatorios.show', $relatorio) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>
                                            Ver Relatório
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($equipamento->relatorios->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('relatorios.index', ['equipamento_id' => $equipamento->id]) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-list me-1"></i>
                                    Ver Todos os Relatórios ({{ $equipamento->relatorios->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-text fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">Nenhum relatório encontrado</h6>
                            <p class="text-muted small mb-3">Este equipamento ainda não possui relatórios associados.</p>
                            <a href="{{ route('relatorios.create', ['equipamento_id' => $equipamento->id]) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>
                                Criar Primeiro Relatório
                            </a>
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
    
    .list-group-item {
        transition: background-color 0.2s;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: #6c757d;
    }
    
    /* Layout responsivo dos botões */
    @media (max-width: 767.98px) {
        /* Mobile: Botões em grupos organizados */
        .btn {
            min-height: 44px; /* Tamanho mínimo para touch */
            font-size: 0.9rem;
        }
        
        /* Primeira linha: Voltar (largura total) */
        .d-flex.flex-column > div:first-child {
            margin-bottom: 0.5rem;
        }
        
        /* Segunda linha: Editar + Novo (50% cada) */
        .d-flex.flex-column > div:nth-child(2) {
            margin-bottom: 0.5rem;
        }
        
        /* Terceira linha: Ativar/Desativar + Excluir (50% cada) */
        .d-flex.flex-column > div:last-child {
            /* Sem margin-bottom adicional */
        }
    }
    
    @media (min-width: 768px) {
        /* Desktop: Botões inline com tamanhos automáticos */
        .flex-md-grow-0 {
            flex-grow: 0 !important;
            flex-shrink: 0;
        }
    }
    
    /* Melhorar espaçamento entre grupos de botões */
    .d-flex.flex-column.flex-md-row > div {
        min-width: 0; /* Previne overflow */
    }
    
    /* Botões com texto responsivo */
    .btn .d-none.d-sm-inline,
    .btn .d-sm-none {
        transition: opacity 0.2s;
    }
</style>
@endpush

@push('scripts')
<script>
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
