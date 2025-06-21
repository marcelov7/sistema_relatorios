@extends('layouts.app')

@section('title', 'Relatórios - Sistema de Relatórios')

@push('styles')
<style>
    .stats-row {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 1rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 1rem;
    }

    .relatorio-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
        overflow: hidden;
        height: 100%;
    }

    .relatorio-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    /* Cards com destaque */
    .relatorio-card.novo {
        border-color: #28a745 !important;
        box-shadow: 0 0 15px rgba(40, 167, 69, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #f8fff9 100%);
    }

    .relatorio-card.atualizado {
        border-color: #17a2b8 !important;
        box-shadow: 0 0 15px rgba(23, 162, 184, 0.2);
        background: linear-gradient(135deg, #ffffff 0%, #f8feff 100%);
    }

    /* Animação sutil para destacar */
    .relatorio-card.novo::before,
    .relatorio-card.atualizado::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
    }

    .relatorio-card.novo::before {
        background: linear-gradient(90deg, #28a745, #20c997);
    }

    .relatorio-card.atualizado::before {
        background: linear-gradient(90deg, #17a2b8, #6f42c1);
    }

    /* Badges com ícones animados */
    .badge-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        position: relative;
        border: 2px solid transparent;
    }

    .badge-icon.novo {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        animation: pulseNovo 2s infinite;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
    }

    .badge-icon.atualizado {
        background: linear-gradient(135deg, #17a2b8, #007bff);
        color: white;
        animation: pulseAtualizado 2s infinite;
        box-shadow: 0 0 10px rgba(23, 162, 184, 0.4);
    }

    /* Animações */
    @keyframes pulseNovo {
        0% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.8);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
        }
    }

    @keyframes pulseAtualizado {
        0% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(23, 162, 184, 0.4);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(23, 162, 184, 0.8);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 10px rgba(23, 162, 184, 0.4);
        }
    }

    /* Efeito de brilho adicional */
    .badge-icon::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
        opacity: 0;
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }

    /* Tooltip para os ícones */
    .badge-icon[title] {
        cursor: help;
    }

    /* Badges menores para mobile */
    @media (max-width: 768px) {
        .badge-icon {
            width: 20px;
            height: 20px;
            font-size: 10px;
        }
    }

    /* Animação de entrada suave */
    .badge-icon {
        animation: fadeInScale 0.6s ease-out;
    }

    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.5);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.2);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Efeito hover mais suave */
    .badge-icon:hover {
        transform: scale(1.15);
        transition: transform 0.2s ease;
    }

    /* Ajuste fino das animações */
    .badge-icon.novo {
        animation: fadeInScale 0.6s ease-out, pulseNovo 2s infinite 0.6s;
    }

    .badge-icon.atualizado {
        animation: fadeInScale 0.6s ease-out, pulseAtualizado 2s infinite 0.6s;
    }

    .relatorio-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .priority-indicator {
        width: 4px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
    }

    .priority-baixa { background: #28a745; }
    .priority-media { background: #ffc107; }
    .priority-alta { background: #fd7e14; }
    .priority-critica { background: #dc3545; }

    .progress-circle {
        position: relative;
        width: 60px;
        height: 60px;
    }

    .progress-circle svg {
        transform: rotate(-90deg);
    }

    .progress-circle .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Paginação personalizada */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        border-radius: 0.5rem;
        margin: 0 0.125rem;
        border: 1px solid #dee2e6;
        color: var(--primary-color);
        padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    @media (max-width: 768px) {
        .filter-card .row > div {
            margin-bottom: 0.75rem;
        }
        
        .relatorio-card {
            margin-bottom: 1rem;
        }

        .stats-row {
            padding: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
        }

        .progress-circle .progress-text {
            font-size: 0.75rem;
        }

        /* Paginação mobile */
        .pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            font-size: 0.875rem;
            padding: 0.375rem 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
        <div>
            <h2 class="mb-1 fw-bold fs-4 fs-md-2">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                Relatórios
            </h2>
            <p class="text-muted mb-0 d-none d-md-block">Gerencie todos os relatórios do sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('relatorios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                <span class="d-none d-sm-inline">Novo Relatório</span>
                <span class="d-sm-none">Novo</span>
            </a>
            <a href="{{ route('relatorios-v2.create') }}" class="btn btn-success" title="Relatório com múltiplos equipamentos">
                <i class="bi bi-gear-wide-connected me-1"></i>
                <span class="d-none d-lg-inline">Multi-Equipamento</span>
                <span class="d-lg-none d-none d-md-inline">V2</span>
                <span class="d-md-none">V2</span>
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="stats-row">
        <div class="row text-center">
            <div class="col-6 col-md-3">
                <div class="fw-bold text-primary fs-4">{{ $stats['total'] }}</div>
                <small class="text-muted">Total</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-warning fs-4">{{ $stats['pendentes'] }}</div>
                <small class="text-muted">Pendentes</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-info fs-4">{{ $stats['em_andamento'] }}</div>
                <small class="text-muted">Em Andamento</small>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-bold text-success fs-4">{{ $stats['resolvidos'] }}</div>
                <small class="text-muted">Resolvidos</small>
            </div>
        </div>
    </div>

    <!-- Destaques: Novos e Atualizados -->
    @if($novosRelatorios->count() > 0 || $atualizadosRecentemente->count() > 0)
    <div class="row mb-3 mb-md-4">
        @if($novosRelatorios->count() > 0)
        <div class="col-lg-6 mb-3 mb-lg-0">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>
                        Novos Relatórios (24h)
                        <span class="badge bg-light text-success ms-2">{{ $novosRelatorios->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-2">
                    @foreach($novosRelatorios as $relatorio)
                                         <div class="d-flex align-items-center py-2 border-bottom border-light">
                         <div class="priority-indicator priority-{{ $relatorio->prioridade }}" style="width: 3px; height: 30px; margin-right: 0.5rem; border-radius: 2px;"></div>
                         <span class="badge-icon novo me-2" style="width: 18px; height: 18px; font-size: 9px;" title="Novo">
                             <i class="bi bi-star-fill"></i>
                         </span>
                         <div class="flex-grow-1">
                             <div class="fw-semibold small">{{ strlen($relatorio->titulo) > 25 ? substr($relatorio->titulo, 0, 25) . '...' : $relatorio->titulo }}</div>
                             <small class="text-muted">
                                 <i class="bi bi-person me-1"></i>{{ $relatorio->usuario->name }} • 
                                 <i class="bi bi-clock me-1"></i>{{ $relatorio->data_criacao->diffForHumans() }}
                             </small>
                         </div>
                         <div>
                             <span class="badge {{ $relatorio->status_badge }} rounded-pill small">{{ $relatorio->status_label }}</span>
                         </div>
                         <div class="ms-2">
                             <a href="{{ route('relatorios.show', $relatorio) }}" class="btn btn-sm btn-outline-success">
                                 <i class="bi bi-eye"></i>
                             </a>
                         </div>
                     </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($atualizadosRecentemente->count() > 0)
        <div class="col-lg-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-arrow-repeat me-2"></i>
                        Atualizados Recentemente (24h)
                        <span class="badge bg-light text-info ms-2">{{ $atualizadosRecentemente->count() }}</span>
                    </h6>
                </div>
                <div class="card-body p-2">
                    @foreach($atualizadosRecentemente as $relatorio)
                                         <div class="d-flex align-items-center py-2 border-bottom border-light">
                         <div class="priority-indicator priority-{{ $relatorio->prioridade }}" style="width: 3px; height: 30px; margin-right: 0.5rem; border-radius: 2px;"></div>
                         <span class="badge-icon atualizado me-2" style="width: 18px; height: 18px; font-size: 9px;" title="Atualizado">
                             <i class="bi bi-arrow-repeat"></i>
                         </span>
                         <div class="flex-grow-1">
                             <div class="fw-semibold small">{{ strlen($relatorio->titulo) > 25 ? substr($relatorio->titulo, 0, 25) . '...' : $relatorio->titulo }}</div>
                             <small class="text-muted">
                                 <i class="bi bi-person me-1"></i>{{ $relatorio->usuario->name }} • 
                                 <i class="bi bi-clock me-1"></i>{{ $relatorio->data_atualizacao->diffForHumans() }}
                             </small>
                         </div>
                         <div>
                             <span class="badge {{ $relatorio->status_badge }} rounded-pill small">{{ $relatorio->status_label }}</span>
                         </div>
                         <div class="ms-2">
                             <a href="{{ route('relatorios.show', $relatorio) }}" class="btn btn-sm btn-outline-info">
                                 <i class="bi bi-eye"></i>
                             </a>
                         </div>
                     </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Filtros Rápidos -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <small class="text-muted me-2">
                    <i class="bi bi-funnel me-1"></i>Filtros rápidos:
                </small>
                <a href="{{ route('relatorios.index', ['novo' => '1']) }}" 
                   class="btn btn-sm {{ request('novo') == '1' ? 'btn-success' : 'btn-outline-success' }}">
                    <i class="bi bi-star-fill me-1"></i>Novos
                    @if($novosRelatorios->count() > 0)
                        <span class="badge bg-light text-success ms-1">{{ $novosRelatorios->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('relatorios.index', ['atualizado' => '1']) }}" 
                   class="btn btn-sm {{ request('atualizado') == '1' ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="bi bi-arrow-repeat me-1"></i>Atualizados
                    @if($atualizadosRecentemente->count() > 0)
                        <span class="badge bg-light text-info ms-1">{{ $atualizadosRecentemente->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('relatorios.index', ['prioridade' => 'critica']) }}" 
                   class="btn btn-sm {{ request('prioridade') == 'critica' ? 'btn-danger' : 'btn-outline-danger' }}">
                    <i class="bi bi-exclamation-triangle me-1"></i>Críticos
                </a>
                <a href="{{ route('relatorios.index', ['status' => 'pendente']) }}" 
                   class="btn btn-sm {{ request('status') == 'pendente' ? 'btn-warning' : 'btn-outline-warning' }}">
                    <i class="bi bi-clock me-1"></i>Pendentes
                </a>
                @if(request()->hasAny(['novo', 'atualizado', 'search', 'status', 'prioridade', 'usuario_id', 'data_inicio', 'data_fim']))
                    <a href="{{ route('relatorios.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                        <i class="bi bi-x-circle me-1"></i>Limpar filtros
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card filter-card mb-3 mb-md-4">
        <div class="card-body">
            <form method="GET" action="{{ route('relatorios.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="search" class="form-label fw-semibold">
                            <i class="bi bi-search me-1"></i>Pesquisar
                        </label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Título ou descrição...">
                    </div>
                    
                    <div class="col-6 col-md-6 col-lg-2">
                        <label for="status" class="form-label fw-semibold">
                            <i class="bi bi-flag me-1"></i>Status
                        </label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos</option>
                            @foreach($statusOptions as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-6 col-md-6 col-lg-2">
                        <label for="prioridade" class="form-label fw-semibold">
                            <i class="bi bi-exclamation-triangle me-1"></i>Prioridade
                        </label>
                        <select class="form-select" id="prioridade" name="prioridade">
                            <option value="">Todas</option>
                            @foreach($prioridadeOptions as $key => $label)
                                <option value="{{ $key }}" {{ request('prioridade') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-md-6 col-lg-2">
                        <label for="usuario_id" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Usuário
                        </label>
                        <select class="form-select" id="usuario_id" name="usuario_id">
                            <option value="">Todos</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ request('usuario_id') == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-6 col-md-12 col-lg-3 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-search me-1"></i>
                                <span class="d-none d-sm-inline">Filtrar</span>
                            </button>
                            <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($relatorios->count() > 0)
        <!-- Desktop Table -->
        <div class="card d-none d-lg-block">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Lista de Relatórios
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Título</th>
                                <th>Status</th>
                                <th>Prioridade</th>
                                <th>Progresso</th>
                                <th>Criado por</th>
                                <th>Data</th>
                                <th class="text-center" width="120">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($relatorios as $relatorio)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="priority-indicator priority-{{ $relatorio->prioridade }}" style="width: 4px; height: 40px; margin-right: 0.75rem; border-radius: 2px;"></div>
                                        <div>
                                            <div class="fw-semibold d-flex align-items-center">
                                                {{ strlen($relatorio->titulo) > 50 ? substr($relatorio->titulo, 0, 50) . '...' : $relatorio->titulo }}
                                                @if($relatorio->is_novo)
                                                    <span class="badge-icon novo ms-2" title="Novo - Criado há {{ $relatorio->data_criacao->diffForHumans() }}">
                                                        <i class="bi bi-star-fill"></i>
                                                    </span>
                                                @elseif($relatorio->is_atualizado_recentemente)
                                                    <span class="badge-icon atualizado ms-2" title="Atualizado - Modificado há {{ $relatorio->data_atualizacao->diffForHumans() }}">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ strlen($relatorio->descricao) > 80 ? substr($relatorio->descricao, 0, 80) . '...' : $relatorio->descricao }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $relatorio->status_badge }} rounded-pill">
                                        {{ $relatorio->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $relatorio->prioridade_badge }} rounded-pill">
                                        {{ $relatorio->prioridade_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 8px;">
                                            <div class="progress-bar" style="width: {{ $relatorio->progresso }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $relatorio->progresso }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-2" style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                                            {{ strtoupper(substr($relatorio->usuario->name, 0, 1)) }}
                                        </div>
                                        <small>{{ $relatorio->usuario->name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $relatorio->data_ocorrencia->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('relatorios.show', $relatorio) }}" 
                                           class="btn btn-sm btn-outline-info" title="Visualizar">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($relatorio->podeSerEditado() && ($relatorio->usuario_id == auth()->id() || hasRole(['admin', 'supervisor'])))
                                            <a href="{{ route('relatorios.edit', $relatorio) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="d-lg-none">
            @foreach($relatorios as $relatorio)
            <div class="card relatorio-card position-relative @if($relatorio->is_novo) novo @elseif($relatorio->is_atualizado_recentemente) atualizado @endif">
                <div class="priority-indicator priority-{{ $relatorio->prioridade }}"></div>
                
                                <div class="relatorio-header">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <h6 class="card-title mb-0 me-2">{{ strlen($relatorio->titulo) > 40 ? substr($relatorio->titulo, 0, 40) . '...' : $relatorio->titulo }}</h6>
                                @if($relatorio->is_novo)
                                    <span class="badge-icon novo" title="Novo - Criado há {{ $relatorio->data_criacao->diffForHumans() }}">
                                        <i class="bi bi-star-fill"></i>
                                    </span>
                                @elseif($relatorio->is_atualizado_recentemente)
                                    <span class="badge-icon atualizado" title="Atualizado - Modificado há {{ $relatorio->data_atualizacao->diffForHumans() }}">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="progress-circle">
                            <svg width="50" height="50">
                                <circle cx="25" cy="25" r="20" stroke="#e9ecef" stroke-width="4" fill="none"/>
                                <circle cx="25" cy="25" r="20" stroke="var(--primary-color)" stroke-width="4" 
                                        fill="none" stroke-dasharray="{{ 2 * 3.14159 * 20 }}" 
                                        stroke-dashoffset="{{ 2 * 3.14159 * 20 * (1 - $relatorio->progresso / 100) }}"/>
                            </svg>
                            <div class="progress-text">{{ $relatorio->progresso }}%</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mb-2">
                        <span class="badge {{ $relatorio->status_badge }} small">{{ $relatorio->status_label }}</span>
                        <span class="badge {{ $relatorio->prioridade_badge }} small">{{ $relatorio->prioridade_label }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <p class="card-text small text-muted mb-3">
                        {{ strlen($relatorio->descricao) > 100 ? substr($relatorio->descricao, 0, 100) . '...' : $relatorio->descricao }}
                    </p>
                    
                    <div class="row small">
                        <div class="col-6">
                            <div class="text-muted">Criado por:</div>
                            <div class="fw-semibold">{{ $relatorio->usuario->name }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="text-muted">Data:</div>
                            <div class="fw-semibold">{{ $relatorio->data_ocorrencia->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('relatorios.show', $relatorio) }}" 
                           class="btn btn-outline-info btn-sm flex-grow-1">
                            <i class="bi bi-eye me-1"></i>Ver
                        </a>
                        
                        @if($relatorio->podeSerEditado() && ($relatorio->usuario_id == auth()->id() || hasRole(['admin', 'supervisor'])))
                            <a href="{{ route('relatorios.edit', $relatorio) }}" 
                               class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="bi bi-pencil me-1"></i>Editar
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($relatorios->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                                                         <!-- Informações da paginação -->
                             <div class="mb-3 mb-md-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                 <small class="text-muted me-3 mb-2 mb-sm-0">
                                     <i class="bi bi-info-circle me-1"></i>
                                     Mostrando {{ $relatorios->firstItem() }} a {{ $relatorios->lastItem() }} 
                                     de {{ $relatorios->total() }} resultados
                                     @if(request()->hasAny(['search', 'status', 'prioridade', 'usuario_id']))
                                         <span class="badge bg-light text-dark ms-1">
                                             <i class="bi bi-funnel me-1"></i>Filtrado
                                         </span>
                                     @endif
                                 </small>
                                 
                                 <!-- Seletor de itens por página -->
                                 <div class="d-flex align-items-center">
                                     <small class="text-muted me-2">Itens por página:</small>
                                     <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                         <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                                         <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                         <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                                         <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                     </select>
                                 </div>
                             </div>
                            
                            <!-- Links de paginação -->
                            <div class="d-flex justify-content-center">
                                {{ $relatorios->withQueryString()->links('pagination::bootstrap-4') }}
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
                            Mostrando {{ $relatorios->count() }} de {{ $relatorios->total() }} resultados
                            @if(request()->hasAny(['search', 'status', 'prioridade', 'usuario_id']))
                                <span class="badge bg-light text-dark ms-1">
                                    <i class="bi bi-funnel me-1"></i>Filtrado
                                </span>
                            @endif
                            @if($relatorios->total() > 10)
                                <span class="badge bg-primary ms-1">
                                    {{ request('per_page', 10) }} por página
                                </span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">Nenhum relatório encontrado</h4>
                <p class="text-muted">Não encontramos relatórios com os filtros aplicados.</p>
                <div class="mt-3">
                    <a href="{{ route('relatorios.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-clockwise me-1"></i>Limpar Filtros
                    </a>
                    <a href="{{ route('relatorios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Novo Relatório
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit filtros quando mudarem
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('#status, #prioridade, #usuario_id');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Submit no Enter para o campo de pesquisa
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filterForm').submit();
            }
        });
    });

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
                // Adicionar loading visual
                const icon = document.createElement('i');
                icon.className = 'bi bi-arrow-clockwise me-1';
                icon.style.animation = 'spin 1s linear infinite';
                
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

    // Inicializar tooltips do Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            placement: 'top',
            trigger: 'hover'
        });
    });

    // Efeito de click nos badges animados
    const badgeIcons = document.querySelectorAll('.badge-icon');
    badgeIcons.forEach(badge => {
        badge.addEventListener('click', function() {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Animação de loading
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush 