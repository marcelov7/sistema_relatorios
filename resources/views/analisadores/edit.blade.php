@extends('layouts.app')

@section('title', 'Editar Analisador')

@push('styles')
<style>
    /* Cards de componentes modernos */
    .component-switch-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        cursor: pointer;
    }

    .component-switch-card:hover {
        border-color: #6f42c1;
        box-shadow: 0 8px 25px rgba(111, 66, 193, 0.1);
        transform: translateY(-2px);
    }

    .component-switch-wrapper {
        padding: 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
    }

    /* Ícones dos componentes */
    .component-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .component-icon i {
        font-size: 20px;
        color: white;
    }

    .component-switch-card:hover .component-icon {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
    }

    /* Nome do componente */
    .component-name {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    /* Switch customizado */
    .component-switch {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }

    .component-switch .form-check-input {
        width: 45px;
        height: 22px;
        border: 2px solid #dee2e6;
        border-radius: 25px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .component-switch .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
    }

    .component-switch .form-check-input:not(:checked) {
        background-color: #dc3545;
        border-color: #dc3545;
        box-shadow: 0 0 8px rgba(220, 53, 69, 0.3);
    }

    /* Texto do switch */
    .component-switch .form-check-label {
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .switch-text-on,
    .switch-text-off {
        display: none;
        padding: 3px 6px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .component-switch .form-check-input:checked ~ .form-check-label .switch-text-on {
        display: inline-block;
        background: #d4edda;
        color: #155724;
    }

    .component-switch .form-check-input:not(:checked) ~ .form-check-label .switch-text-off {
        display: inline-block;
        background: #f8d7da;
        color: #721c24;
    }

    /* Indicador de status */
    .component-status {
        position: absolute;
        top: 8px;
        right: 8px;
    }

    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #28a745;
        box-shadow: 0 0 6px rgba(40, 167, 69, 0.5);
        transition: all 0.3s ease;
    }

    .component-switch .form-check-input:not(:checked) ~ .form-check-label ~ .component-status .status-indicator {
        background: #dc3545;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.5);
    }

    /* Estados especiais */
    .component-switch-card.checked {
        border-color: #28a745;
        background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%);
    }

    .component-switch-card.unchecked {
        border-color: #dc3545;
        background: linear-gradient(135deg, #fff8f8 0%, #f5e8e8 100%);
    }

    /* Responsividade para mobile */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }
        .form-check-label.small {
            font-size: 0.8rem;
        }
        .badge {
            font-size: 0.7rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .component-switch-wrapper {
            padding: 1rem;
        }
        
        .component-icon {
            width: 40px;
            height: 40px;
        }
        
        .component-icon i {
            font-size: 16px;
        }
        
        .component-name {
            font-size: 12px;
        }
        
        .component-switch .form-check-input {
            width: 40px;
            height: 20px;
        }
    }
    
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-header h6 {
        font-size: 0.9rem;
    }

    /* Melhorias gerais */
    .card-header.bg-primary {
        background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%) !important;
    }
    
    .card-header.bg-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">
                    <i class="bi bi-house me-1"></i>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analisadores.index') }}">Analisadores</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('analisadores.show', $analisador) }}">
                    {{ $analisador->analyzer }} #{{ $analisador->id }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Editar</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-2 mb-lg-0">
            <h1 class="h4 mb-1">
                <i class="bi bi-pencil me-2"></i>
                Editando: {{ $analisador->analyzer }}
                @if($analisador->ativo)
                    <span class="badge bg-success ms-2">Ativo</span>
                @else
                    <span class="badge bg-danger ms-2">Inativo</span>
                @endif
            </h1>
            <div class="d-flex flex-wrap gap-2 align-items-center text-muted small">
                <span>ID: #{{ $analisador->id }}</span>
                <span>{{ $analisador->usuario->name ?? 'N/A' }}</span>
                <span>{{ $analisador->check_date->format('d/m/Y') }}</span>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('analisadores.show', $analisador) }}" class="btn btn-info btn-sm">
                <i class="bi bi-eye me-1"></i>
                Visualizar
            </a>
            <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('analisadores.update', $analisador) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Formulário Principal -->
            <div class="col-lg-8">
                <!-- Informações Básicas -->
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
                                <label for="analyzer" class="form-label fw-semibold mb-1">Tipo de Analisador:</label>
                                <select class="form-select @error('analyzer') is-invalid @enderror" id="analyzer" name="analyzer" required>
                                    <option value="">Selecione o tipo</option>
                                    @foreach($tiposAnalisadores as $key => $tipo)
                                        <option value="{{ $key }}" {{ old('analyzer', $analisador->analyzer) == $key ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('analyzer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="check_date" class="form-label fw-semibold mb-1">Data da Verificação:</label>
                                <input type="date" class="form-control @error('check_date') is-invalid @enderror" 
                                       id="check_date" name="check_date" 
                                       value="{{ old('check_date', $analisador->check_date->format('Y-m-d')) }}" required>
                                @error('check_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dos Componentes -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-list-check me-2"></i>
                            Status dos Componentes
                            <span class="badge bg-light text-dark ms-2">
                                {{ collect($componentes)->filter(function($nome, $key) use ($analisador) { return $analisador->$key; })->count() }}/{{ count($componentes) }} funcionando
                            </span>
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-4">
                            @php
                                $componentIcons = [
                                    'acid_filter' => 'bi-funnel',
                                    'gas_dryer' => 'bi-wind',
                                    'paper_filter' => 'bi-file-earmark',
                                    'peristaltic_pump' => 'bi-arrow-repeat',
                                    'rotameter' => 'bi-speedometer2',
                                    'disposable_filter' => 'bi-trash',
                                    'blocking_filter' => 'bi-shield-check'
                                ];
                            @endphp
                            
                            @foreach($componentes as $key => $nome)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="component-switch-card {{ $analisador->$key ? 'checked' : 'unchecked' }}">
                                        <div class="component-switch-wrapper">
                                            <!-- Campo hidden para garantir que o valor false seja enviado -->
                                            <input type="hidden" name="{{ $key }}" value="0">
                                            
                                            <div class="component-icon">
                                                <i class="bi {{ $componentIcons[$key] ?? 'bi-gear' }}"></i>
                                            </div>
                                            
                                            <div class="component-info">
                                                <h6 class="component-name">{{ $nome }}</h6>
                                                <div class="component-switch">
                                                    <input class="form-check-input @error($key) is-invalid @enderror" 
                                                           type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1"
                                                           {{ old($key, $analisador->$key) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $key }}">
                                                        <span class="switch-text-on">Funcionando</span>
                                                        <span class="switch-text-off">Com Problema</span>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="component-status">
                                                <span class="status-indicator"></span>
                                            </div>
                                        </div>
                                        @error($key)
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Medições Ambientais -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-thermometer me-2"></i>
                            Medições Ambientais
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="room_temperature" class="form-label fw-semibold mb-1">
                                    <i class="bi bi-thermometer-half text-info me-1"></i>
                                    Temperatura (°C):
                                </label>
                                <input type="number" step="0.01" min="-50" max="100" 
                                       class="form-control @error('room_temperature') is-invalid @enderror" 
                                       id="room_temperature" name="room_temperature" 
                                       value="{{ old('room_temperature', $analisador->room_temperature) }}" 
                                       placeholder="Ex: 25.5">
                                <small class="form-text text-muted">Faixa: -50°C a 100°C</small>
                                @error('room_temperature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="air_pressure" class="form-label fw-semibold mb-1">
                                    <i class="bi bi-speedometer2 text-secondary me-1"></i>
                                    Pressão (bar):
                                </label>
                                <input type="number" step="0.01" min="0" max="10" 
                                       class="form-control @error('air_pressure') is-invalid @enderror" 
                                       id="air_pressure" name="air_pressure" 
                                       value="{{ old('air_pressure', $analisador->air_pressure) }}" 
                                       placeholder="Ex: 1.01">
                                <small class="form-text text-muted">Faixa: 0 a 10 bar</small>
                                @error('air_pressure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações e Imagem -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-chat-text me-2"></i>
                            Observações e Configurações
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <!-- Observações -->
                        <div class="mb-3">
                            <label for="observation" class="form-label fw-semibold mb-1">Observações:</label>
                            <textarea class="form-control @error('observation') is-invalid @enderror" 
                                      id="observation" name="observation" rows="3" 
                                      placeholder="Descreva observações importantes...">{{ old('observation', $analisador->observation) }}</textarea>
                            @error('observation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <!-- Upload de Imagem -->
                            <div class="col-md-8">
                                <label for="image" class="form-label fw-semibold mb-1">Imagem:</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <div class="form-text small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    JPEG, PNG, JPG, GIF, WEBP. Máx: 7MB
                                    @if($analisador->image)
                                        <br><strong>Atual:</strong> {{ basename($analisador->image) }}
                                    @endif
                                </div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status Ativo/Inativo -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-1">Status:</label>
                                <div class="p-2 bg-light rounded">
                                    <div class="form-check form-switch">
                                        <!-- Campo hidden para garantir que o valor false seja enviado -->
                                        <input type="hidden" name="ativo" value="0">
                                        <input class="form-check-input @error('ativo') is-invalid @enderror" 
                                               type="checkbox" id="ativo" name="ativo" value="1" 
                                               {{ old('ativo', $analisador->ativo) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold small" for="ativo">
                                            <i class="bi bi-power me-1"></i>
                                            Ativo
                                        </label>
                                        @error('ativo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('analisadores.show', $analisador) }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-eye me-1"></i>
                        Visualizar
                    </a>
                    <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i>
                        Atualizar
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informações do Analisador -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle me-2"></i>
                            Informações do Analisador
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <strong class="text-muted small">Tipo:</strong><br>
                            <span class="badge bg-primary">
                                <i class="bi bi-gear me-1"></i>
                                {{ $analisador->analyzer }}
                            </span>
                        </div>
                        
                        <div class="mb-2">
                            <strong class="text-muted small">Última Verificação:</strong><br>
                            <span class="fw-semibold">{{ $analisador->check_date->format('d/m/Y') }}</span>
                            <small class="text-muted d-block">({{ $analisador->check_date->diffForHumans() }})</small>
                        </div>
                        
                        <div class="mb-2">
                            <strong class="text-muted small">Responsável:</strong><br>
                            <span class="fw-semibold">{{ $analisador->usuario->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="mb-2">
                            <strong class="text-muted small">Status:</strong><br>
                            @if($analisador->ativo)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Ativo
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Inativo
                                </span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <strong class="text-muted small">Componentes:</strong><br>
                            @if($analisador->todos_componentes_ok)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-all me-1"></i>
                                    Todos OK
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $analisador->componentes_com_problema }} problema(s)
                                </span>
                            @endif
                        </div>
                        
                        @if($analisador->room_temperature || $analisador->air_pressure)
                            <hr class="my-2">
                            <div class="small">
                                <strong class="text-muted">Medições Atuais:</strong>
                                @if($analisador->room_temperature)
                                    <div class="d-flex justify-content-between mt-1">
                                        <span>Temperatura:</span>
                                        <span class="fw-semibold">{{ $analisador->room_temperature }}°C</span>
                                    </div>
                                @endif
                                @if($analisador->air_pressure)
                                    <div class="d-flex justify-content-between mt-1">
                                        <span>Pressão:</span>
                                        <span class="fw-semibold">{{ $analisador->air_pressure }} bar</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Imagem Atual -->
                @if($analisador->image)
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-image me-2"></i>
                                Imagem Atual
                            </h6>
                        </div>
                        <div class="card-body p-3 text-center">
                            <img src="{{ Storage::url($analisador->image) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-width: 100%; max-height: 150px;"
                                 alt="Imagem do Analisador">
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-file-image me-1"></i>
                                    {{ basename($analisador->image) }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para atualizar visual dos cards baseado no estado do switch
    function updateComponentCard(checkbox) {
        const card = checkbox.closest('.component-switch-card');
        
        if (checkbox.checked) {
            card.classList.remove('unchecked');
            card.classList.add('checked');
        } else {
            card.classList.remove('checked');
            card.classList.add('unchecked');
        }
    }

    // Função para atualizar contador de componentes
    function updateComponentCounter() {
        const checkboxes = document.querySelectorAll('.component-switch input[type="checkbox"]');
        const checkedCount = document.querySelectorAll('.component-switch input[type="checkbox"]:checked').length;
        const totalCount = checkboxes.length;
        
        // Atualizar badge no header
        const badge = document.querySelector('.card-header.bg-success .badge');
        if (badge) {
            badge.textContent = `${checkedCount}/${totalCount} funcionando`;
        }
    }

    // Inicializar estado dos cards
    const checkboxes = document.querySelectorAll('.component-switch input[type="checkbox"]');
    checkboxes.forEach(function(checkbox) {
        // Definir estado inicial
        updateComponentCard(checkbox);
        
        // Adicionar listener para mudanças
        checkbox.addEventListener('change', function() {
            updateComponentCard(this);
            updateComponentCounter();
            
            // Efeito visual de feedback
            const card = this.closest('.component-switch-card');
            card.style.transform = 'scale(0.98)';
            setTimeout(() => {
                card.style.transform = '';
            }, 150);
        });
    });

    // Adicionar efeito de clique nos cards (clicar no card alterna o switch)
    document.querySelectorAll('.component-switch-card').forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Evitar duplo clique se já clicou no checkbox ou label
            if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL' || e.target.closest('.form-check-label')) {
                return;
            }
            
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // Preview da nova imagem
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Criar ou atualizar preview
                let previewCard = document.getElementById('new-image-preview');
                if (!previewCard) {
                    previewCard = document.createElement('div');
                    previewCard.id = 'new-image-preview';
                    previewCard.className = 'card mt-3';
                    previewCard.innerHTML = `
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-image me-2"></i>
                                Nova Imagem (Preview)
                            </h6>
                        </div>
                        <div class="card-body p-3 text-center">
                            <img id="new-image-display" class="img-fluid rounded shadow" 
                                 style="max-width: 100%; max-height: 150px;" alt="Nova Imagem">
                            <div class="mt-2">
                                <small class="text-muted" id="new-image-name"></small>
                            </div>
                        </div>
                    `;
                    
                    // Inserir após a sidebar
                    const sidebar = document.querySelector('.col-lg-4');
                    if (sidebar) {
                        sidebar.appendChild(previewCard);
                    }
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('new-image-display');
                    const nameSpan = document.getElementById('new-image-name');
                    
                    if (img) img.src = e.target.result;
                    if (nameSpan) nameSpan.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                // Remover preview se não há arquivo
                const previewCard = document.getElementById('new-image-preview');
                if (previewCard) {
                    previewCard.remove();
                }
            }
        });
    }

    // Validação em tempo real
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Verificar campos obrigatórios
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll para o primeiro erro
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // Inicializar contador
    updateComponentCounter();
});
</script>
@endpush 