@extends('layouts.app')

@section('title', 'Editar Relatório #' . $relatorio->id . ' - Sistema de Relatórios')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.3s ease;
    }

    .form-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .form-header {
        background: linear-gradient(135deg, #fd7e14 0%, #dc3545 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .form-section h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .form-floating > label {
        font-weight: 500;
        color: #6c757d;
    }

    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 1.5px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    .progress-container {
        position: relative;
        margin: 1rem 0;
    }

    .progress-value {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--primary-color);
    }

    .btn-action {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.15);
    }

    .icon-section {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 1rem auto;
    }

    .edit-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .changes-indicator {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-size: 0.875rem;
        display: none;
    }

    @media (max-width: 768px) {
        .form-header {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn-action {
            padding: 0.625rem 1rem;
            font-size: 0.8rem;
        }
        
        .icon-section {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Header -->
    <div class="mb-3 mb-md-4">

        
        <div class="d-flex align-items-center">
            <a href="{{ route('relatorios.show', $relatorio) }}" class="btn btn-outline-secondary me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-1 fw-bold fs-4 fs-md-2">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                    Editar Relatório #{{ $relatorio->id }}
                </h2>
                <p class="text-muted mb-0 d-none d-md-block">Atualize as informações do relatório</p>
            </div>
        </div>
    </div>

    <!-- Informações do Relatório Atual -->
    <div class="edit-info">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle text-warning me-2"></i>
            <div>
                <strong>Relatório:</strong> {{ $relatorio->titulo }} |
                <strong>Status:</strong> <span class="badge {{ $relatorio->status_badge }}">{{ $relatorio->status_label }}</span> |
                <strong>Progresso:</strong> {{ $relatorio->progresso }}%
            </div>
        </div>
    </div>

    <!-- Indicador de Mudanças -->
    <div id="changesIndicator" class="changes-indicator">
        <i class="bi bi-exclamation-circle me-2"></i>
        Existem mudanças não salvas neste formulário.
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <form action="{{ route('relatorios.update', $relatorio) }}" method="POST" enctype="multipart/form-data" id="relatorioForm">
                @csrf
                @method('PUT')
                
                <div class="card form-card">
                    <div class="form-header text-center">
                        <div class="icon-section">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Editar Relatório</h4>
                        <p class="mb-0 opacity-75">Atualize as informações necessárias</p>
                    </div>

                    <div class="card-body p-0">
                        <!-- Informações Básicas -->
                        <div class="form-section">
                            <h6>
                                <i class="bi bi-info-circle me-2"></i>
                                Informações Básicas
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                               id="titulo" name="titulo" value="{{ old('titulo', $relatorio->titulo) }}" 
                                               placeholder="Título do relatório" required maxlength="200" data-original="{{ $relatorio->titulo }}">
                                        <label for="titulo">
                                            <i class="bi bi-card-text me-2"></i>Título do Relatório *
                                        </label>
                                        @error('titulo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="descricao" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-2"></i>Descrição Detalhada *
                                    </label>
                                    <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                              id="descricao" name="descricao" rows="6" 
                                              placeholder="Descreva detalhadamente a ocorrência, problemas encontrados, ações tomadas, etc..." 
                                              required data-original="{{ $relatorio->descricao }}">{{ old('descricao', $relatorio->descricao) }}</textarea>
                                    @error('descricao')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Detalhe qualquer mudança ou progresso realizado
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" class="form-control @error('data_ocorrencia') is-invalid @enderror" 
                                               id="data_ocorrencia" name="data_ocorrencia" 
                                               value="{{ old('data_ocorrencia', $relatorio->data_ocorrencia->setTimezone('America/Sao_Paulo')->format('Y-m-d\TH:i')) }}" 
                                               required data-original="{{ $relatorio->data_ocorrencia->setTimezone('America/Sao_Paulo')->format('Y-m-d\TH:i') }}">
                                        <label for="data_ocorrencia">
                                            <i class="bi bi-calendar-event me-2"></i>Data e Hora da Ocorrência *
                                        </label>
                                        @error('data_ocorrencia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('prioridade') is-invalid @enderror" 
                                                id="prioridade" name="prioridade" required data-original="{{ $relatorio->prioridade }}">
                                            <option value="">Selecione a prioridade</option>
                                            @foreach($prioridadeOptions as $key => $label)
                                                <option value="{{ $key }}" {{ old('prioridade', $relatorio->prioridade) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="prioridade">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Prioridade *
                                        </label>
                                        @error('prioridade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status e Progresso -->
                        <div class="form-section">
                            <h6>
                                <i class="bi bi-flag me-2"></i>
                                Status e Progresso
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required data-original="{{ $relatorio->status }}">
                                            @foreach($statusOptions as $key => $label)
                                                <option value="{{ $key }}" {{ old('status', $relatorio->status) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="status">
                                            <i class="bi bi-flag me-2"></i>Status *
                                        </label>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="progresso" class="form-label fw-semibold">
                                        <i class="bi bi-bar-chart me-2"></i>Progresso * 
                                        <span id="progressoValue" class="text-primary">({{ old('progresso', $relatorio->progresso) }}%)</span>
                                    </label>
                                    <div class="progress-container">
                                        <div class="progress" style="height: 25px; border-radius: 15px;">
                                            <div id="progressBar" class="progress-bar" style="width: {{ old('progresso', $relatorio->progresso) }}%"></div>
                                        </div>
                                        <div class="progress-value" id="progressText">{{ old('progresso', $relatorio->progresso) }}%</div>
                                    </div>
                                    <input type="range" class="form-range @error('progresso') is-invalid @enderror" 
                                           id="progresso" name="progresso" min="0" max="100" step="5" 
                                           value="{{ old('progresso', $relatorio->progresso) }}" required data-original="{{ $relatorio->progresso }}">
                                    @error('progresso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Localização (Opcional) -->
                        <div class="form-section">
                            <h6>
                                <i class="bi bi-geo-alt me-2"></i>
                                Localização e Equipamento
                                <small class="text-muted fw-normal">(Opcional)</small>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('local_id') is-invalid @enderror" 
                                                id="local_id" name="local_id" data-original="{{ $relatorio->local_id }}"
                                                onchange="updateEquipamentosByLocal()">
                                            <option value="">Selecione um local</option>
                                            @foreach($locais as $local)
                                                <option value="{{ $local->id }}" 
                                                        {{ old('local_id', $relatorio->local_id) == $local->id ? 'selected' : '' }}>
                                                    {{ $local->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="local_id">
                                            <i class="bi bi-geo-alt me-2"></i>Local
                                        </label>
                                        @error('local_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('equipamento_id') is-invalid @enderror" 
                                                id="equipamento_id" name="equipamento_id" data-original="{{ $relatorio->equipamento_id }}">
                                            <option value="">Primeiro selecione um local</option>
                                        </select>
                                        <label for="equipamento_id">
                                            <i class="bi bi-cpu me-2"></i>Equipamento
                                        </label>
                                        @error('equipamento_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            <span id="equipamento-help-text">Primeiro selecione um local para ver os equipamentos disponíveis</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template oculto com todos os equipamentos -->
                                <div id="equipamentos-template" style="display: none;">
                                    @foreach($equipamentos as $equipamento)
                                        <option value="{{ $equipamento->id }}" 
                                                data-local-id="{{ $equipamento->local_id }}"
                                                {{ old('equipamento_id', $relatorio->equipamento_id) == $equipamento->id ? 'data-selected="true"' : '' }}>
                                            {{ $equipamento->nome }}
                                            @if($equipamento->codigo)
                                                - {{ $equipamento->codigo }}
                                            @endif
                                            @if($equipamento->local)
                                                ({{ $equipamento->local->nome }})
                                            @endif
                                        </option>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Imagens Existentes e Upload -->
                        <div class="form-section">
                            <h6>
                                <i class="bi bi-images me-2"></i>
                                Imagens do Relatório
                                <small class="text-muted fw-normal">(Máximo 7MB por imagem)</small>
                            </h6>
                            
                            <!-- Imagens Existentes -->
                            @if($relatorio->imagens->count() > 0)
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <h6 class="small fw-bold text-muted mb-2">Imagens Atuais:</h6>
                                        <div class="row g-2" id="existingImages">
                                            @foreach($relatorio->imagens as $imagem)
                                                <div class="col-6 col-md-4 col-lg-3" id="existing-image-{{ $imagem->id }}">
                                                    <div class="position-relative">
                                                        <img src="{{ $imagem->url }}" 
                                                             class="img-fluid rounded" 
                                                             style="height: 120px; object-fit: cover; width: 100%;"
                                                             onclick="openImageModal('{{ $imagem->url }}', '{{ $imagem->nome_original }}')">
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                                                onclick="markImageForRemoval({{ $imagem->id }})"
                                                                style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                        <div class="text-center mt-1">
                                                            <small class="text-muted">{{ $imagem->nome_original }}</small><br>
                                                            <small class="text-muted">{{ $imagem->tamanho_formatado }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Upload de Novas Imagens -->
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="imagens" class="form-label fw-semibold">
                                        <i class="bi bi-cloud-upload me-2"></i>Adicionar Novas Imagens
                                    </label>
                                    <input type="file" class="form-control @error('imagens.*') is-invalid @enderror" 
                                           id="imagens" name="imagens[]" multiple accept="image/*">
                                    @error('imagens.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Formatos aceitos: JPG, PNG, GIF, WEBP. Máximo 7MB por imagem.
                                    </div>
                                </div>

                                <!-- Preview das novas imagens -->
                                <div class="col-12">
                                    <div id="newImagePreview" class="row g-2" style="display: none;">
                                        <div class="col-12 mb-2">
                                            <h6 class="small fw-bold text-muted">Novas Imagens:</h6>
                                        </div>
                                        <!-- Previews serão inseridos aqui via JavaScript -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden inputs para imagens a serem removidas -->
                            <div id="imagesToRemove"></div>
                        </div>

                        <!-- Informações de Edição -->
                        <div class="form-section">
                            <h6>
                                <i class="bi bi-clock-history me-2"></i>
                                Histórico de Edições
                            </h6>
                            
                            <div class="small text-muted">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2">
                                        <strong>Criado em:</strong> {{ $relatorio->data_criacao->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <strong>Última atualização:</strong> {{ $relatorio->data_atualizacao->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <strong>Criado por:</strong> {{ $relatorio->usuario->name }}
                                    </div>
                                    <div class="col-12 col-md-6 mb-2">
                                        <strong>Editável:</strong> 
                                        <span class="badge {{ $relatorio->editavel ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $relatorio->editavel ? 'Sim' : 'Não' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="text-center p-4">
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <a href="{{ route('relatorios.show', $relatorio) }}" class="btn btn-outline-secondary btn-action">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="button" class="btn btn-outline-info btn-action" onclick="resetForm()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Resetar
                                </button>
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Controle de progresso
        const progressoSlider = document.getElementById('progresso');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const progressValue = document.getElementById('progressoValue');
        const statusSelect = document.getElementById('status');

        progressoSlider.addEventListener('input', function() {
            const value = this.value;
            progressBar.style.width = value + '%';
            progressText.textContent = value + '%';
            progressValue.textContent = '(' + value + '%)';

            // Sugerir status baseado no progresso (sem forçar)
            if (value == 0 && statusSelect.value === 'em_andamento') {
                if (confirm('Progresso é 0%. Deseja alterar o status para "Pendente"?')) {
                    statusSelect.value = 'pendente';
                }
            } else if (value == 100 && statusSelect.value !== 'resolvido') {
                if (confirm('Progresso é 100%. Deseja alterar o status para "Resolvido"?')) {
                    statusSelect.value = 'resolvido';
                }
            } else if (value > 0 && value < 100 && statusSelect.value !== 'em_andamento') {
                if (confirm('Progresso está entre 1-99%. Deseja alterar o status para "Em Andamento"?')) {
                    statusSelect.value = 'em_andamento';
                }
            }
            
            checkForChanges();
        });

        // Verificar mudanças no formulário
        const formInputs = document.querySelectorAll('input, textarea, select');
        const changesIndicator = document.getElementById('changesIndicator');
        
        function checkForChanges() {
            let hasChanges = false;
            
            formInputs.forEach(input => {
                if (input.hasAttribute('data-original')) {
                    const original = input.getAttribute('data-original');
                    const current = input.value;
                    
                    if (original !== current) {
                        hasChanges = true;
                    }
                }
            });
            
            if (hasChanges) {
                changesIndicator.style.display = 'block';
            } else {
                changesIndicator.style.display = 'none';
            }
        }

        formInputs.forEach(input => {
            input.addEventListener('input', checkForChanges);
            input.addEventListener('change', checkForChanges);
        });

        // Verificação inicial
        checkForChanges();

        // Aviso antes de sair com mudanças não salvas
        window.addEventListener('beforeunload', function(e) {
            let hasChanges = false;
            formInputs.forEach(input => {
                if (input.hasAttribute('data-original')) {
                    const original = input.getAttribute('data-original');
                    const current = input.value;
                    if (original !== current) {
                        hasChanges = true;
                    }
                }
            });

            if (hasChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Validação do formulário
        const form = document.getElementById('relatorioForm');
        form.addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const descricao = document.getElementById('descricao').value.trim();

            if (!titulo || !descricao) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
                return false;
            }

            // Remover listener de beforeunload para permitir submit
            window.removeEventListener('beforeunload', function() {});

            // Mostrar loading
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Salvando...';
            submitBtn.disabled = true;
        });

        // Auto-resize para textarea
        const textarea = document.getElementById('descricao');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Trigger inicial para ajustar altura
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';

        // Gerenciamento de imagens
        const imagensInput = document.getElementById('imagens');
        const newImagePreview = document.getElementById('newImagePreview');
        const imagesToRemove = document.getElementById('imagesToRemove');
        let selectedFiles = [];
        let imagensParaRemover = [];

        // Upload e preview de novas imagens
        imagensInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Validar tamanho e tipo de arquivo
            const validFiles = files.filter(file => {
                if (file.size > 7 * 1024 * 1024) { // 7MB
                    alert(`A imagem "${file.name}" excede o limite de 7MB.`);
                    return false;
                }
                
                if (!file.type.startsWith('image/')) {
                    alert(`O arquivo "${file.name}" não é uma imagem válida.`);
                    return false;
                }
                
                return true;
            });

            // Adicionar arquivos válidos à lista
            selectedFiles = [...selectedFiles, ...validFiles];
            
            // Atualizar preview
            updateNewImagePreview();
        });

        function updateNewImagePreview() {
            // Limpar preview mantendo o título
            newImagePreview.innerHTML = '<div class="col-12 mb-2"><h6 class="small fw-bold text-muted">Novas Imagens:</h6></div>';
            
            if (selectedFiles.length === 0) {
                newImagePreview.style.display = 'none';
                return;
            }
            
            newImagePreview.style.display = 'block';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'col-6 col-md-4 col-lg-3';
                    
                    previewDiv.innerHTML = `
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded" style="height: 120px; object-fit: cover; width: 100%;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                    onclick="removeNewImage(${index})" style="border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="text-center mt-1">
                                <small class="text-muted">${file.name}</small><br>
                                <small class="text-muted">${formatFileSize(file.size)}</small>
                            </div>
                        </div>
                    `;
                    
                    newImagePreview.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
        }

        // Atualizar o input file com os arquivos selecionados
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            imagensInput.files = dataTransfer.files;
        }

        // Função global para remover nova imagem
        window.removeNewImage = function(index) {
            selectedFiles.splice(index, 1);
            updateNewImagePreview();
            updateFileInput();
        };

        // Função global para marcar imagem existente para remoção
        window.markImageForRemoval = function(imagemId) {
            if (confirm('Tem certeza que deseja remover esta imagem?')) {
                const imageElement = document.getElementById(`existing-image-${imagemId}`);
                imageElement.style.opacity = '0.5';
                imageElement.style.filter = 'grayscale(100%)';
                
                // Adicionar à lista de remoção
                if (!imagensParaRemover.includes(imagemId)) {
                    imagensParaRemover.push(imagemId);
                    
                    // Criar input hidden
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'remover_imagens[]';
                    hiddenInput.value = imagemId;
                    hiddenInput.id = `remove-input-${imagemId}`;
                    imagesToRemove.appendChild(hiddenInput);
                }
                
                // Mudar botão para "desfazer"
                const removeBtn = imageElement.querySelector('button');
                removeBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                removeBtn.onclick = function() { undoImageRemoval(imagemId); };
                removeBtn.className = 'btn btn-success btn-sm position-absolute top-0 end-0 m-1';
            }
        };

        // Função global para desfazer remoção de imagem
        window.undoImageRemoval = function(imagemId) {
            const imageElement = document.getElementById(`existing-image-${imagemId}`);
            imageElement.style.opacity = '1';
            imageElement.style.filter = 'none';
            
            // Remover da lista de remoção
            const index = imagensParaRemover.indexOf(imagemId);
            if (index > -1) {
                imagensParaRemover.splice(index, 1);
                
                // Remover input hidden
                const hiddenInput = document.getElementById(`remove-input-${imagemId}`);
                if (hiddenInput) {
                    hiddenInput.remove();
                }
            }
            
            // Restaurar botão original
            const removeBtn = imageElement.querySelector('button');
            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
            removeBtn.onclick = function() { markImageForRemoval(imagemId); };
            removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-1';
        };

        // Função global para abrir modal de imagem (visualização)
        window.openImageModal = function(imageUrl, imageName) {
            // Criar modal dinamicamente
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${imageName}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${imageUrl}" class="img-fluid rounded" style="max-height: 70vh;">
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
            
            // Remover modal quando fechado
            modal.addEventListener('hidden.bs.modal', function() {
                modal.remove();
            });
        };

        // Função para formatar tamanho do arquivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Inicializar filtro de equipamentos
        updateEquipamentosByLocal();
    });

    // Função para resetar formulário
    function resetForm() {
        if (confirm('Tem certeza que deseja resetar todas as alterações? Isso irá restaurar os valores originais.')) {
            const formInputs = document.querySelectorAll('input, textarea, select');
            
            formInputs.forEach(input => {
                if (input.hasAttribute('data-original')) {
                    input.value = input.getAttribute('data-original');
                    
                    // Trigger events para atualizar UI
                    if (input.id === 'progresso') {
                        input.dispatchEvent(new Event('input'));
                    }
                }
            });

            // Atualizar filtro de equipamentos após reset
            updateEquipamentosByLocal();

            // Ocultar indicador de mudanças
            document.getElementById('changesIndicator').style.display = 'none';
        }
    }

    // Função para filtrar equipamentos por local
    function updateEquipamentosByLocal() {
        const localSelect = document.getElementById('local_id');
        const equipamentoSelect = document.getElementById('equipamento_id');
        const helpText = document.getElementById('equipamento-help-text');
        const equipamentosTemplate = document.getElementById('equipamentos-template');
        const selectedLocalId = localSelect.value;
        const currentEquipamentoId = equipamentoSelect.value;

        // Limpar opções de equipamento (manter primeira opção)
        const firstOption = equipamentoSelect.querySelector('option[value=""]');
        equipamentoSelect.innerHTML = '';
        equipamentoSelect.appendChild(firstOption);

        if (!selectedLocalId) {
            // Nenhum local selecionado
            firstOption.textContent = 'Primeiro selecione um local';
            equipamentoSelect.disabled = true;
            helpText.textContent = 'Primeiro selecione um local para ver os equipamentos disponíveis';
            return;
        }

        // Filtrar equipamentos do local selecionado do template
        const allEquipamentos = Array.from(equipamentosTemplate.querySelectorAll('option'));
        const equipamentosDoLocal = allEquipamentos.filter(option => 
            option.getAttribute('data-local-id') == selectedLocalId
        );
        
        if (equipamentosDoLocal.length === 0) {
            firstOption.textContent = 'Nenhum equipamento disponível neste local';
            equipamentoSelect.disabled = true;
            helpText.textContent = 'Não há equipamentos cadastrados neste local';
        } else {
            firstOption.textContent = 'Selecione um equipamento';
            equipamentoSelect.disabled = false;
            helpText.textContent = 'Equipamentos disponíveis no local selecionado';

            // Adicionar equipamentos filtrados
            equipamentosDoLocal.forEach(option => {
                const newOption = option.cloneNode(true);
                
                // Verificar se deve estar selecionado
                if (newOption.hasAttribute('data-selected') || newOption.value == currentEquipamentoId) {
                    newOption.selected = true;
                }
                
                equipamentoSelect.appendChild(newOption);
            });
        }
    }
</script>
@endpush 