@extends('layouts.app')

@section('title', ($motor->equipment ?? 'Motor') . ' - Motores')

@section('content')
<div class="container">


    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-2 mb-lg-0">
            <h1 class="h4 mb-1">
                <i class="bi bi-gear-wide-connected me-2"></i>
                {{ $motor->equipment ?? 'Motor #' . $motor->id }}
                @if($motor->tag)
                    <span class="badge bg-primary ms-2">{{ $motor->tag }}</span>
                @endif
            </h1>
            <div class="d-flex flex-wrap gap-2 align-items-center text-muted small">
                <span>ID: #{{ $motor->id }}</span>
                @if($motor->manufacturer)
                    <span>{{ $motor->manufacturer }}</span>
                @endif
                @if($motor->equipment_type)
                    <span>{{ $motor->equipment_type }}</span>
                @endif
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('motores.edit', $motor) }}" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>
                Editar
            </a>
            <a href="{{ route('pdf.inspecao', $motor) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                PDF
            </a>
            <a href="{{ route('motores.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informações Principais -->
        <div class="col-lg-8">
            <!-- Dados Básicos -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>
                        Informações Básicas
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong class="text-muted small">Equipamento:</strong><br>
                            <span class="fw-semibold">{{ $motor->equipment ?? 'N/A' }}</span>
                        </div>
                        
                        @if($motor->tag)
                            <div class="col-md-6">
                                <strong class="text-muted small">Tag:</strong><br>
                                <span class="badge bg-primary">{{ $motor->tag }}</span>
                            </div>
                        @endif
                        
                        @if($motor->equipment_type)
                            <div class="col-md-6">
                                <strong class="text-muted small">Tipo de Equipamento:</strong><br>
                                <span class="fw-semibold">{{ $motor->equipment_type }}</span>
                            </div>
                        @endif
                        
                        @if($motor->manufacturer)
                            <div class="col-md-6">
                                <strong class="text-muted small">Fabricante:</strong><br>
                                <span class="fw-semibold">{{ $motor->manufacturer }}</span>
                            </div>
                        @endif
                        
                        @if($motor->frame_manufacturer)
                            <div class="col-md-6">
                                <strong class="text-muted small">Fabricante do Frame:</strong><br>
                                <span class="fw-semibold">{{ $motor->frame_manufacturer }}</span>
                            </div>
                        @endif
                        
                        @if($motor->location)
                            <div class="col-md-6">
                                <strong class="text-muted small">Localização:</strong><br>
                                <span class="fw-semibold">
                                    <i class="bi bi-geo-alt text-primary me-1"></i>
                                    {{ $motor->location }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Especificações Técnicas -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-lightning me-2"></i>
                        Especificações Técnicas
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @if($motor->power_kw || $motor->power_cv)
                            <div class="col-md-6">
                                <strong class="text-muted small">Potência:</strong><br>
                                <span class="fw-semibold text-success">
                                    <i class="bi bi-lightning me-1"></i>
                                    {{ $motor->power_display }}
                                </span>
                            </div>
                        @endif
                        
                        @if($motor->rotation)
                            <div class="col-md-6">
                                <strong class="text-muted small">Rotação:</strong><br>
                                <span class="fw-semibold text-info">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    {{ $motor->rotation_display }}
                                </span>
                            </div>
                        @endif
                        
                        @if($motor->rated_current)
                            <div class="col-md-6">
                                <strong class="text-muted small">Corrente Nominal:</strong><br>
                                <span class="fw-semibold">{{ $motor->rated_current }}A</span>
                            </div>
                        @endif
                        
                        @if($motor->configured_current)
                            <div class="col-md-6">
                                <strong class="text-muted small">Corrente Configurada:</strong><br>
                                <span class="fw-semibold">{{ $motor->configured_current }}A</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estoque e Armazenamento -->
            @if($motor->stock_reserve || $motor->storage)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-box-seam me-2"></i>
                            Estoque e Armazenamento
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @if($motor->stock_reserve)
                                <div class="col-md-6">
                                    <strong class="text-muted small">Estoque Reserva:</strong><br>
                                    <span class="badge bg-{{ $motor->stock_reserve == 'Sim' ? 'success' : ($motor->stock_reserve == 'Não' ? 'danger' : 'warning') }}">
                                        {{ $motor->stock_reserve }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($motor->storage)
                                <div class="col-md-6">
                                    <strong class="text-muted small">Armazenamento:</strong><br>
                                    <span class="fw-semibold">{{ $motor->storage }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resumo -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Resumo Técnico
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <strong class="text-muted small">Potência Total:</strong><br>
                        <span class="fw-semibold text-success">{{ $motor->power_display }}</span>
                    </div>
                    
                    @if($motor->rotation)
                        <div class="mb-2">
                            <strong class="text-muted small">Rotação:</strong><br>
                            <span class="fw-semibold text-info">{{ $motor->rotation_display }}</span>
                        </div>
                    @endif
                    
                    @if($motor->rated_current || $motor->configured_current)
                        <div class="mb-2">
                            <strong class="text-muted small">Correntes:</strong><br>
                            <span class="fw-semibold">{{ $motor->current_display }}</span>
                        </div>
                    @endif
                    
                    <hr class="my-2">
                    
                    <div class="small">
                        <strong class="text-muted">Criado em:</strong><br>
                        <span>{{ $motor->data_criacao->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    @if($motor->data_atualizacao != $motor->data_criacao)
                        <div class="small mt-1">
                            <strong class="text-muted">Atualizado em:</strong><br>
                            <span>{{ $motor->data_atualizacao->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Foto -->
            @if($motor->photo)
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-camera me-2"></i>
                            Foto do Motor
                        </h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        <img src="{{ Storage::url($motor->photo) }}" 
                             class="img-fluid rounded shadow" 
                             style="max-width: 100%; max-height: 300px;"
                             alt="Foto do Motor">
                    </div>
                </div>
            @endif

            <!-- Ações -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-tools me-2"></i>
                        Ações
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('motores.edit', $motor) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-1"></i>
                            Editar Motor
                        </a>
                        
                        <form action="{{ route('motores.duplicate', $motor) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-info btn-sm w-100">
                                <i class="bi bi-files me-1"></i>
                                Duplicar Motor
                            </button>
                        </form>
                        
                        <hr class="my-2">
                        
                        <form action="{{ route('motores.destroy', $motor) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este motor?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash me-1"></i>
                                Excluir Motor
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 