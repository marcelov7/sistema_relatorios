@extends('layouts.app')

@section('title', 'Novo Analisador')

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
    }

    .component-switch-card:hover {
        border-color: #6f42c1;
        box-shadow: 0 8px 25px rgba(111, 66, 193, 0.1);
        transform: translateY(-2px);
    }

    .component-switch-wrapper {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
    }

    /* Ícones dos componentes */
    .component-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .component-icon i {
        font-size: 24px;
        color: white;
    }

    .component-switch-card:hover .component-icon {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
    }

    /* Nome do componente */
    .component-name {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }

    /* Switch customizado */
    .component-switch {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .component-switch .form-check-input {
        width: 50px;
        height: 25px;
        border: 2px solid #dee2e6;
        border-radius: 25px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .component-switch .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
    }

    .component-switch .form-check-input:not(:checked) {
        background-color: #dc3545;
        border-color: #dc3545;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
    }

    /* Texto do switch */
    .component-switch .form-check-label {
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .switch-text-on,
    .switch-text-off {
        display: none;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 11px;
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
        top: 10px;
        right: 10px;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.5);
        transition: all 0.3s ease;
    }

    .component-switch .form-check-input:not(:checked) ~ .form-check-label ~ .component-status .status-indicator {
        background: #dc3545;
        box-shadow: 0 0 8px rgba(220, 53, 69, 0.5);
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

    /* Responsividade */
    @media (max-width: 576px) {
        .component-switch-wrapper {
            padding: 1rem;
        }
        
        .component-icon {
            width: 50px;
            height: 50px;
        }
        
        .component-icon i {
            font-size: 20px;
        }
        
        .component-name {
            font-size: 13px;
        }
    }

    /* Melhorias gerais */
    .card-header.bg-primary {
        background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%) !important;
    }

    .alert-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196f3;
    }
</style>
@endpush

