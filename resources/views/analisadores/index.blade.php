@extends('layouts.app')

@section('title', 'Analisadores')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-gear-wide-connected me-2"></i>
                        Analisadores
                    </h1>
                    <p class="text-muted mb-0">Gerencie os analisadores do sistema</p>
                </div>
                <div>
                    <a href="{{ route('analisadores.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Analisador
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-gear-wide-connected fs-1 text-primary mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['total'] }}</h5>
                    <p class="card-text text-muted small">Total</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['ativos'] }}</h5>
                    <p class="card-text text-muted small">Ativos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-x-circle fs-1 text-danger mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['inativos'] }}</h5>
                    <p class="card-text text-muted small">Inativos</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-exclamation-triangle fs-1 text-warning mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['com_problemas'] }}</h5>
                    <p class="card-text text-muted small">Com Problemas</p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="bi bi-star fs-1 text-success mb-2"></i>
                    <h5 class="card-title mb-1">{{ $stats['novos'] }}</h5>
                    <p class="card-text text-muted small">Novos (24h)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Destaques -->
    @if($analisadoresNovos->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-success shadow-sm destaque-card">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-star-fill me-2"></i>
                        Novos Analisadores (24h)
                    </h5>
                    <span class="badge bg-success bg-opacity-25 text-white fs-6">{{ $analisadoresNovos->count() }}</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        @foreach($analisadoresNovos as $analisador)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <div class="card h-100 border-0 shadow-sm destaque-item">
                                <div class="card-body p-2 text-center">
                                    <div class="position-relative">
                                        <i class="bi bi-gear-wide-connected text-success fs-4 mb-2"></i>
                                        <span class="badge-novo-destaque">⭐</span>
                                    </div>
                                    <h6 class="card-title mb-1 fw-semibold small">{{ strlen($analisador->analyzer) > 15 ? substr($analisador->analyzer, 0, 15) . '...' : $analisador->analyzer }}</h6>
                                    <p class="card-text text-muted small mb-1">{{ $analisador->usuario->name ?? 'N/A' }}</p>
                                    <small class="text-success fw-semibold">{{ $analisador->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="card-footer p-1 bg-transparent border-0">
                                    <a href="{{ route('analisadores.show', $analisador) }}" class="btn btn-success btn-sm w-100 py-1">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtros Rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-muted me-2">Filtros rápidos:</span>
                        <a href="{{ route('analisadores.index', ['created_at' => 'today']) }}" 
                           class="btn btn-outline-success btn-sm {{ request('created_at') == 'today' ? 'active' : '' }}">
                            <i class="bi bi-star me-1"></i>Novos
                        </a>
                        <a href="{{ route('analisadores.index', ['ativo' => '1']) }}" 
                           class="btn btn-outline-primary btn-sm {{ request('ativo') == '1' ? 'active' : '' }}">
                            <i class="bi bi-check-circle me-1"></i>Ativos
                        </a>
                        <a href="{{ route('analisadores.index', ['componentes' => 'problema']) }}" 
                           class="btn btn-outline-warning btn-sm {{ request('componentes') == 'problema' ? 'active' : '' }}">
                            <i class="bi bi-exclamation-triangle me-1"></i>Com Problemas
                        </a>
                        <a href="{{ route('analisadores.index', ['ativo' => '0']) }}" 
                           class="btn btn-outline-danger btn-sm {{ request('ativo') == '0' ? 'active' : '' }}">
                            <i class="bi bi-x-circle me-1"></i>Inativos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('analisadores.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nome, observação ou usuário...">
                </div>
                <div class="col-md-2">
                    <label for="analyzer" class="form-label">Tipo</label>
                    <select class="form-select" id="analyzer" name="analyzer">
                        <option value="">Todos</option>
                        @foreach($tiposAnalisadores as $key => $tipo)
                            <option value="{{ $key }}" {{ request('analyzer') == $key ? 'selected' : '' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="ativo" class="form-label">Status</label>
                    <select class="form-select" id="ativo" name="ativo">
                        <option value="">Todos</option>
                        <option value="1" {{ request('ativo') == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ request('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                           value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="data_fim" name="data_fim" 
                           value="{{ request('data_fim') }}">
                </div>
                <!-- Botões de ação centralizados -->
                <div class="col-12 d-flex justify-content-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpar
                    </a>
                </div>
               
            </form>
        </div>
    </div>

    <!-- Lista de Analisadores -->
    @if($analisadores->count() > 0)
        <!-- Controle de paginação -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-list me-2"></i>
                Lista de Analisadores
                <span class="badge bg-primary">{{ $analisadores->total() }}</span>
            </h5>
            <div class="d-flex align-items-center">
                <span class="text-muted me-2">Mostrar:</span>
                <form method="GET" action="{{ route('analisadores.index') }}" class="d-inline">
                    @foreach(request()->except('per_page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach($analisadores as $analisador)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 d-flex align-items-center">
                                <i class="bi bi-gear-wide-connected me-1"></i>
                                {{ $analisador->analyzer }}
                                @if($analisador->is_novo)
                                    <span class="badge-novo ms-2" 
                                          data-bs-toggle="tooltip" 
                                          title="Criado {{ $analisador->created_at->diffForHumans() }}">⭐</span>
                                @endif
                            </h6>
                            <div class="d-flex gap-1">
                                @if($analisador->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                                
                                @if($analisador->todos_componentes_ok)
                                    <span class="badge bg-primary">Todos OK</span>
                                @else
                                    <span class="badge bg-warning">{{ $analisador->componentes_com_problema }} problema(s)</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-muted small mb-2">
                                @php
                                    $observation = $analisador->observation ?: 'Nenhuma observação registrada.';
                                    $observationText = strlen($observation) > 60 ? substr($observation, 0, 60) . '...' : $observation;
                                @endphp
                                {{ $observationText }}
                            </p>
                            
                            <p class="card-text small mb-2">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $analisador->check_date->format('d/m/Y') }}
                                <small class="text-muted">({{ $analisador->check_date->diffForHumans() }})</small>
                            </p>
                            
                            @if($analisador->usuario)
                                <p class="card-text small mb-2">
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ $analisador->usuario->name }}
                                </p>
                            @endif
                            
                            @if($analisador->room_temperature || $analisador->air_pressure)
                                <div class="mb-2">
                                    @if($analisador->room_temperature)
                                        <span class="badge bg-info me-1">
                                            <i class="bi bi-thermometer-half me-1"></i>
                                            {{ $analisador->room_temperature }}°C
                                        </span>
                                    @endif
                                    @if($analisador->air_pressure)
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-speedometer2 me-1"></i>
                                            {{ $analisador->air_pressure }} bar
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex gap-1">
                                <a href="{{ route('analisadores.show', $analisador) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver Detalhes
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('analisadores.edit', $analisador) }}">
                                                <i class="bi bi-pencil me-2"></i>Editar
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('analisadores.duplicate', $analisador) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-files me-2"></i>Duplicar
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('analisadores.toggle-status', $analisador) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-{{ $analisador->ativo ? 'pause' : 'play' }} me-2"></i>
                                                    {{ $analisador->ativo ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($analisadores->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <!-- Informações da paginação -->
                            <div class="mb-3 mb-md-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                <small class="text-muted me-3 mb-2 mb-sm-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mostrando {{ $analisadores->firstItem() }} a {{ $analisadores->lastItem() }} 
                                    de {{ $analisadores->total() }} resultados
                                    @if(request()->hasAny(['search', 'analyzer', 'ativo', 'data_inicio', 'data_fim']))
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
                                {{ $analisadores->withQueryString()->links('pagination::bootstrap-4') }}
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
                            Mostrando {{ $analisadores->count() }} de {{ $analisadores->total() }} resultados
                            @if(request()->hasAny(['search', 'analyzer', 'ativo', 'data_inicio', 'data_fim']))
                                <span class="badge bg-light text-dark ms-1">
                                    <i class="bi bi-funnel me-1"></i>Filtrado
                                </span>
                            @endif
                            @if($analisadores->total() > 10)
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
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-gear-wide-connected text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Nenhum analisador encontrado</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'analyzer', 'ativo', 'data_inicio', 'data_fim']))
                        Ajuste os filtros para encontrar os analisadores desejados.
                    @else
                        Comece criando seu primeiro analisador.
                    @endif
                </p>
                <div class="mt-3">
                    @if(request()->hasAny(['search', 'analyzer', 'ativo', 'data_inicio', 'data_fim']))
                        <a href="{{ route('analisadores.index') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Limpar Filtros
                        </a>
                    @endif
                    <a href="{{ route('analisadores.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Novo Analisador
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Cards de destaque */
    .destaque-card {
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #28a745, #20c997) border-box;
        transition: all 0.3s ease;
    }

    .destaque-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.15) !important;
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    }

    .destaque-item {
        transition: all 0.3s ease;
        border: 1px solid rgba(40, 167, 69, 0.2) !important;
    }

    .destaque-item:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2) !important;
        border-color: #28a745 !important;
    }

    /* Badges animados */
    .badge-novo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        font-size: 12px;
        animation: fadeInScale 0.5s ease-out, pulseNovo 2s infinite;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    .badge-novo:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.5);
    }

    .badge-novo-destaque {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulseNovo 2s infinite;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }

    /* Animações */
    @keyframes fadeInScale {
        0% {
            opacity: 0;
            transform: scale(0.5);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulseNovo {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.5);
        }
    }

    /* Responsividade dos badges */
    @media (max-width: 768px) {
        .badge-novo {
            width: 20px;
            height: 20px;
            font-size: 11px;
        }
        
        .badge-novo-destaque {
            width: 16px;
            height: 16px;
            font-size: 9px;
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

        // Inicializar tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Efeito de clique nos badges
        document.querySelectorAll('.badge-novo').forEach(badge => {
            badge.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Animação de clique
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>
@endpush 