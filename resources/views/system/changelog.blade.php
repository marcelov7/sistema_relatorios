@extends('layouts.app')

@section('title', 'Histórico de Versões')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
        <div class="mb-2 mb-lg-0">
            <h1 class="h3 mb-1">
                <i class="bi bi-clock-history text-primary me-2"></i>
                Histórico de Versões
            </h1>
            <p class="text-muted mb-0">Acompanhe a evolução do sistema ao longo do tempo</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('system.info') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-info-circle me-1"></i>
                Informações do Sistema
            </a>
            <a href="{{ route('relatorios.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Versão Atual -->
    <div class="alert alert-primary border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="bi bi-star-fill fs-2"></i>
            </div>
            <div>
                <h5 class="alert-heading mb-1">Versão Atual: {{ $versionInfo['formatted_version'] }}</h5>
                <p class="mb-0">Lançada em {{ $versionInfo['formatted_date'] }} • Build #{{ $versionInfo['build'] }}</p>
            </div>
        </div>
    </div>

    <!-- Timeline de Versões -->
    <div class="row">
        <div class="col-12">
            @foreach($changelog as $version => $details)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-{{ $loop->first ? 'primary' : 'light' }} {{ $loop->first ? 'text-white' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-semibold">
                                @if($loop->first)
                                    <i class="bi bi-star-fill me-2"></i>
                                @else
                                    <i class="bi bi-tag me-2"></i>
                                @endif
                                Versão {{ $version }}
                                @if(!empty($details['version_name']))
                                    "{{ $details['version_name'] }}"
                                @endif
                            </h5>
                            <small class="{{ $loop->first ? 'text-white-50' : 'text-muted' }}">
                                Lançada em {{ \Carbon\Carbon::parse($details['release_date'])->format('d/m/Y') }}
                            </small>
                        </div>
                        <div>
                            @php
                                $typeColors = [
                                    'major' => 'danger',
                                    'minor' => 'warning', 
                                    'patch' => 'info'
                                ];
                                $typeColor = $typeColors[$details['type']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $typeColor }}">
                                {{ ucfirst($details['type']) }} Release
                            </span>
                            @if($loop->first)
                                <span class="badge bg-success ms-1">Atual</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @if(!empty($details['features']))
                        <div class="col-lg-6">
                            <h6 class="text-success fw-semibold mb-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Novas Funcionalidades
                                <span class="badge bg-success ms-1">{{ count($details['features']) }}</span>
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($details['features'] as $feature)
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-check-circle text-success me-2 mt-1 flex-shrink-0"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($details['improvements']))
                        <div class="col-lg-6">
                            <h6 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-arrow-up-circle me-2"></i>
                                Melhorias
                                <span class="badge bg-primary ms-1">{{ count($details['improvements']) }}</span>
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($details['improvements'] as $improvement)
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-arrow-up text-primary me-2 mt-1 flex-shrink-0"></i>
                                    <span>{{ $improvement }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($details['fixes']))
                        <div class="col-lg-6">
                            <h6 class="text-warning fw-semibold mb-3">
                                <i class="bi bi-wrench me-2"></i>
                                Correções
                                <span class="badge bg-warning ms-1">{{ count($details['fixes']) }}</span>
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($details['fixes'] as $fix)
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-wrench text-warning me-2 mt-1 flex-shrink-0"></i>
                                    <span>{{ $fix }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(!empty($details['technical']))
                        <div class="col-lg-6">
                            <h6 class="text-info fw-semibold mb-3">
                                <i class="bi bi-gear me-2"></i>
                                Aspectos Técnicos
                                <span class="badge bg-info ms-1">{{ count($details['technical']) }}</span>
                            </h6>
                            <ul class="list-unstyled">
                                @foreach($details['technical'] as $tech)
                                <li class="mb-2 d-flex align-items-start">
                                    <i class="bi bi-gear text-info me-2 mt-1 flex-shrink-0"></i>
                                    <span>{{ $tech }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    @if(!empty($details['features']) || !empty($details['improvements']) || !empty($details['fixes']) || !empty($details['technical']))
                    <div class="mt-4 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="text-success">
                                    <i class="bi bi-plus-circle fs-4"></i>
                                    <div class="fw-semibold">{{ count($details['features'] ?? []) }}</div>
                                    <small class="text-muted">Funcionalidades</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-primary">
                                    <i class="bi bi-arrow-up-circle fs-4"></i>
                                    <div class="fw-semibold">{{ count($details['improvements'] ?? []) }}</div>
                                    <small class="text-muted">Melhorias</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-warning">
                                    <i class="bi bi-wrench fs-4"></i>
                                    <div class="fw-semibold">{{ count($details['fixes'] ?? []) }}</div>
                                    <small class="text-muted">Correções</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-info">
                                    <i class="bi bi-gear fs-4"></i>
                                    <div class="fw-semibold">{{ count($details['technical'] ?? []) }}</div>
                                    <small class="text-muted">Técnicos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Informações sobre Versionamento -->
    <div class="card border-0 shadow-sm bg-light">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h6 class="fw-semibold mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Sobre o Versionamento
                    </h6>
                    <p class="text-muted mb-0">
                        Este sistema segue o padrão de versionamento semântico (SemVer): 
                        <strong>MAJOR.MINOR.PATCH</strong>. Versões MAJOR introduzem mudanças incompatíveis, 
                        MINOR adicionam funcionalidades mantendo compatibilidade, e PATCH incluem correções de bugs.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex gap-2 justify-content-lg-end">
                        <span class="badge bg-danger">Major</span>
                        <span class="badge bg-warning">Minor</span>
                        <span class="badge bg-info">Patch</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 