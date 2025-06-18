@extends('layouts.app')

@section('title', 'Inspeções de Gerador')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-3 mb-lg-0">
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bi bi-lightning-charge me-2"></i>
                Inspeções de Gerador
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inspeções de Gerador</li>
                </ol>
            </nav>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('inspecoes-gerador.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Nova Inspeção
            </a>
            
            @if($inspecoes->count() > 0)
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-download me-1"></i>
                        Exportar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Estatísticas Rápidas -->
    @if($inspecoes->count() > 0)
    <div class="row mb-4 g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Inspeções</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inspecoes->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Parâmetros Normais</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspecoes->sum(function($i) { return $i->validacao_parametros['resumo']['normal']; }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Requer Atenção</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspecoes->sum(function($i) { return $i->validacao_parametros['resumo']['atencao']; }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Críticos/Anormais</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $inspecoes->sum(function($i) { return $i->validacao_parametros['resumo']['critico'] + $i->validacao_parametros['resumo']['anormal']; }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>
                Filtros de Pesquisa
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inspecoes-gerador.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="ID, colaborador...">
                </div>
                <div class="col-md-2">
                    <label for="colaborador" class="form-label">Colaborador</label>
                    <input type="text" class="form-control" id="colaborador" name="colaborador" 
                           value="{{ request('colaborador') }}" placeholder="Nome do colaborador">
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
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="normal" {{ request('status') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="atencao" {{ request('status') === 'atencao' ? 'selected' : '' }}>Atenção</option>
                        <option value="anormal" {{ request('status') === 'anormal' ? 'selected' : '' }}>Anormal</option>
                        <option value="critico" {{ request('status') === 'critico' ? 'selected' : '' }}>Crítico</option>
                    </select>
                </div>
                
                <!-- Botões de ação centralizados -->
                <div class="col-12 d-flex justify-content-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" id="btn-filtrar">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('inspecoes-gerador.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpar Filtros
                    </a>
                    @if(request()->hasAny(['search', 'colaborador', 'data_inicio', 'data_fim', 'status']))
                        <small class="text-muted align-self-center ms-2">
                            <i class="bi bi-funnel-fill text-primary me-1"></i>
                            Filtros ativos
                        </small>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Inspeções -->
    @if($inspecoes->count() > 0)
        <div class="row g-4 mb-4">
            @foreach($inspecoes as $inspecao)
                @php
                    $statusGeral = $inspecao->status_geral;
                    $validacao = $inspecao->validacao_parametros;
                @endphp
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card h-100 shadow border-0 {{ $statusGeral['status'] === 'critico' ? 'border-danger' : ($statusGeral['status'] === 'anormal' ? 'border-warning' : ($statusGeral['status'] === 'atencao' ? 'border-info' : 'border-success')) }}" style="border-left: 4px solid {{ $statusGeral['status'] === 'critico' ? '#dc3545' : ($statusGeral['status'] === 'anormal' ? '#fd7e14' : ($statusGeral['status'] === 'atencao' ? '#0dcaf0' : '#198754')) }} !important;">
                        <!-- Header do Card -->
                        <div class="card-header bg-white border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title mb-1 fw-bold text-dark">
                                        <i class="bi bi-lightning-charge text-primary me-2"></i>
                                        Inspeção #{{ $inspecao->id }}
                                    </h6>
                                    <small class="text-muted">{{ $inspecao->data_formatada }}</small>
                                </div>
                                <span class="badge {{ $statusGeral['badge'] }} fs-6">
                                    <i class="{{ $statusGeral['icone'] }} me-1"></i>
                                    {{ strtoupper($statusGeral['status']) }}
                                </span>
                            </div>
                        </div>

                        <!-- Corpo do Card -->
                        <div class="card-body pt-2">
                            <!-- Informações do Colaborador -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-fill text-muted me-2"></i>
                                    <strong class="text-dark">{{ $inspecao->colaborador }}</strong>
                                </div>
                                @if($inspecao->usuario)
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        Registrado por {{ $inspecao->usuario->name }} em {{ $inspecao->data_hora_formatada }}
                                    </small>
                                @endif
                            </div>

                            <!-- Análise de Parâmetros -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-2 fw-semibold">
                                    <i class="bi bi-graph-up me-1"></i>
                                    Análise de Parâmetros
                                </h6>
                                <div class="row text-center g-2">
                                    <div class="col-3">
                                        <div class="bg-success bg-opacity-10 rounded p-2">
                                            <div class="text-success fw-bold fs-5">{{ $validacao['resumo']['normal'] }}</div>
                                            <small class="text-success fw-semibold">Normal</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-warning bg-opacity-10 rounded p-2">
                                            <div class="text-warning fw-bold fs-5">{{ $validacao['resumo']['atencao'] }}</div>
                                            <small class="text-warning fw-semibold">Atenção</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-danger bg-opacity-10 rounded p-2">
                                            <div class="text-danger fw-bold fs-5">{{ $validacao['resumo']['anormal'] }}</div>
                                            <small class="text-danger fw-semibold">Anormal</small>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-dark bg-opacity-10 rounded p-2">
                                            <div class="text-dark fw-bold fs-5">{{ $validacao['resumo']['critico'] }}</div>
                                            <small class="text-dark fw-semibold">Crítico</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recomendações (se houver) -->
                            @if(count($inspecao->recomendacoes) > 0)
                                <div class="mb-3">
                                    <div class="alert alert-warning py-2 px-3 mb-0 border-0 bg-warning bg-opacity-10">
                                        <small class="text-warning fw-semibold">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            {{ count($inspecao->recomendacoes) }} recomendação(ões) de manutenção
                                        </small>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Status Operacional -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-2 fw-semibold">
                                    <i class="bi bi-gear me-1"></i>
                                    Status Operacional
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-droplet text-primary me-2"></i>
                                            <span class="badge bg-{{ $inspecao->nivel_oleo == 'Normal' ? 'success' : ($inspecao->nivel_oleo == 'Máximo' ? 'info' : 'danger') }} small">
                                                Óleo: {{ $inspecao->nivel_oleo }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-droplet-half text-info me-2"></i>
                                            <span class="badge bg-{{ $inspecao->nivel_agua == 'Normal' ? 'success' : ($inspecao->nivel_agua == 'Máximo' ? 'info' : 'danger') }} small">
                                                Água: {{ $inspecao->nivel_agua }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-fuel-pump text-warning me-2"></i>
                                            <span class="badge bg-{{ $inspecao->combustivel_50 == 'Sim' ? 'success' : 'danger' }} small">
                                                Combustível: {{ $inspecao->combustivel_50 }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($inspecao->rpm)
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-speedometer2 text-success me-2"></i>
                                                <span class="text-dark fw-semibold small">{{ number_format($inspecao->rpm) }} RPM</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($inspecao->observacao)
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1 fw-semibold">
                                        <i class="bi bi-chat-text me-1"></i>
                                        Observação
                                    </h6>
                                    <p class="small text-muted mb-0 fst-italic">
                                        "{{ strlen($inspecao->observacao) > 100 ? substr($inspecao->observacao, 0, 100) . '...' : $inspecao->observacao }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Footer do Card -->
                        <div class="card-footer bg-light border-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('inspecoes-gerador.show', $inspecao) }}" 
                                   class="btn btn-primary btn-sm flex-fill">
                                    <i class="bi bi-eye me-1"></i>
                                    Visualizar
                                </a>
                                <a href="{{ route('inspecoes-gerador.edit', $inspecao) }}" 
                                   class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center">
            {{ $inspecoes->appends(request()->query())->links() }}
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Nenhuma inspeção encontrada</h5>
                <p class="text-muted mb-4">
                    @if(request()->hasAny(['search', 'colaborador', 'data_inicio', 'data_fim', 'status']))
                        Não foram encontradas inspeções com os filtros aplicados.
                    @else
                        Ainda não há inspeções cadastradas no sistema.
                    @endif
                </p>
                <a href="{{ route('inspecoes-gerador.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Criar Primeira Inspeção
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}
.text-xs {
    font-size: 0.7rem;
}
.font-weight-bold {
    font-weight: 700 !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.fa-2x {
    font-size: 2em;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnFiltrar = document.getElementById('btn-filtrar');
    const formFiltros = btnFiltrar.closest('form');
    
    // Melhorar feedback visual do botão filtrar
    formFiltros.addEventListener('submit', function(e) {
        const originalText = btnFiltrar.innerHTML;
        btnFiltrar.disabled = true;
        btnFiltrar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Filtrando...';
        
        // Restaurar botão após um tempo (fallback)
        setTimeout(function() {
            btnFiltrar.disabled = false;
            btnFiltrar.innerHTML = originalText;
        }, 5000);
    });
    
    // Auto-submit quando status for alterado
    const selectStatus = document.getElementById('status');
    if (selectStatus) {
        selectStatus.addEventListener('change', function() {
            if (this.value !== '') {
                formFiltros.submit();
            }
        });
    }
    
    // Limpar campos com Enter
    const inputs = formFiltros.querySelectorAll('input[type="text"], input[type="date"]');
    inputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                formFiltros.submit();
            }
        });
    });
});
</script>
@endpush 