@extends('layouts.app')

@section('title', 'Detalhes da Inspeção de Gerador')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-gear-fill me-2"></i>
                Detalhes da Inspeção de Gerador
            </h1>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inspecoes-gerador.index') }}">Inspeções de Gerador</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </nav>
        </div>
        
        <div class="btn-group">
            <a href="{{ route('inspecoes-gerador.edit', $inspecao) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i>
                Editar
            </a>
            <a href="{{ route('pdf.inspecao', $inspecao) }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-pdf me-1"></i>
                PDF
            </a>
            <a href="{{ route('inspecoes-gerador.duplicate', $inspecao) }}" class="btn btn-info">
                <i class="bi bi-copy me-1"></i>
                Duplicar
            </a>
        </div>
    </div>

    @php
        $statusGeral = $inspecao->status_geral;
        $validacao = $inspecao->validacao_parametros;
    @endphp
    
    <!-- Header da Inspeção -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-2 text-dark fw-bold">
                                <i class="bi bi-lightning-charge text-primary me-2"></i>
                                Inspeção de Gerador #{{ $inspecao->id }}
                            </h4>
                            <div class="d-flex align-items-center gap-3 text-muted">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $inspecao->data_formatada }}</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $inspecao->data_hora_formatada }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $statusGeral['badge'] }} fs-5 mb-2">
                                <i class="{{ $statusGeral['icone'] }} me-1"></i>
                                {{ strtoupper($statusGeral['status']) }}
                            </span>
                            @if($inspecao->ativo)
                                <div><span class="badge bg-success">Ativo</span></div>
                            @else
                                <div><span class="badge bg-secondary">Inativo</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="row mb-4 g-3">
        <div class="col-lg-6">
            <div class="card h-100 shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-person-fill me-2"></i>
                        Informações do Colaborador
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold">COLABORADOR</label>
                        <div class="fw-bold text-dark fs-5">{{ $inspecao->colaborador }}</div>
                    </div>
                    @if($inspecao->usuario)
                        <div class="mb-0">
                            <label class="text-muted small fw-semibold">REGISTRADO POR</label>
                            <div class="text-dark">{{ $inspecao->usuario->name }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card h-100 shadow border-0">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-gear-fill me-2"></i>
                        Status Operacional
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background-color: {{ $inspecao->nivel_oleo == 'Normal' ? '#d1e7dd' : ($inspecao->nivel_oleo == 'Máximo' ? '#cff4fc' : '#f8d7da') }};">
                                <i class="bi bi-droplet fs-4 mb-1" style="color: {{ $inspecao->nivel_oleo == 'Normal' ? '#0f5132' : ($inspecao->nivel_oleo == 'Máximo' ? '#055160' : '#842029') }};"></i>
                                <div class="small fw-semibold text-muted">ÓLEO</div>
                                <div class="fw-bold" style="color: {{ $inspecao->nivel_oleo == 'Normal' ? '#0f5132' : ($inspecao->nivel_oleo == 'Máximo' ? '#055160' : '#842029') }};">{{ $inspecao->nivel_oleo }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background-color: {{ $inspecao->nivel_agua == 'Normal' ? '#d1e7dd' : ($inspecao->nivel_agua == 'Máximo' ? '#cff4fc' : '#f8d7da') }};">
                                <i class="bi bi-droplet-half fs-4 mb-1" style="color: {{ $inspecao->nivel_agua == 'Normal' ? '#0f5132' : ($inspecao->nivel_agua == 'Máximo' ? '#055160' : '#842029') }};"></i>
                                <div class="small fw-semibold text-muted">ÁGUA</div>
                                <div class="fw-bold" style="color: {{ $inspecao->nivel_agua == 'Normal' ? '#0f5132' : ($inspecao->nivel_agua == 'Máximo' ? '#055160' : '#842029') }};">{{ $inspecao->nivel_agua }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background-color: {{ $inspecao->combustivel_50 == 'Sim' ? '#d1e7dd' : '#f8d7da' }};">
                                <i class="bi bi-fuel-pump fs-4 mb-1" style="color: {{ $inspecao->combustivel_50 == 'Sim' ? '#0f5132' : '#842029' }};"></i>
                                <div class="small fw-semibold text-muted">COMBUSTÍVEL 50%</div>
                                <div class="fw-bold" style="color: {{ $inspecao->combustivel_50 == 'Sim' ? '#0f5132' : '#842029' }};">{{ $inspecao->combustivel_50 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 rounded" style="background-color: {{ $inspecao->iluminacao_emergencia == 'Sim' ? '#d1e7dd' : '#f8d7da' }};">
                                <i class="bi bi-lightbulb fs-4 mb-1" style="color: {{ $inspecao->iluminacao_emergencia == 'Sim' ? '#0f5132' : '#842029' }};"></i>
                                <div class="small fw-semibold text-muted">ILUMINAÇÃO</div>
                                <div class="fw-bold" style="color: {{ $inspecao->iluminacao_emergencia == 'Sim' ? '#0f5132' : '#842029' }};">{{ $inspecao->iluminacao_emergencia }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análise Automática de Parâmetros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-graph-up me-2"></i>
                        Análise Automática de Parâmetros
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Resumo da Análise -->
                    <div class="row text-center mb-4 g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="bg-success bg-opacity-10 rounded p-3 h-100">
                                <i class="bi bi-check-circle-fill text-success fs-2 mb-2"></i>
                                <div class="text-success fw-bold fs-1">{{ $validacao['resumo']['normal'] }}</div>
                                <div class="text-success fw-semibold">NORMAL</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="bg-warning bg-opacity-10 rounded p-3 h-100">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-2 mb-2"></i>
                                <div class="text-warning fw-bold fs-1">{{ $validacao['resumo']['atencao'] }}</div>
                                <div class="text-warning fw-semibold">ATENÇÃO</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="bg-danger bg-opacity-10 rounded p-3 h-100">
                                <i class="bi bi-x-circle-fill text-danger fs-2 mb-2"></i>
                                <div class="text-danger fw-bold fs-1">{{ $validacao['resumo']['anormal'] }}</div>
                                <div class="text-danger fw-semibold">ANORMAL</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="bg-dark bg-opacity-10 rounded p-3 h-100">
                                <i class="bi bi-exclamation-octagon-fill text-dark fs-2 mb-2"></i>
                                <div class="text-dark fw-bold fs-1">{{ $validacao['resumo']['critico'] }}</div>
                                <div class="text-dark fw-semibold">CRÍTICO</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recomendações (se houver) -->
    @if(count($inspecao->recomendacoes) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow border-0 border-warning" style="border-left: 4px solid #fd7e14 !important;">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Recomendações de Manutenção ({{ count($inspecao->recomendacoes) }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($inspecao->recomendacoes as $index => $recomendacao)
                                <div class="col-lg-6">
                                    <div class="alert alert-{{ $recomendacao['prioridade'] === 'alta' ? 'danger' : ($recomendacao['prioridade'] === 'media' ? 'warning' : 'info') }} mb-0">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-{{ $recomendacao['prioridade'] === 'alta' ? 'exclamation-octagon' : ($recomendacao['prioridade'] === 'media' ? 'exclamation-triangle' : 'info-circle') }}-fill me-2 mt-1"></i>
                                            <div>
                                                <div class="fw-semibold mb-1">
                                                    Prioridade {{ ucfirst($recomendacao['prioridade']) }}
                                                </div>
                                                <div class="small">{{ $recomendacao['descricao'] }}</div>
                                            </div>
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

    <!-- Tensões Elétricas -->
    @if($validacao['parametros'])
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightning-charge me-2"></i>
                        Tensões Elétricas - Análise Automática
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Parâmetro</th>
                                    <th class="text-center">Valor Medido</th>
                                    <th class="text-center">Faixa Normal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Avaliação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['tensao_sync_gerador', 'tensao_sync_rede', 'tensao_a', 'tensao_b', 'tensao_c', 'tensao_bateria', 'tensao_alternador'] as $campo)
                                    @if(isset($validacao['parametros'][$campo]))
                                        @php $param = $validacao['parametros'][$campo]; @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $param['nome'] }}</td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{ number_format($param['valor'], 2, ',', '.') }}{{ $param['unidade'] }}</span>
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ number_format($param['min'], 1) }} - {{ number_format($param['max'], 1) }}{{ $param['unidade'] }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $param['badge'] }}">
                                                    <i class="{{ $param['icone'] }} me-1"></i>
                                                    {{ strtoupper($param['status']) }}
                                                </span>
                                            </td>
                                            <td class="text-center {{ $param['classe'] }}">
                                                <small>{{ $param['mensagem'] }}</small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medições Operacionais -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Medições Operacionais - Análise Automática
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Parâmetro</th>
                                    <th class="text-center">Valor Medido</th>
                                    <th class="text-center">Faixa Normal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Avaliação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['temp_agua', 'pressao_oleo', 'frequencia', 'rpm'] as $campo)
                                    @if(isset($validacao['parametros'][$campo]))
                                        @php $param = $validacao['parametros'][$campo]; @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $param['nome'] }}</td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{ number_format($param['valor'], $campo === 'rpm' ? 0 : 2, ',', '.') }}{{ $param['unidade'] }}</span>
                                            </td>
                                            <td class="text-center text-muted">
                                                {{ number_format($param['min'], $campo === 'rpm' ? 0 : 1) }} - {{ number_format($param['max'], $campo === 'rpm' ? 0 : 1) }}{{ $param['unidade'] }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $param['badge'] }}">
                                                    <i class="{{ $param['icone'] }} me-1"></i>
                                                    {{ strtoupper($param['status']) }}
                                                </span>
                                            </td>
                                            <td class="text-center {{ $param['classe'] }}">
                                                <small>{{ $param['mensagem'] }}</small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Observações -->
    @if($inspecao->observacao)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-chat-text-fill me-2"></i>
                            Observações
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-muted fst-italic">"{{ $inspecao->observacao }}"</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Ações -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-body text-center">
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('inspecoes-gerador.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Voltar à Lista
                        </a>
                        <a href="{{ route('inspecoes-gerador.edit', $inspecao) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>
                            Editar Inspeção
                        </a>
                        <a href="#" class="btn btn-success" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>
                            Imprimir Relatório
                        </a>
                        <a href="#" class="btn btn-danger">
                            <i class="bi bi-file-pdf me-1"></i>
                            Gerar PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta inspeção?</p>
                <p class="text-muted">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('inspecoes-gerador.destroy', $inspecao) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-success {
    border-left: 4px solid #28a745 !important;
}
.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}
.table th {
    font-weight: 600;
    font-size: 0.875rem;
}
.badge {
    font-size: 0.75rem;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
</style>
@endpush 