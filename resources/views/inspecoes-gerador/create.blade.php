@extends('layouts.app')

@section('title', 'Nova Inspeção de Gerador - Sistema de Relatórios')

@push('styles')
<style>
    .form-section {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f8f9fa;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
        font-size: 1.2rem;
    }

    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }

    .btn-primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .btn-secondary {
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .alert {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .page-header h1 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 600;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    /* Status Cards */
    .status-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .status-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .status-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }

    .status-card:hover::before {
        transform: scaleX(1);
    }

    .status-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
    }

    .status-card.selected::before {
        transform: scaleX(1);
    }

    .status-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .status-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
        color: white;
    }

    .status-card-title {
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
        font-size: 1.1rem;
    }

    .status-options {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .status-option {
        flex: 1;
        min-width: 80px;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 0.75rem;
        background: white;
        text-align: center;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .status-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .status-option:hover::before {
        left: 100%;
    }

    .status-option:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .status-option.selected {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .status-option input[type="radio"] {
        display: none;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .form-section {
            padding: 1rem;
        }

        .status-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .status-card {
            padding: 1rem;
        }

        .status-card-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .status-options {
            flex-direction: column;
        }

        .status-option {
            min-width: auto;
        }
    }

    .is-warning {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
    }

    .parameter-feedback {
        font-size: 0.875rem;
    }

    .form-control:focus.is-warning {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .btn-example {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50px;
        padding: 10px 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>
                    <i class="bi bi-lightning-charge me-2"></i>
                    Nova Inspeção de Gerador
                </h1>
                <p>Registre uma nova inspeção com todas as medições e observações necessárias</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-plus-circle" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="bi bi-exclamation-triangle me-2"></i>Erro de Validação</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inspecoes-gerador.store') }}" method="POST">
        @csrf
        <input type="hidden" name="ativo" value="1">

        <!-- Informações Básicas -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-info-circle"></i>
                Informações Básicas
            </h5>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="data" class="form-label">Data da Inspeção <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="data" name="data" 
                           value="{{ old('data', date('Y-m-d')) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="colaborador" class="form-label">Colaborador <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="colaborador" name="colaborador" 
                           value="{{ old('colaborador', auth()->user()->name) }}" required
                           placeholder="Nome do colaborador responsável">
                    <small class="form-text text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Nome do colaborador que realizou a inspeção
                    </small>
                </div>
            </div>
        </div>

        <!-- Níveis -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-droplet"></i>
                Níveis
            </h5>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nivel_oleo" class="form-label">Nível de Óleo <span class="text-danger">*</span></label>
                    <select class="form-select" id="nivel_oleo" name="nivel_oleo" required>
                        <option value="">Selecione o nível...</option>
                        <option value="Máximo" {{ old('nivel_oleo') == 'Máximo' ? 'selected' : '' }}>Máximo</option>
                        <option value="Normal" {{ old('nivel_oleo') == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Baixo" {{ old('nivel_oleo') == 'Baixo' ? 'selected' : '' }}>Baixo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nivel_agua" class="form-label">Nível de Água <span class="text-danger">*</span></label>
                    <select class="form-select" id="nivel_agua" name="nivel_agua" required>
                        <option value="">Selecione o nível...</option>
                        <option value="Máximo" {{ old('nivel_agua') == 'Máximo' ? 'selected' : '' }}>Máximo</option>
                        <option value="Normal" {{ old('nivel_agua') == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Baixo" {{ old('nivel_agua') == 'Baixo' ? 'selected' : '' }}>Baixo</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tensões -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-lightning"></i>
                Tensões (V)
            </h5>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tensao_sync_gerador" class="form-label">Tensão Sync Gerador</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_sync_gerador" 
                           name="tensao_sync_gerador" value="{{ old('tensao_sync_gerador') }}" 
                           placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="tensao_sync_rede" class="form-label">Tensão Sync Rede</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_sync_rede" 
                           name="tensao_sync_rede" value="{{ old('tensao_sync_rede') }}" 
                           placeholder="0.00">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tensao_a" class="form-label">Tensão A</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_a" 
                           name="tensao_a" value="{{ old('tensao_a') }}" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="tensao_b" class="form-label">Tensão B</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_b" 
                           name="tensao_b" value="{{ old('tensao_b') }}" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="tensao_c" class="form-label">Tensão C</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_c" 
                           name="tensao_c" value="{{ old('tensao_c') }}" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tensao_bateria" class="form-label">Tensão Bateria</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_bateria" 
                           name="tensao_bateria" value="{{ old('tensao_bateria') }}" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="tensao_alternador" class="form-label">Tensão Alternador</label>
                    <input type="number" step="0.01" class="form-control" id="tensao_alternador" 
                           name="tensao_alternador" value="{{ old('tensao_alternador') }}" placeholder="0.00">
                </div>
            </div>
        </div>

        <!-- Medições -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-speedometer2"></i>
                Medições
            </h5>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="temp_agua" class="form-label">Temperatura da Água (°C)</label>
                    <input type="number" step="0.01" class="form-control" id="temp_agua" 
                           name="temp_agua" value="{{ old('temp_agua') }}" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="pressao_oleo" class="form-label">Pressão do Óleo (bar)</label>
                    <input type="number" step="0.01" class="form-control" id="pressao_oleo" 
                           name="pressao_oleo" value="{{ old('pressao_oleo') }}" placeholder="0.00">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="frequencia" class="form-label">Frequência (Hz)</label>
                    <input type="number" step="0.01" class="form-control" id="frequencia" 
                           name="frequencia" value="{{ old('frequencia') }}" placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="rpm" class="form-label">RPM</label>
                    <input type="number" class="form-control" id="rpm" 
                           name="rpm" value="{{ old('rpm') }}" placeholder="0">
                </div>
            </div>
        </div>

        <!-- Status e Verificações -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-check-circle"></i>
                Status e Verificações
            </h5>
            
            <div class="status-cards">
                <!-- Card Combustível -->
                <div class="status-card" data-field="combustivel_50">
                    <div class="status-card-header">
                        <div class="status-card-icon">
                            <i class="bi bi-fuel-pump"></i>
                        </div>
                        <div>
                            <h6 class="status-card-title">Combustível > 50%</h6>
                            <small class="text-muted">Verificar nível do tanque</small>
                        </div>
                    </div>
                    <div class="status-options">
                        <label class="status-option {{ old('combustivel_50') == 'Sim' ? 'selected' : '' }}">
                            <input type="radio" name="combustivel_50" value="Sim" {{ old('combustivel_50') == 'Sim' ? 'checked' : '' }}>
                            <i class="bi bi-check-circle me-1"></i>
                            Sim
                        </label>
                        <label class="status-option {{ old('combustivel_50') == 'Não' ? 'selected' : '' }}">
                            <input type="radio" name="combustivel_50" value="Não" {{ old('combustivel_50') == 'Não' ? 'checked' : '' }}>
                            <i class="bi bi-x-circle me-1"></i>
                            Não
                        </label>
                    </div>
                </div>

                <!-- Card Iluminação -->
                <div class="status-card" data-field="iluminacao_sala">
                    <div class="status-card-header">
                        <div class="status-card-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>
                        <div>
                            <h6 class="status-card-title">Iluminação da Sala <span class="text-danger">*</span></h6>
                            <small class="text-muted">Condição da iluminação</small>
                        </div>
                    </div>
                    <div class="status-options">
                        <label class="status-option {{ old('iluminacao_sala') == 'Normal' ? 'selected' : '' }}">
                            <input type="radio" name="iluminacao_sala" value="Normal" {{ old('iluminacao_sala') == 'Normal' ? 'checked' : '' }} required>
                            <i class="bi bi-check-circle me-1"></i>
                            Normal
                        </label>
                        <label class="status-option {{ old('iluminacao_sala') == 'Anormal' ? 'selected' : '' }}">
                            <input type="radio" name="iluminacao_sala" value="Anormal" {{ old('iluminacao_sala') == 'Anormal' ? 'checked' : '' }}>
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Anormal
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="form-section">
            <h5 class="section-title">
                <i class="bi bi-chat-text"></i>
                Observações
            </h5>
            
            <div class="form-group">
                <label for="observacao" class="form-label">Observações Gerais</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="4" 
                          placeholder="Descreva observações importantes sobre a inspeção...">{{ old('observacao') }}</textarea>
            </div>
        </div>

        <!-- Botões de Ação -->
        <div class="form-section">
            <div class="d-flex justify-content-between">
                <a href="{{ route('inspecoes-gerador.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Voltar
                </a>
                
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Salvar Inspeção
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Botão flutuante para valores de exemplo -->
<button type="button" class="btn btn-info btn-example" onclick="preencherValoresIdeais()" title="Preencher com valores ideais de exemplo">
    <i class="bi bi-lightbulb me-1"></i>
    Exemplo
</button>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus no primeiro campo
        document.getElementById('data').focus();
        
        // Parâmetros normais para validação
        const parametrosNormais = {
            tensao_sync_gerador: { min: 11.0, max: 13.0, ideal: 12.0, unidade: 'V' },
            tensao_sync_rede: { min: 11.0, max: 13.0, ideal: 12.0, unidade: 'V' },
            tensao_a: { min: 210.0, max: 240.0, ideal: 227.0, unidade: 'V' },
            tensao_b: { min: 210.0, max: 240.0, ideal: 227.0, unidade: 'V' },
            tensao_c: { min: 210.0, max: 240.0, ideal: 227.0, unidade: 'V' },
            tensao_bateria: { min: 20.0, max: 26.0, ideal: 24.0, unidade: 'V' },
            tensao_alternador: { min: 100.0, max: 120.0, ideal: 111.0, unidade: 'V' },
            temp_agua: { min: 20.0, max: 90.0, ideal: 80.0, critico_max: 95.0, unidade: '°C' },
            pressao_oleo: { min: 3.0, max: 6.0, ideal: 4.5, critico_min: 2.0, unidade: 'bar' },
            frequencia: { min: 59.5, max: 60.5, ideal: 60.0, unidade: 'Hz' },
            rpm: { min: 1750, max: 1850, ideal: 1800, unidade: 'RPM' }
        };

        // Função para validar um campo
        function validarCampo(campo, valor) {
            const config = parametrosNormais[campo];
            if (!config || valor === '' || valor === null) return null;

            valor = parseFloat(valor);
            
            // Verificar se está na faixa crítica
            if (config.critico_min && valor < config.critico_min) {
                return { status: 'critico', classe: 'is-invalid', mensagem: 'CRÍTICO - Abaixo do mínimo seguro' };
            }
            if (config.critico_max && valor > config.critico_max) {
                return { status: 'critico', classe: 'is-invalid', mensagem: 'CRÍTICO - Acima do máximo seguro' };
            }

            // Verificar se está na faixa normal
            if (valor >= config.min && valor <= config.max) {
                return { status: 'normal', classe: 'is-valid', mensagem: 'NORMAL' };
            }

            // Verificar se está em atenção (10% de tolerância)
            const tolerancia = (config.max - config.min) * 0.1;
            if (valor >= (config.min - tolerancia) && valor <= (config.max + tolerancia)) {
                return { status: 'atencao', classe: 'is-warning', mensagem: 'ATENÇÃO - Fora da faixa ideal' };
            }

            // Fora de qualquer faixa aceitável
            return { status: 'anormal', classe: 'is-invalid', mensagem: 'ANORMAL - Verificar imediatamente' };
        }

        // Função para criar feedback visual
        function criarFeedback(input, validacao, config) {
            // Remove feedbacks anteriores
            const feedbackExistente = input.parentNode.querySelector('.parameter-feedback');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }

            if (!validacao) return;

            // Aplica classe CSS
            input.classList.remove('is-valid', 'is-invalid', 'is-warning');
            input.classList.add(validacao.classe);

            // Cria elemento de feedback
            const feedback = document.createElement('div');
            feedback.className = 'parameter-feedback mt-1';
            
            let corClasse = 'text-success';
            let icone = 'bi-check-circle-fill';
            
            if (validacao.status === 'critico' || validacao.status === 'anormal') {
                corClasse = 'text-danger';
                icone = 'bi-exclamation-triangle-fill';
            } else if (validacao.status === 'atencao') {
                corClasse = 'text-warning';
                icone = 'bi-exclamation-triangle';
            }

            feedback.innerHTML = `
                <small class="${corClasse}">
                    <i class="bi ${icone} me-1"></i>
                    ${validacao.mensagem}
                    <br>
                    <span class="text-muted">Faixa normal: ${config.min} - ${config.max}${config.unidade}</span>
                </small>
            `;

            input.parentNode.appendChild(feedback);
        }

        // Adicionar validação em tempo real para campos numéricos
        Object.keys(parametrosNormais).forEach(campo => {
            const input = document.getElementById(campo);
            if (input) {
                input.addEventListener('input', function() {
                    const validacao = validarCampo(campo, this.value);
                    const config = parametrosNormais[campo];
                    criarFeedback(this, validacao, config);
                });

                // Adicionar placeholder com faixa normal
                const config = parametrosNormais[campo];
                input.placeholder = `Faixa normal: ${config.min} - ${config.max}${config.unidade}`;
            }
        });

        // Validação de campos numéricos (evitar valores negativos)
        const numericFields = document.querySelectorAll('input[type="number"]');
        numericFields.forEach(field => {
            field.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });

        // Função para preencher valores ideais (botão de exemplo)
        window.preencherValoresIdeais = function() {
            if (confirm('Deseja preencher os campos com valores ideais de exemplo?')) {
                Object.keys(parametrosNormais).forEach(campo => {
                    const input = document.getElementById(campo);
                    if (input) {
                        input.value = parametrosNormais[campo].ideal;
                        input.dispatchEvent(new Event('input'));
                    }
                });
            }
        };

        // Funcionalidade dos Status Cards
        const statusCards = document.querySelectorAll('.status-card');
        statusCards.forEach(card => {
            const options = card.querySelectorAll('.status-option');
            const radioInputs = card.querySelectorAll('input[type="radio"]');
            
            // Configurar estado inicial
            radioInputs.forEach(radio => {
                if (radio.checked) {
                    updateCardSelection(card, radio.closest('.status-option'));
                }
            });

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        
                        // Remove seleção de outras opções
                        options.forEach(opt => opt.classList.remove('selected'));
                        
                        // Adiciona seleção na opção clicada
                        this.classList.add('selected');
                        
                        // Atualiza o card
                        updateCardSelection(card, option);
                        
                        // Marca que o formulário foi alterado
                        formChanged = true;
                    }
                });
            });
        });

        function updateCardSelection(card, selectedOption) {
            if (selectedOption) {
                card.classList.add('selected');
                
                // Efeito visual especial
                card.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 200);
            } else {
                card.classList.remove('selected');
            }
        }

        // Controle de alterações no formulário
        let formChanged = false;
        let isSubmitting = false;
        const formInputs = document.querySelectorAll('input, select, textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (!isSubmitting) {
                    formChanged = true;
                }
            });
        });
        
        // Remove o aviso quando o formulário é submetido
        document.querySelector('form').addEventListener('submit', function() {
            isSubmitting = true;
            formChanged = false;
        });

        // Confirmação inteligente apenas para links externos ou quando há mudanças significativas
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Verificar se é um link interno do sistema
                const currentDomain = window.location.origin;
                const linkDomain = new URL(link.href, window.location.origin).origin;
                
                // Se é link interno e há mudanças, confirmar apenas se há dados importantes preenchidos
                if (linkDomain === currentDomain && formChanged) {
                    const hasImportantData = document.querySelector('input[name="colaborador"]').value.trim() ||
                                           document.querySelector('textarea[name="observacao"]').value.trim() ||
                                           document.querySelector('input[name="nivel_oleo"]:checked') ||
                                           document.querySelector('input[name="nivel_agua"]:checked');
                    
                    if (hasImportantData && !confirm('Você tem alterações não salvas. Deseja realmente sair?')) {
                        e.preventDefault();
                    }
                }
            });
        });

        // Animação de entrada suave para os cards
        setTimeout(() => {
            statusCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
            });
        }, 100);
    });
</script>
@endpush 