@extends('layouts.app')

@section('title', 'Geração de PDFs')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-file-pdf text-danger me-2"></i>
                        Geração de PDFs
                    </h1>
                    <p class="text-muted mb-0">Gere relatórios personalizados em PDF</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Relatórios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['relatorios']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Inspeções</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['inspecoes']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Analisadores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['analisadores']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-microscope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Equipamentos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['equipamentos']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Locais</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['locais']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Geração -->
    <div class="row">
        <!-- Relatórios em Lote -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Relatórios em Lote
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pdf.relatorios-lote') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio_rel" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio_rel" name="data_inicio" 
                                       value="{{ now()->subMonth()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_fim_rel" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim_rel" name="data_fim" 
                                       value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status_rel" class="form-label">Status</label>
                                <select class="form-control" id="status_rel" name="status">
                                    <option value="">Todos</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="em_andamento">Em Andamento</option>
                                    <option value="resolvido">Resolvido</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prioridade_rel" class="form-label">Prioridade</label>
                                <select class="form-control" id="prioridade_rel" name="prioridade">
                                    <option value="">Todas</option>
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            Gerar PDF de Relatórios
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Inspeções em Lote -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-search me-2"></i>
                        Inspeções em Lote
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pdf.inspecoes-lote') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio_insp" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio_insp" name="data_inicio" 
                                       value="{{ now()->subMonth()->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_fim_insp" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim_insp" name="data_fim" 
                                       value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="colaborador" class="form-label">Colaborador</label>
                            <input type="text" class="form-control" id="colaborador" name="colaborador" 
                                   placeholder="Nome do colaborador (opcional)">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>
                            Gerar PDF de Inspeções
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Analytics -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-bar me-2"></i>
                        Relatório Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pdf.analytics') }}" method="GET" target="_blank">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio_analytics" class="form-label">Data Início</label>
                                <input type="date" class="form-control" id="data_inicio_analytics" name="data_inicio" 
                                       value="{{ now()->subMonths(3)->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_fim_analytics" class="form-label">Data Fim</label>
                                <input type="date" class="form-control" id="data_fim_analytics" name="data_fim" 
                                       value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Inclui gráficos, estatísticas e análises detalhadas
                        </p>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-download me-2"></i>
                            Gerar PDF Analytics
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Links Rápidos -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-bolt me-2"></i>
                        Acesso Rápido
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('relatorios.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Ver Relatórios (PDFs individuais)
                        </a>
                        <a href="{{ route('motores.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-search me-2"></i>
                            Ver Inspeções (PDFs individuais)
                        </a>
                        <a href="{{ route('analisadores.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-microscope me-2"></i>
                            Ver Analisadores (PDFs individuais)
                        </a>
                        <a href="{{ route('analytics.dashboard') }}" class="btn btn-outline-warning">
                            <i class="fas fa-chart-bar me-2"></i>
                            Dashboard Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruções -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-question-circle me-2"></i>
                        Como usar
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">PDFs em Lote:</h6>
                            <ul class="text-muted">
                                <li>Selecione o período desejado</li>
                                <li>Aplique filtros opcionais</li>
                                <li>Clique em "Gerar PDF" para download</li>
                                <li>O arquivo será baixado automaticamente</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">PDFs Individuais:</h6>
                            <ul class="text-muted">
                                <li>Acesse a página do item desejado</li>
                                <li>Clique no botão "PDF" na página</li>
                                <li>O arquivo será gerado e baixado</li>
                                <li>Inclui todos os detalhes e imagens</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}
</style>
@endpush
@endsection 