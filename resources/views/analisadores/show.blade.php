@extends('layouts.app')

@section('title', 'Detalhes do Analisador')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="bi bi-house"></i> Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analisadores.index') }}" class="text-decoration-none">Analisadores</a>
            </li>
            <li class="breadcrumb-item active">{{ $analisador->analyzer }} #{{ $analisador->id }}</li>
        </ol>
    </nav>

    <!-- Header Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Informações Principais -->
                        <div class="col-lg-8">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-{{ $analisador->ativo ? 'success' : 'danger' }} px-3 py-2">
                                    <i class="bi bi-{{ $analisador->ativo ? 'check-circle' : 'x-circle' }} me-1"></i>
                                    {{ $analisador->status_label }}
                                </span>
                                <span class="badge bg-primary px-3 py-2">
                                    <i class="bi bi-gear-wide-connected me-1"></i>
                                    {{ $analisador->analyzer }}
                                </span>
                                @if($analisador->todos_componentes_ok)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-all me-1"></i>
                                        Todos os componentes OK
                                    </span>
                                @else
                                    <span class="badge bg-warning px-3 py-2">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $analisador->componentes_com_problema }} componente(s) com problema
                                    </span>
                                @endif
                            </div>
                            
                            <h1 class="h3 fw-bold text-dark mb-3">
                                <i class="bi bi-gear-wide-connected text-primary me-2"></i>
                                Analisador {{ $analisador->analyzer }}
                            </h1>
                            
                            <div class="row g-3 text-muted">
                                <div class="col-md-6">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <strong>Responsável:</strong> {{ $analisador->usuario->name ?? 'N/A' }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <strong>Última Verificação:</strong> {{ $analisador->check_date->format('d/m/Y') }}
                                    <small class="text-muted">({{ $analisador->check_date->diffForHumans() }})</small>
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-hash text-primary me-2"></i>
                                    <strong>ID:</strong> #{{ $analisador->id }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-clock text-primary me-2"></i>
                                    <strong>Criado em:</strong> {{ $analisador->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ações -->
                        <div class="col-lg-4 text-center mt-4 mt-lg-0">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('analisadores.edit', $analisador) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i>
                                    Editar Analisador
                                </a>
                                <form action="{{ route('analisadores.duplicate', $analisador) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="bi bi-files me-1"></i>
                                        Duplicar
                                    </button>
                                </form>
                                <form action="{{ route('analisadores.toggle-status', $analisador) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-{{ $analisador->ativo ? 'secondary' : 'success' }} w-100">
                                        <i class="bi bi-{{ $analisador->ativo ? 'pause' : 'play' }} me-1"></i>
                                        {{ $analisador->ativo ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                                <a href="{{ route('pdf.analisador', $analisador) }}" class="btn btn-outline-danger" target="_blank">
                                    <i class="bi bi-file-earmark-pdf me-1"></i>
                                    PDF (DomPDF)
                                </a>
                                <a href="{{ route('analisador.pdf.browsershot', $analisador) }}" class="btn btn-danger" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                    PDF Premium
                                </a>
                                <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Voltar à Lista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Conteúdo Principal -->
        <div class="col-lg-8">
            <!-- Status dos Componentes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-list-check text-primary me-2"></i>
                        Status dos Componentes
                        <span class="badge bg-{{ $analisador->todos_componentes_ok ? 'success' : 'warning' }} ms-2">
                            {{ collect($componentes)->filter(function($nome, $key) use ($analisador) { return $analisador->$key; })->count() }}/{{ count($componentes) }}
                        </span>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($componentes as $key => $nome)
                            <div class="col-md-6 col-lg-4">
                                <div class="d-flex align-items-center p-3 border rounded {{ $analisador->$key ? 'border-success bg-success bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}">
                                    <div class="me-3">
                                        @if($analisador->$key)
                                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                        @else
                                            <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $nome }}</div>
                                        <small class="text-muted">
                                            {{ $analisador->$key ? 'Funcionando' : 'Com problema' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Medições Ambientais -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-thermometer text-primary me-2"></i>
                        Medições Ambientais
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="text-center p-4 bg-light rounded">
                                @if($analisador->room_temperature)
                                    <div class="mb-2">
                                        <i class="bi bi-thermometer-half text-info fs-1"></i>
                                    </div>
                                    <div class="fs-2 fw-bold text-info">{{ $analisador->room_temperature }}°C</div>
                                    <small class="text-muted">Temperatura do Ambiente</small>
                                @else
                                    <div class="mb-2">
                                        <i class="bi bi-dash-circle text-muted fs-1"></i>
                                    </div>
                                    <div class="text-muted">
                                        <div class="fw-semibold">Não informado</div>
                                        <small>Temperatura do Ambiente</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-4 bg-light rounded">
                                @if($analisador->air_pressure)
                                    <div class="mb-2">
                                        <i class="bi bi-speedometer2 text-secondary fs-1"></i>
                                    </div>
                                    <div class="fs-2 fw-bold text-secondary">{{ $analisador->air_pressure }} bar</div>
                                    <small class="text-muted">Pressão do Ar</small>
                                @else
                                    <div class="mb-2">
                                        <i class="bi bi-dash-circle text-muted fs-1"></i>
                                    </div>
                                    <div class="text-muted">
                                        <div class="fw-semibold">Não informado</div>
                                        <small>Pressão do Ar</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            @if($analisador->observation)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-chat-text text-primary me-2"></i>
                            Observações
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="bg-light rounded p-3" style="line-height: 1.7;">
                            {!! nl2br(e($analisador->observation)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Imagem -->
            @if($analisador->image)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-image text-primary me-2"></i>
                            Imagem do Analisador
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <img src="{{ Storage::url($analisador->image) }}" 
                             class="img-fluid rounded shadow" 
                             style="max-width: 100%; max-height: 400px;"
                             alt="Imagem do Analisador">
                        <div class="mt-3">
                            <a href="{{ Storage::url($analisador->image) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-download me-1"></i>
                                Baixar Imagem
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resumo Rápido -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-speedometer2 text-success me-2"></i>
                        Resumo Rápido
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <div class="fw-bold text-success fs-4">{{ collect($componentes)->filter(function($nome, $key) use ($analisador) { return $analisador->$key; })->count() }}</div>
                                <small class="text-muted">Funcionando</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <div class="fw-bold text-danger fs-4">{{ $analisador->componentes_com_problema }}</div>
                                <small class="text-muted">Com Problema</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status Geral:</span>
                        <span class="badge bg-{{ $analisador->todos_componentes_ok ? 'success' : 'warning' }}">
                            {{ $analisador->todos_componentes_ok ? 'Todos OK' : 'Requer Atenção' }}
                        </span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Eficiência:</span>
                        @php
                            $total = count($componentes);
                            $funcionando = collect($componentes)->filter(function($nome, $key) use ($analisador) { return $analisador->$key; })->count();
                            $eficiencia = $total > 0 ? round(($funcionando / $total) * 100) : 0;
                        @endphp
                        <span class="fw-bold text-{{ $eficiencia >= 80 ? 'success' : ($eficiencia >= 60 ? 'warning' : 'danger') }}">
                            {{ $eficiencia }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informações Técnicas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Informações Técnicas
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ID do Sistema:</span>
                            <span class="fw-semibold">#{{ $analisador->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Criado em:</span>
                            <span>{{ $analisador->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Atualizado em:</span>
                            <span>{{ $analisador->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $analisador->ativo ? 'success' : 'danger' }}">
                                {{ $analisador->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 