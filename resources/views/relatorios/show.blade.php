@extends('layouts.app')

@section('title', 'Relatório #' . $relatorio->id)

@section('content')
<div class="container">


    <!-- Header Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Informações Principais -->
                        <div class="col-lg-8">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-{{ $relatorio->status === 'pendente' ? 'warning' : ($relatorio->status === 'em_andamento' ? 'info' : 'success') }} px-3 py-2">
                                    <i class="bi bi-{{ $relatorio->status === 'pendente' ? 'clock' : ($relatorio->status === 'em_andamento' ? 'gear' : 'check-circle') }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                                </span>
                                <span class="badge bg-{{ $relatorio->prioridade === 'baixa' ? 'secondary' : ($relatorio->prioridade === 'media' ? 'warning' : ($relatorio->prioridade === 'alta' ? 'danger' : 'dark')) }} px-3 py-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Prioridade {{ ucfirst($relatorio->prioridade) }}
                                </span>
                                @if(!$relatorio->editavel)
                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="bi bi-lock me-1"></i>Bloqueado
                                    </span>
                                @endif
                            </div>
                            
                            <h1 class="h3 fw-bold text-dark mb-3">{{ $relatorio->titulo }}</h1>
                            
                            <div class="row g-3 text-muted">
                                <div class="col-md-6">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <strong>Criado por:</strong> {{ $relatorio->usuario->name }}
                                </div>
                                <div class="col-md-6">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <strong>Data:</strong> {{ $relatorio->data_ocorrencia->format('d/m/Y H:i') }}
                                </div>
                                @if($relatorio->local)
                                    <div class="col-md-6">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        <strong>Local:</strong> {{ $relatorio->local->nome }}
                                    </div>
                                @endif
                                @if($relatorio->equipamento)
                                    <div class="col-md-6">
                                        <i class="bi bi-cpu text-primary me-2"></i>
                                        <strong>Equipamento:</strong> {{ $relatorio->equipamento->nome }}
                                        @if($relatorio->equipamento->codigo)
                                            <small class="text-muted">({{ $relatorio->equipamento->codigo }})</small>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Progresso -->
                        <div class="col-lg-4 text-center mt-4 mt-lg-0">
                            <div class="position-relative d-inline-block">
                                <svg width="120" height="120" class="progress-ring">
                                    <circle cx="60" cy="60" r="50" stroke="#e9ecef" stroke-width="8" fill="none"/>
                                    <circle cx="60" cy="60" r="50" stroke="#6f42c1" stroke-width="8" fill="none"
                                            stroke-dasharray="314" stroke-dashoffset="{{ 314 - (314 * $relatorio->progresso / 100) }}"
                                            style="transition: stroke-dashoffset 1s ease-in-out; transform: rotate(-90deg); transform-origin: center;"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <div class="h4 fw-bold text-primary mb-0">{{ $relatorio->progresso }}%</div>
                                    <small class="text-muted">Progresso</small>
                                </div>
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
            <!-- Descrição -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-file-text text-primary me-2"></i>
                        Descrição do Problema
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="bg-light rounded p-3" style="line-height: 1.7;">
                        {!! nl2br(e($relatorio->descricao)) !!}
                    </div>
                </div>
            </div>

            <!-- Imagens -->
            @if($relatorio->imagens && $relatorio->imagens->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-images text-primary me-2"></i>
                            Imagens Anexadas
                            <span class="badge bg-primary ms-2">{{ $relatorio->imagens->count() }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($relatorio->imagens as $imagem)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="position-relative image-container">
                                        @if($imagem->url)
                                            <img src="{{ $imagem->url }}" 
                                                 class="img-fluid rounded shadow-sm w-100 image-thumbnail" 
                                                 style="height: 120px; object-fit: cover; cursor: pointer; transition: transform 0.2s ease;"
                                                 onclick="openImageModal('{{ $imagem->url }}', '{{ $imagem->nome_original }}')"
                                                 title="{{ $imagem->nome_original }}"
                                                 onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center bg-light rounded\' style=\'height: 120px;\'><i class=\'bi bi-image text-muted fs-1\'></i></div>'">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 120px;">
                                                <div class="text-center">
                                                    <i class="bi bi-exclamation-triangle text-warning fs-3"></i>
                                                    <div class="small text-muted mt-1">Imagem não encontrada</div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="mt-2">
                                            <small class="text-muted d-block text-truncate" title="{{ $imagem->nome_original }}">
                                                <i class="bi bi-file-image me-1"></i>
                                                {{ $imagem->nome_original }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="bi bi-hdd me-1"></i>
                                                {{ $imagem->tamanho_formatado ?? 'N/A' }}
                                            </small>
                                            @if($imagem->url)
                                                <div class="mt-1">
                                                    <a href="{{ $imagem->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-download me-1"></i>Baixar
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Mensagem quando não há imagens -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-images text-primary me-2"></i>
                            Imagens Anexadas
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="py-4">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            <h6 class="text-muted mt-3 mb-2">Nenhuma imagem anexada</h6>
                            <p class="text-muted small mb-0">Este relatório não possui imagens anexadas.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Histórico de Atualizações -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Histórico de Atividades
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-plus-circle me-1"></i>
                                Criação
                            </span>
                            @if($relatorio->historicos->count() > 0)
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    {{ $relatorio->historicos->count() }} Atualizações
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="timeline-container">
                        <!-- Criação do Relatório (Destaque especial) -->
                        <div class="timeline-item creation-item">
                            <div class="timeline-marker-large bg-success">
                                <i class="bi bi-plus-circle-fill text-white"></i>
                            </div>
                            <div class="timeline-content-large">
                                <div class="creation-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1 text-success fw-bold">
                                                <i class="bi bi-file-earmark-plus me-2"></i>
                                                Relatório Criado
                                            </h5>
                                            <div class="creator-info">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="avatar-creator me-3">
                                                        <span>{{ strtoupper(substr($relatorio->usuario->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ $relatorio->usuario->name }}</div>
                                                        <small class="text-muted">
                                                            <i class="bi bi-person-badge me-1"></i>Autor do Relatório
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="date-time-badge">
                                                <div class="fw-semibold">{{ $relatorio->data_criacao->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $relatorio->data_criacao->format('H:i') }}</small>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                {{ $relatorio->data_criacao->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="initial-details">
                                        <div class="row g-2 text-sm">
                                            <div class="col-md-6">
                                                <span class="badge bg-{{ $relatorio->prioridade === 'baixa' ? 'secondary' : ($relatorio->prioridade === 'media' ? 'warning' : ($relatorio->prioridade === 'alta' ? 'danger' : 'dark')) }}-subtle text-{{ $relatorio->prioridade === 'baixa' ? 'secondary' : ($relatorio->prioridade === 'media' ? 'warning' : ($relatorio->prioridade === 'alta' ? 'danger' : 'dark')) }}">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                    Prioridade {{ ucfirst($relatorio->prioridade) }}
                                                </span>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Status: Pendente
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Histórico de Atualizações -->
                        @if($relatorio->historicos->count() > 0)
                            <div class="updates-section">
                                <div class="section-header">
                                    <div class="section-line"></div>
                                    <span class="section-label">
                                        <i class="bi bi-arrow-repeat me-1"></i>
                                        Atualizações e Progresso
                                    </span>
                                    <div class="section-line"></div>
                                </div>

                                @foreach($relatorio->historicos as $index => $historico)
                                    <div class="timeline-item update-item">
                                        <div class="timeline-marker bg-{{ $historico->cor }}">
                                            <i class="bi {{ $historico->icone }} text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="update-card">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-2 fw-semibold text-dark">
                                                            @if($historico->houve_mudanca_status)
                                                                <i class="bi bi-arrow-right-circle text-{{ $historico->cor }} me-2"></i>
                                                                Status alterado para <span class="text-{{ $historico->cor }}">{{ $historico->status_novo_label }}</span>
                                                            @else
                                                                <i class="bi bi-bar-chart text-{{ $historico->cor }} me-2"></i>
                                                                Progresso atualizado para <span class="text-{{ $historico->cor }}">{{ $historico->progresso }}%</span>
                                                            @endif
                                                        </h6>
                                                        
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="avatar-updater me-2">
                                                                <span>{{ strtoupper(substr($historico->usuario->name, 0, 1)) }}</span>
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium">{{ $historico->usuario->name }}</div>
                                                                <small class="text-muted">
                                                                    @if($historico->usuario->id === $relatorio->usuario_id)
                                                                        <i class="bi bi-person-check text-success me-1"></i>Autor do Relatório
                                                                    @else
                                                                        <i class="bi bi-person text-info me-1"></i>Colaborador
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>

                                                        @if($historico->houve_mudanca_status)
                                                            <div class="status-transition mb-2">
                                                                <span class="badge bg-{{ $historico->status_anterior === 'pendente' ? 'warning' : ($historico->status_anterior === 'em_andamento' ? 'info' : 'success') }}-subtle text-{{ $historico->status_anterior === 'pendente' ? 'warning' : ($historico->status_anterior === 'em_andamento' ? 'info' : 'success') }}">
                                                                    {{ $historico->status_anterior_label }}
                                                                </span>
                                                                <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                                                <span class="badge bg-{{ $historico->cor }}-subtle text-{{ $historico->cor }}">
                                                                    {{ $historico->status_novo_label }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="text-end">
                                                        <div class="date-time-badge">
                                                            <div class="fw-semibold">{{ $historico->data_atualizacao->format('d/m/Y') }}</div>
                                                            <small class="text-muted">{{ $historico->data_atualizacao->format('H:i') }}</small>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">
                                                            {{ $historico->data_atualizacao->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                @if($historico->descricao)
                                                    <div class="update-description">
                                                        <div class="description-content">
                                                            <i class="bi bi-chat-left-text text-muted me-2"></i>
                                                            <span class="text-dark">{{ $historico->descricao }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($historico->imagens && $historico->imagens->count() > 0)
                                                    <div class="update-images mt-3">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="bi bi-images text-muted me-2"></i>
                                                            <small class="text-muted fw-medium">
                                                                {{ $historico->imagens->count() }} imagem(s) anexada(s)
                                                            </small>
                                                        </div>
                                                        <div class="row g-2">
                                                            @foreach($historico->imagens as $imagem)
                                                                <div class="col-4 col-md-3">
                                                                    @if($imagem->url)
                                                                        <div class="image-thumb">
                                                                            <img src="{{ $imagem->url }}" 
                                                                                 class="img-fluid rounded" 
                                                                                 onclick="openImageModal('{{ $imagem->url }}', '{{ $imagem->nome_original }}')"
                                                                                 title="{{ $imagem->nome_original }}">
                                                                        </div>
                                                                    @else
                                                                        <div class="image-thumb-error">
                                                                            <i class="bi bi-image text-muted"></i>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-updates">
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history text-muted" style="font-size: 2rem;"></i>
                                    <h6 class="text-muted mt-3 mb-1">Nenhuma atualização ainda</h6>
                                    <p class="text-muted small mb-0">Este relatório ainda não recebeu atualizações de progresso.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Atualizar Progresso -->
            @php
                $usuariosAtribuidos = $relatorio->usuariosAtribuidos;
                $podeAtualizar = $relatorio->usuario_id == auth()->id()
                    || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
                    || $usuariosAtribuidos->pluck('id')->contains(auth()->id());
            @endphp
            @if($relatorio->editavel && $podeAtualizar)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-bar-chart text-primary me-2"></i>
                            Atualizar Progresso
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label">
                                Progresso Atual: <span class="fw-bold text-primary">{{ $relatorio->progresso }}%</span>
                            </label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" style="width: {{ $relatorio->progresso }}%"></div>
                            </div>
                        </div>
                        @if(in_array($relatorio->status, ['pendente', 'em_andamento']))
                            <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#progressoModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                Nova Atualização
                            </button>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                Relatório concluído
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Card de Atribuição de Usuários -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-people text-primary me-2"></i>
                        Atribuições
                    </h6>
                </div>
                <div class="card-body p-4">
                    @php
                        $podeAtribuir = ($relatorio->usuario_id == auth()->id() || (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin')))
                            && in_array($relatorio->status, ['pendente', 'em_andamento']);
                        $usuariosAtribuidos = $relatorio->usuariosAtribuidos;
                        $usuariosAtivos = \App\Models\User::where('ativo', true)
                            ->where('id', '!=', $relatorio->usuario_id)
                            ->whereNotIn('id', $usuariosAtribuidos->pluck('id'))
                            ->orderBy('name')
                            ->get();
                    @endphp
                    @if($podeAtribuir)
                        <form method="POST" action="{{ route('relatorios.atribuir', $relatorio) }}" class="d-flex mb-3 align-items-center gap-2">
                            @csrf
                            <select name="user_id" class="form-select" required style="max-width: 70%">
                                <option value="">Selecione um usuário...</option>
                                @foreach($usuariosAtivos as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->cargo }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Atribuir
                            </button>
                        </form>
                    @endif
                    <div>
                        @if($usuariosAtribuidos->count() > 0)
                            <ul class="list-group mb-0">
                                @foreach($usuariosAtribuidos as $user)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="bi bi-person-circle text-primary me-1"></i>
                                            {{ $user->name }} <small class="text-muted">({{ $user->cargo }})</small>
                                        </span>
                                        @if($podeAtribuir)
                                            <form method="POST" action="{{ route('relatorios.remover-atribuicao', [$relatorio, $user->id]) }}" onsubmit="return confirm('Remover atribuição deste usuário?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover">
                                                    <i class="bi bi-person-dash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted text-center">Nenhum usuário atribuído a este relatório.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informações Gerais -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Informações Gerais
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">ID:</span>
                                <span class="fw-semibold">#{{ $relatorio->id }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status:</span>
                                <span class="badge bg-{{ $relatorio->status === 'pendente' ? 'warning' : ($relatorio->status === 'em_andamento' ? 'info' : 'success') }}">
                                    {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Prioridade:</span>
                                <span class="badge bg-{{ $relatorio->prioridade === 'baixa' ? 'secondary' : ($relatorio->prioridade === 'media' ? 'warning' : ($relatorio->prioridade === 'alta' ? 'danger' : 'dark')) }}">
                                    {{ ucfirst($relatorio->prioridade) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Progresso:</span>
                                <span class="fw-semibold text-primary">{{ $relatorio->progresso }}%</span>
                            </div>
                        </div>
                        @if($relatorio->imagens && $relatorio->imagens->count() > 0)
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Imagens:</span>
                                    <span class="fw-semibold">{{ $relatorio->imagens->count() }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightning text-primary me-2"></i>
                        Ações
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <!-- Voltar -->
                        <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Voltar à Lista
                        </a>

                        <!-- Editar -->
                        @if($relatorio->editavel && ($relatorio->usuario_id == auth()->id() || auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel))
                            <a href="{{ route('relatorios.edit', $relatorio) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i>Editar Relatório
                            </a>
                        @endif

                        <!-- Duplicar -->
                        <form action="{{ route('relatorios.duplicate', $relatorio) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100">
                                <i class="bi bi-files me-2"></i>Duplicar Relatório
                            </button>
                        </form>

                        <!-- Imprimir -->
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-2"></i>Imprimir
                        </button>

                        <!-- Gerar PDF -->
                        <a href="{{ route('pdf.relatorio', $relatorio) }}" class="btn btn-outline-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2"></i>PDF (DomPDF)
                        </a>

                        <!-- Gerar PDF com Browsershot -->
                        <a href="{{ route('relatorio.pdf.browsershot', $relatorio) }}" class="btn btn-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>PDF Premium
                        </a>

                        <!-- Excluir -->
                        @if($relatorio->usuario_id == auth()->id() || auth()->user()->papel === 'admin' || !auth()->user()->papel)
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-2"></i>Excluir
                            </button>
                        @endif

                        <!-- Toggle Editabilidade (apenas admin) -->
                        @if(auth()->user()->papel === 'admin' || !auth()->user()->papel)
                            <form action="{{ route('relatorios.toggle-editavel', $relatorio) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning w-100">
                                    <i class="bi bi-{{ $relatorio->editavel ? 'lock' : 'unlock' }} me-2"></i>
                                    {{ $relatorio->editavel ? 'Bloquear Edição' : 'Permitir Edição' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
@if($relatorio->usuario_id == auth()->id() || auth()->user()->papel === 'admin' || !auth()->user()->papel)
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este relatório?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Atenção:</strong> Esta ação não pode ser desfeita.
                </div>
                <div class="bg-light p-3 rounded">
                    <strong>Relatório:</strong> {{ $relatorio->titulo }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('relatorios.destroy', $relatorio) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Excluir Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal de Atualização de Progresso -->
@if($relatorio->editavel && ($relatorio->usuario_id == auth()->id() || auth()->user()->papel === 'admin' || auth()->user()->papel === 'supervisor' || !auth()->user()->papel))
<div class="modal fade" id="progressoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bar-chart text-primary me-2"></i>
                    Atualizar Progresso do Relatório
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="progressoModalForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Progresso Atual -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>Progresso Atual:</strong> {{ $relatorio->progresso }}%</span>
                                    <span class="badge bg-{{ $relatorio->status === 'pendente' ? 'warning' : ($relatorio->status === 'em_andamento' ? 'info' : 'success') }}">
                                        {{ $relatorio->status_label }}
                                    </span>
                                </div>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ $relatorio->progresso }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Novo Progresso -->
                        <div class="col-12">
                            <label for="modalProgresso" class="form-label fw-semibold">
                                <i class="bi bi-bar-chart me-1"></i>
                                Novo Progresso: <span id="modalProgressoValue" class="text-primary">{{ $relatorio->progresso }}%</span>
                            </label>
                            <input type="range" class="form-range" id="modalProgresso" name="progresso"
                                   min="0" max="100" step="5" value="{{ $relatorio->progresso }}">
                            <div class="d-flex justify-content-between small text-muted mt-1">
                                <span>0%</span>
                                <span>25%</span>
                                <span>50%</span>
                                <span>75%</span>
                                <span>100%</span>
                            </div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" id="modalProgressBar" style="width: {{ $relatorio->progresso }}%"></div>
                            </div>
                        </div>

                        <!-- Descrição da Atividade -->
                        <div class="col-12">
                            <label for="modalDescricao" class="form-label fw-semibold">
                                <i class="bi bi-card-text me-1"></i>
                                Descrição da Atividade Realizada *
                            </label>
                            <textarea class="form-control" id="modalDescricao" name="descricao" rows="4" 
                                      placeholder="Descreva o que foi realizado, problemas encontrados, próximos passos, etc..." 
                                      required minlength="10" maxlength="1000"></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Mínimo 10 caracteres, máximo 1000. (<span id="descricaoCount">0</span>/1000)
                            </div>
                        </div>

                        <!-- Upload de Imagens -->
                        <div class="col-12">
                            <label for="modalImagens" class="form-label fw-semibold">
                                <i class="bi bi-images me-1"></i>
                                Imagens da Atividade <small class="text-muted">(Opcional)</small>
                            </label>
                            <input type="file" class="form-control" id="modalImagens" name="imagens[]" 
                                   multiple accept="image/*">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Formatos aceitos: JPG, PNG, GIF, WEBP. Máximo 7MB por imagem.
                            </div>
                            
                            <!-- Preview das imagens -->
                            <div id="modalImagePreview" class="row g-2 mt-2" style="display: none;">
                                <div class="col-12">
                                    <small class="text-muted fw-semibold">Imagens selecionadas:</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="modalSubmitBtn">
                        <i class="bi bi-check-circle me-2"></i>Salvar Atualização
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
    .progress-ring {
        transform: rotate(-90deg);
    }
    
    /* Timeline Container */
    .timeline-container {
        position: relative;
        padding: 1.5rem;
    }
    
    /* Criação do Relatório */
    .creation-item {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .timeline-marker-large {
        position: absolute;
        left: -1rem;
        top: 1rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #e9ecef, 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
    }
    
    .timeline-content-large {
        margin-left: 2rem;
    }
    
    .creation-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 2px solid #28a745;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.1);
        position: relative;
    }
    
    .creation-card::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 1rem;
        z-index: -1;
        opacity: 0.1;
    }
    
    .avatar-creator {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    }
    
    .date-time-badge {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Seção de Atualizações */
    .updates-section {
        position: relative;
        margin-top: 1rem;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        position: relative;
    }
    
    .section-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, #dee2e6, transparent);
    }
    
    .section-label {
        background: white;
        color: #6c757d;
        padding: 0.5rem 1rem;
        border: 1px solid #dee2e6;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 500;
        white-space: nowrap;
        margin: 0 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    /* Timeline Principal */
    .updates-section::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, #dee2e6, #f8f9fa);
    }
    
    .timeline-item.update-item {
        position: relative;
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -1rem;
        top: 0.5rem;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef, 0 2px 6px rgba(0,0,0,0.1);
        z-index: 5;
    }
    
    .update-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    .update-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }
    
    .avatar-updater {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
    }
    
    .status-transition {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .update-description {
        background: #f8f9fa;
        border-left: 3px solid #6c757d;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        margin-top: 0.75rem;
    }
    
    .description-content {
        display: flex;
        align-items: flex-start;
        line-height: 1.5;
    }
    
    .update-images {
        background: #f8f9fa;
        border-radius: 0.5rem;
        padding: 0.75rem;
    }
    
    .image-thumb {
        aspect-ratio: 1;
        border-radius: 0.5rem;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .image-thumb:hover {
        transform: scale(1.05);
    }
    
    .image-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-thumb-error {
        aspect-ratio: 1;
        border-radius: 0.5rem;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }
    
    .no-updates {
        padding: 2rem 1rem;
        background: #f8f9fa;
        border-radius: 0.75rem;
        margin: 1rem;
    }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .form-range::-webkit-slider-thumb {
        background: #6f42c1;
    }
    
    .form-range::-moz-range-thumb {
        background: #6f42c1;
        border: none;
    }
    
    /* Estilos para imagens */
    .image-container {
        transition: transform 0.2s ease;
    }
    
    .image-container:hover {
        transform: translateY(-2px);
    }
    
    .image-thumbnail:hover {
        transform: scale(1.05);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .image-container .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    @media (max-width: 768px) {
        .timeline-container {
            padding: 1rem;
        }
        
        .timeline-marker-large {
            left: -0.5rem;
            width: 35px;
            height: 35px;
        }
        
        .timeline-content-large {
            margin-left: 1.5rem;
        }
        
        .creation-card {
            padding: 1rem;
        }
        
        .avatar-creator {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .timeline-marker {
            left: -0.5rem;
            width: 25px;
            height: 25px;
        }
        
        .timeline-item.update-item {
            padding-left: 1.5rem;
        }
        
        .avatar-updater {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        
        .update-card {
            padding: 1rem;
        }
        
        .date-time-badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }
        
        .section-label {
            font-size: 0.8rem;
            padding: 0.375rem 0.75rem;
        }
        
        .image-container .col-6 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal de Progresso
    const progressoModal = document.getElementById('progressoModal');
    const modalForm = document.getElementById('progressoModalForm');
    const modalProgresso = document.getElementById('modalProgresso');
    const modalProgressoValue = document.getElementById('modalProgressoValue');
    const modalProgressBar = document.getElementById('modalProgressBar');
    const modalDescricao = document.getElementById('modalDescricao');
    const modalImagens = document.getElementById('modalImagens');
    const modalImagePreview = document.getElementById('modalImagePreview');
    const descricaoCount = document.getElementById('descricaoCount');
    const submitBtn = document.getElementById('modalSubmitBtn');
    
    // Atualizar valor do progresso no modal
    if (modalProgresso && modalProgressoValue && modalProgressBar) {
        modalProgresso.addEventListener('input', function() {
            modalProgressoValue.textContent = this.value + '%';
            modalProgressBar.style.width = this.value + '%';
        });
    }
    
    // Contador de caracteres
    if (modalDescricao && descricaoCount) {
        modalDescricao.addEventListener('input', function() {
            descricaoCount.textContent = this.value.length;
        });
    }
    
    // Preview de imagens
    if (modalImagens && modalImagePreview) {
        modalImagens.addEventListener('change', function() {
            const files = this.files;
            modalImagePreview.innerHTML = '<div class="col-12"><small class="text-muted fw-semibold">Imagens selecionadas:</small></div>';
            
            if (files.length > 0) {
                modalImagePreview.style.display = 'block';
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = `
                                <div class="col-4 col-md-3">
                                    <img src="${e.target.result}" class="img-fluid rounded shadow-sm" 
                                         style="height: 60px; object-fit: cover;" title="${file.name}">
                                    <small class="d-block text-truncate text-muted mt-1" style="font-size: 0.7rem;">${file.name}</small>
                                </div>
                            `;
                            modalImagePreview.insertAdjacentHTML('beforeend', preview);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                modalImagePreview.style.display = 'none';
            }
        });
    }
    
    // Submit do formulário
    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Salvando...';
            
            fetch(`{{ route('relatorios.updateProgresso', $relatorio) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    
                    // Fechar modal
                    bootstrap.Modal.getInstance(progressoModal).hide();
                    
                    // Redirecionar se necessário ou recarregar
                    if (data.redirect) {
                        setTimeout(() => window.location.href = data.redirect, 1000);
                    } else {
                        setTimeout(() => window.location.reload(), 1000);
                    }
                } else {
                    showAlert('danger', data.message || 'Erro ao salvar atualização');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showAlert('danger', 'Erro ao salvar atualização');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // Reset modal quando fechado
    if (progressoModal) {
        progressoModal.addEventListener('hidden.bs.modal', function() {
            if (modalForm) modalForm.reset();
            if (modalImagePreview) modalImagePreview.style.display = 'none';
            if (descricaoCount) descricaoCount.textContent = '0';
        });
    }

    // Modal de imagem
    window.openImageModal = function(imageUrl, imageName) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-image me-2"></i>${imageName}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="${imageUrl}" class="img-fluid w-100" style="max-height: 70vh;">
                    </div>
                    <div class="modal-footer">
                        <a href="${imageUrl}" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Baixar
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        
        modal.addEventListener('hidden.bs.modal', function() {
            modal.remove();
        });
    };
});

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.querySelector('.container').insertAdjacentHTML('afterbegin', alertHtml);
}
</script>
@endpush 