@section('content')
<div class="container">
    

    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="bi bi-plus-circle me-2"></i>
                        Novo Analisador
                    </h1>
                    <p class="text-muted mb-0">Cadastre um novo analisador no sistema</p>
                </div>
                <div>
                    <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('analisadores.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Informações Básicas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear-wide-connected me-2"></i>
                            Informações Básicas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="analyzer" class="form-label">Tipo de Analisador <span class="text-danger">*</span></label>
                                <select class="form-select @error('analyzer') is-invalid @enderror" id="analyzer" name="analyzer" required>
                                    <option value="">Selecione o tipo</option>
                                    @foreach($tiposAnalisadores as $key => $tipo)
                                        <option value="{{ $key }}" {{ old('analyzer') == $key ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('analyzer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="check_date" class="form-label">Data da Verificação <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('check_date') is-invalid @enderror" 
                                       id="check_date" name="check_date" value="{{ old('check_date', date('Y-m-d')) }}" required>
                                @error('check_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dos Componentes -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            Status dos Componentes
                        </h5>
                        <small class="opacity-75">Marque os componentes que estão funcionando corretamente</small>
                    </div>
                    <div class="card-body p-4">
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
                                    <div class="component-switch-card">
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
                                                           {{ old($key, true) ? 'checked' : '' }}>
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
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle me-2 fs-5"></i>
                                        <div>
                                            <strong>Dica:</strong> Por padrão, todos os componentes começam marcados como funcionando. 
                                            Desmarque apenas os que apresentam problemas ou necessitam manutenção.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medições Ambientais -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-thermometer me-2"></i>
                            Medições Ambientais
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="room_temperature" class="form-label">
                                    <i class="bi bi-thermometer-half text-info me-1"></i>
                                    Temperatura do Ambiente (°C)
                                </label>
                                <input type="number" step="0.01" min="-50" max="100" 
                                       class="form-control @error('room_temperature') is-invalid @enderror" 
                                       id="room_temperature" name="room_temperature" 
                                       value="{{ old('room_temperature') }}" placeholder="Ex: 25.5">
                                <small class="form-text text-muted">Faixa: -50°C a 100°C</small>
                                @error('room_temperature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="air_pressure" class="form-label">
                                    <i class="bi bi-speedometer2 text-secondary me-1"></i>
                                    Pressão do Ar (bar)
                                </label>
                                <input type="number" step="0.01" min="0" max="10" 
                                       class="form-control @error('air_pressure') is-invalid @enderror" 
                                       id="air_pressure" name="air_pressure" 
                                       value="{{ old('air_pressure') }}" placeholder="Ex: 1.01">
                                <small class="form-text text-muted">Faixa: 0 a 10 bar</small>
                                @error('air_pressure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações e Configurações -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-text me-2"></i>
                            Observações e Configurações
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Observações -->
                        <div class="mb-4">
                            <label for="observation" class="form-label">Observações</label>
                            <textarea class="form-control @error('observation') is-invalid @enderror" 
                                      id="observation" name="observation" rows="4" 
                                      placeholder="Descreva observações importantes sobre a verificação...">{{ old('observation') }}</textarea>
                            @error('observation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de Imagem -->
                        <div class="mb-4">
                            <label for="image" class="form-label">
                                <i class="bi bi-image me-1"></i>
                                Imagem do Analisador
                            </label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Formatos aceitos: JPEG, PNG, JPG, GIF, WEBP. Tamanho máximo: 7MB
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Ativo/Inativo -->
                        <div class="p-3 bg-light rounded">
                            <div class="form-check form-switch">
                                <!-- Campo hidden para garantir que o valor false seja enviado -->
                                <input type="hidden" name="ativo" value="0">
                                <input class="form-check-input @error('ativo') is-invalid @enderror" 
                                       type="checkbox" id="ativo" name="ativo" value="1" 
                                       {{ old('ativo', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="ativo">
                                    <i class="bi bi-power me-1"></i>
                                    Analisador Ativo
                                </label>
                                @error('ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('analisadores.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                Salvar Analisador
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar de Ajuda -->
            <div class="col-lg-4">
                <!-- Ajuda -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Ajuda
                        </h6>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary">Tipos de Analisadores</h6>
                        <ul class="list-unstyled mb-3">
                            <li><strong>Torre:</strong> Analisador para torres de resfriamento</li>
                            <li><strong>Chaminé:</strong> Analisador para chaminés industriais</li>
                            <li><strong>Caixa de Fumaça:</strong> Analisador para caixas de fumaça</li>
                        </ul>

                        <h6 class="text-primary">Componentes</h6>
                        <p class="text-muted small mb-3">
                            Marque os componentes que estão funcionando corretamente. 
                            Componentes desmarcados indicam problemas que precisam de atenção.
                        </p>

                        <h6 class="text-primary">Medições</h6>
                        <p class="text-muted small mb-3">
                            As medições ambientais são opcionais, mas importantes para 
                            acompanhar as condições de operação do analisador.
                        </p>

                        <div class="alert alert-info">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Dica:</strong> Por padrão, todos os componentes começam marcados 
                            como funcionando. Desmarque apenas os que apresentam problemas.
                        </div>
                    </div>
                </div>

                <!-- Preview da Imagem -->
                <div class="card" id="imagePreviewCard" style="display: none;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-image me-2"></i>
                            Preview da Imagem
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
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
        const statusIndicator = card.querySelector('.status-indicator');
        
        if (checkbox.checked) {
            card.classList.remove('unchecked');
            card.classList.add('checked');
        } else {
            card.classList.remove('checked');
            card.classList.add('unchecked');
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
        
        // Adicionar cursor pointer para indicar que é clicável
        card.style.cursor = 'pointer';
    });

    // Preview da imagem
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewCard = document.getElementById('imagePreviewCard');
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewCard.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewCard.style.display = 'none';
            }
        });
    }

    // Contador de componentes com problema
    function updateComponentCounter() {
        const problemComponents = document.querySelectorAll('.component-switch input[type="checkbox"]:not(:checked)').length;
        const totalComponents = document.querySelectorAll('.component-switch input[type="checkbox"]').length;
        const workingComponents = totalComponents - problemComponents;
        
        // Atualizar algum contador se existir
        const counterElement = document.getElementById('component-counter');
        if (counterElement) {
            counterElement.textContent = `${workingComponents}/${totalComponents} funcionando`;
        }
    }

    // Adicionar contador se não existir
    const componentHeader = document.querySelector('.card-header.bg-primary');
    if (componentHeader && !document.getElementById('component-counter')) {
        const counter = document.createElement('div');
        counter.id = 'component-counter';
        counter.className = 'opacity-75 small';
        componentHeader.appendChild(counter);
        updateComponentCounter();
        
        // Atualizar contador quando switches mudarem
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateComponentCounter);
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

    // Animação de entrada para os cards
    const componentCards = document.querySelectorAll('.component-switch-card');
    componentCards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush 