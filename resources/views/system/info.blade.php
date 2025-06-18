@extends('layouts.app')

@section('title', 'Informações do Sistema')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-2 mb-lg-0">
            <h1 class="h3 mb-1">
                <i class="bi bi-info-circle text-primary me-2"></i>
                Informações do Sistema
            </h1>
            <p class="text-muted mb-0">Detalhes da versão e configurações do sistema</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('system.changelog') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-clock-history me-1"></i>
                Histórico de Versões
            </a>
            <a href="{{ route('relatorios.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações da Versão -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-tag me-2"></i>
                        Versão Atual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="version-badge bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-rocket-takeoff fs-1"></i>
                        </div>
                        <h2 class="fw-bold text-primary mb-1">{{ $versionInfo['version'] }}</h2>
                        <h4 class="text-muted mb-2">"{{ $versionInfo['version_name'] }}"</h4>
                        <span class="badge bg-success">Estável</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-calendar-event text-primary fs-4 mb-2"></i>
                                <div class="fw-semibold">Data de Lançamento</div>
                                <div class="text-muted small">{{ $versionInfo['formatted_date'] }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-gear text-primary fs-4 mb-2"></i>
                                <div class="fw-semibold">Build</div>
                                <div class="text-muted small">#{{ $versionInfo['build'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Sistema -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-server me-2"></i>
                        Configurações do Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-semibold">
                                    <i class="bi bi-app text-primary me-2"></i>
                                    Nome da Aplicação
                                </span>
                                <span class="text-muted">{{ $systemInfo['app_name'] }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-semibold">
                                    <i class="bi bi-cloud text-success me-2"></i>
                                    Ambiente
                                </span>
                                <span class="badge bg-{{ $systemInfo['app_env'] === 'production' ? 'success' : ($systemInfo['app_env'] === 'local' ? 'warning' : 'info') }}">
                                    {{ ucfirst($systemInfo['app_env']) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-semibold">
                                    <i class="bi bi-code-slash text-warning me-2"></i>
                                    PHP
                                </span>
                                <span class="text-muted">{{ $systemInfo['php_version'] }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-semibold">
                                    <i class="bi bi-layers text-danger me-2"></i>
                                    Laravel
                                </span>
                                <span class="text-muted">{{ $systemInfo['laravel_version'] }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-semibold">
                                    <i class="bi bi-clock text-info me-2"></i>
                                    Timezone
                                </span>
                                <span class="text-muted">{{ $systemInfo['timezone'] }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-semibold">
                                    <i class="bi bi-translate text-purple me-2"></i>
                                    Idioma
                                </span>
                                <span class="text-muted">{{ $systemInfo['locale'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Novidades da Versão Atual -->
    @if(!empty($changelog))
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-star me-2"></i>
                        Novidades da Versão {{ $versionInfo['version'] }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @if(!empty($changelog['features']))
                        <div class="col-lg-6">
                            <h6 class="text-success fw-semibold mb-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Novas Funcionalidades
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($changelog['features'] as $feature)
                                <li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($changelog['improvements']))
                        <div class="col-lg-6">
                            <h6 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-arrow-up-circle me-2"></i>
                                Melhorias
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($changelog['improvements'] as $improvement)
                                <li class="mb-2">
                                    <i class="bi bi-arrow-up text-primary me-2"></i>
                                    {{ $improvement }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($changelog['fixes']))
                        <div class="col-lg-6">
                            <h6 class="text-warning fw-semibold mb-3">
                                <i class="bi bi-wrench me-2"></i>
                                Correções
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($changelog['fixes'] as $fix)
                                <li class="mb-2">
                                    <i class="bi bi-wrench text-warning me-2"></i>
                                    {{ $fix }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($changelog['technical']))
                        <div class="col-lg-6">
                            <h6 class="text-info fw-semibold mb-3">
                                <i class="bi bi-gear me-2"></i>
                                Aspectos Técnicos
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($changelog['technical'] as $tech)
                                <li class="mb-2">
                                    <i class="bi bi-gear text-info me-2"></i>
                                    {{ $tech }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Informações Adicionais -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-square me-2"></i>
                        Informações Adicionais
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="text-center p-4 bg-light rounded">
                                <i class="bi bi-shield-check text-success fs-1 mb-3"></i>
                                <h6 class="fw-semibold">Sistema Seguro</h6>
                                <p class="text-muted small mb-0">Autenticação e controle de acesso implementados</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-center p-4 bg-light rounded">
                                <i class="bi bi-speedometer2 text-primary fs-1 mb-3"></i>
                                <h6 class="fw-semibold">Alta Performance</h6>
                                <p class="text-muted small mb-0">Otimizado para processamento rápido de dados</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-center p-4 bg-light rounded">
                                <i class="bi bi-phone text-info fs-1 mb-3"></i>
                                <h6 class="fw-semibold">Responsivo</h6>
                                <p class="text-muted small mb-0">Interface adaptável para todos os dispositivos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-purple {
    color: #6f42c1 !important;
}

.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
@endsection 