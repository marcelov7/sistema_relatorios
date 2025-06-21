@extends('layouts.app')

@section('title', 'Novo Relatório - Sistema de Relatórios')

@push('styles')
<style>
    .form-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .form-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .form-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
        50% { transform: translate(-50%, -50%) rotate(180deg); }
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
    }

    .form-section:hover {
        background: #f1f3f4;
        transform: translateY(-1px);
    }

    .form-section.active {
        border-color: var(--primary-color);
        background: rgba(13, 110, 253, 0.05);
    }

    .form-section h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .form-floating > label {
        font-weight: 500;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .form-control, .form-select {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        position: relative;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        transform: translateY(-1px);
    }

    .form-control.is-valid {
        border-color: #28a745;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.94-.94L4.3 4.73l1.94-1.94L7.18 3.82 4.3 6.7z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.4m0-2.4L5.8 7'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
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
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        z-index: 2;
    }

    .progress-bar {
        transition: width 0.5s ease;
        background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    }

    .btn-action {
        border-radius: 0.75rem;
        padding: 0.875rem 2rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .btn-action:active {
        transform: translateY(0);
    }

    .icon-section {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        margin: 0 auto 1rem auto;
        animation: pulse 2s ease-in-out infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        100% { transform: scale(1.05); }
    }

    .image-preview-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        border: 2px dashed #e9ecef;
        transition: all 0.3s ease;
        text-align: center;
    }

    .image-preview-card:hover {
        border-color: var(--primary-color);
        background: rgba(13, 110, 253, 0.02);
    }

    .image-preview-card.has-images {
        border-style: solid;
        border-color: var(--primary-color);
        background: rgba(13, 110, 253, 0.05);
    }

    .image-thumbnail {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .image-thumbnail:hover {
        transform: scale(1.02);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15);
    }

    .image-remove-btn {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.9);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        transition: all 0.2s ease;
        opacity: 0;
    }

    .image-thumbnail:hover .image-remove-btn {
        opacity: 1;
    }

    .image-remove-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .save-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        z-index: 1000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
    }

    .save-indicator.show {
        opacity: 1;
        transform: translateY(0);
    }

    .character-counter {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
        margin-top: 0.25rem;
    }

    .character-counter.warning {
        color: #fd7e14;
    }

    .character-counter.danger {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .form-header {
            padding: 1.5rem;
        }
        
        .form-section {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            font-size: 0.8rem;
        }
        
        .icon-section {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container" x-data="relatorioForm()">
    <!-- Indicador de Salvamento -->
    <div class="save-indicator" :class="{ 'show': saveIndicator }" x-text="saveMessage"></div>

    <!-- Header -->
    <div class="mb-3 mb-md-4">

        
        <div class="d-flex align-items-center">
            <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-1 fw-bold fs-4 fs-md-2">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    Novo Relatório
                </h2>
                <p class="text-muted mb-0 d-none d-md-block">Criar um novo relatório de ocorrência</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <form action="{{ route('relatorios.store') }}" method="POST" enctype="multipart/form-data" 
                  x-ref="form" @submit="handleSubmit">
                @csrf
                
                <div class="card form-card">
                    <div class="form-header text-center">
                        <div class="icon-section">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Dados do Relatório</h4>
                        <p class="mb-0 opacity-75">Preencha todas as informações necessárias</p>
                        <div class="mt-2">
                            <small class="opacity-75">
                                <i class="bi bi-clock me-1"></i>
                                <span x-text="'Último salvamento: ' + lastSaved"></span>
                            </small>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Informações Básicas -->
                        <div class="form-section" :class="{ 'active': activeSection === 'basic' }" 
                             @click="activeSection = 'basic'">
                            <h6>
                                <i class="bi bi-info-circle me-2"></i>
                                Informações Básicas
                                <span x-show="formData.titulo && formData.descricao" class="ms-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                </span>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" 
                                               class="form-control" 
                                               :class="getTitleValidationClass()"
                                               id="titulo" 
                                               name="titulo" 
                                               x-model="formData.titulo"
                                               @input="validateTitle(); saveDraft();"
                                               placeholder="Título do relatório" 
                                               required 
                                               maxlength="200">
                                        <label for="titulo">
                                            <i class="bi bi-card-text me-2"></i>Título do Relatório *
                                        </label>
                                        <div class="character-counter" 
                                             :class="{ 'warning': formData.titulo.length > 150, 'danger': formData.titulo.length > 180 }">
                                            <span x-text="formData.titulo.length"></span>/200
                                        </div>
                                        <div x-show="errors.titulo" class="invalid-feedback d-block" x-text="errors.titulo"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="descricao" class="form-label fw-semibold">
                                        <i class="bi bi-card-text me-2"></i>Descrição Detalhada *
                                    </label>
                                    <textarea class="form-control" 
                                              :class="getDescriptionValidationClass()"
                                              id="descricao" 
                                              name="descricao" 
                                              rows="6" 
                                              x-model="formData.descricao"
                                              @input="validateDescription(); saveDraft(); autoResize($event)"
                                              placeholder="Descreva detalhadamente a ocorrência, problemas encontrados, ações tomadas, etc..." 
                                              required></textarea>
                                    <div class="character-counter" 
                                         :class="{ 'warning': formData.descricao.length > 800, 'danger': formData.descricao.length > 950 }">
                                        <span x-text="formData.descricao.length"></span>/1000
                                    </div>
                                    <div x-show="errors.descricao" class="invalid-feedback d-block" x-text="errors.descricao"></div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="datetime-local" 
                                               class="form-control" 
                                               :class="getDateValidationClass()"
                                               id="data_ocorrencia" 
                                               name="data_ocorrencia" 
                                               x-model="formData.data_ocorrencia"
                                               @change="validateDate(); saveDraft();"
                                               required>
                                        <label for="data_ocorrencia">
                                            <i class="bi bi-calendar-event me-2"></i>Data da Ocorrência *
                                        </label>
                                        <div x-show="errors.data_ocorrencia" class="invalid-feedback d-block" x-text="errors.data_ocorrencia"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="prioridade" 
                                                name="prioridade" 
                                                x-model="formData.prioridade"
                                                @change="saveDraft()"
                                                required>
                                            @foreach($prioridadeOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <label for="prioridade">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Prioridade *
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status e Progresso -->
                        <div class="form-section" :class="{ 'active': activeSection === 'status' }" 
                             @click="activeSection = 'status'">
                            <h6>
                                <i class="bi bi-flag me-2"></i>
                                Status e Progresso
                                <span class="badge ms-2" :class="getStatusBadgeClass()" x-text="getStatusLabel()"></span>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="status" 
                                                name="status" 
                                                x-model="formData.status"
                                                @change="updateProgressBasedOnStatus(); saveDraft();"
                                                required>
                                            @foreach($statusOptions as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <label for="status">
                                            <i class="bi bi-flag me-2"></i>Status *
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="progresso" class="form-label fw-semibold">
                                        <i class="bi bi-bar-chart me-2"></i>Progresso * 
                                        <span class="text-primary">(<span x-text="formData.progresso"></span>%)</span>
                                    </label>
                                    <div class="progress-container">
                                        <div class="progress" style="height: 30px; border-radius: 15px;">
                                            <div class="progress-bar" 
                                                 :style="'width: ' + formData.progresso + '%'"
                                                 :class="getProgressBarClass()"></div>
                                        </div>
                                        <div class="progress-value">
                                            <span x-text="formData.progresso"></span>%
                                        </div>
                                    </div>
                                    <input type="range" 
                                           class="form-range" 
                                           id="progresso" 
                                           name="progresso" 
                                           min="0" 
                                           max="100" 
                                           step="5" 
                                           x-model="formData.progresso"
                                           @input="updateStatusBasedOnProgress(); saveDraft();"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Localização (Opcional) -->
                        <div class="form-section" :class="{ 'active': activeSection === 'location' }" 
                             @click="activeSection = 'location'">
                            <h6>
                                <i class="bi bi-geo-alt me-2"></i>
                                Localização e Equipamento
                                <small class="text-muted fw-normal">(Opcional)</small>
                                <span x-show="formData.local_id || formData.equipamento_id" class="ms-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                </span>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="local_id" 
                                                name="local_id" 
                                                x-model="formData.local_id"
                                                @change="updateEquipamentosByLocal(); saveDraft()">
                                            <option value="">Selecione um local</option>
                                            @foreach($locais as $local)
                                                <option value="{{ $local->id }}">{{ $local->nome }}</option>
                                            @endforeach
                                        </select>
                                        <label for="local_id">
                                            <i class="bi bi-geo-alt me-2"></i>Local
                                        </label>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Selecione o local onde ocorreu o problema (se aplicável)
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="equipamento_id" 
                                                name="equipamento_id" 
                                                x-model="formData.equipamento_id"
                                                @change="saveDraft()"
                                                :disabled="!formData.local_id">
                                            <option value="">
                                                <span x-show="!formData.local_id">Primeiro selecione um local</span>
                                                <span x-show="formData.local_id && filteredEquipamentos.length === 0">Nenhum equipamento disponível</span>
                                                <span x-show="formData.local_id && filteredEquipamentos.length > 0">Selecione um equipamento</span>
                                            </option>
                                            <template x-for="equipamento in filteredEquipamentos" :key="equipamento.id">
                                                <option :value="equipamento.id" x-text="equipamento.display_name"></option>
                                            </template>
                                        </select>
                                        <label for="equipamento_id">
                                            <i class="bi bi-cpu me-2"></i>Equipamento
                                        </label>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            <span x-show="!formData.local_id">Primeiro selecione um local para ver os equipamentos disponíveis</span>
                                            <span x-show="formData.local_id">Equipamentos disponíveis no local selecionado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload de Imagens -->
                        <div class="form-section" :class="{ 'active': activeSection === 'images' }" 
                             @click="activeSection = 'images'">
                            <h6>
                                <i class="bi bi-images me-2"></i>
                                Imagens do Relatório
                                <small class="text-muted fw-normal">(Opcional - Máximo 7MB por imagem)</small>
                                <span x-show="selectedImages.length > 0" class="ms-2">
                                    <span class="badge bg-primary" x-text="selectedImages.length + ' imagem(s)'"></span>
                                </span>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="image-preview-card" :class="{ 'has-images': selectedImages.length > 0 }">
                                        <input type="file" 
                                               class="form-control" 
                                               id="imagens" 
                                               name="imagens[]" 
                                               multiple 
                                               accept="image/*"
                                               @change="handleImageUpload($event)"
                                               style="display: none;">
                                        
                                        <div x-show="selectedImages.length === 0" class="text-center py-4">
                                            <i class="bi bi-cloud-upload fs-2 text-muted mb-3"></i>
                                            <p class="mb-3">Clique para selecionar imagens ou arraste aqui</p>
                                            <button type="button" 
                                                    class="btn btn-outline-primary"
                                                    @click="$refs.imageInput.click()">
                                                <i class="bi bi-plus-circle me-2"></i>Selecionar Imagens
                                            </button>
                                        </div>

                                        <div x-show="selectedImages.length > 0">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">
                                                    <span x-text="selectedImages.length"></span> 
                                                    <span x-text="selectedImages.length === 1 ? 'imagem selecionada' : 'imagens selecionadas'"></span>
                                                </h6>
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-sm"
                                                        @click="$refs.imageInput.click()">
                                                    <i class="bi bi-plus-circle me-1"></i>Adicionar Mais
                                                </button>
                                            </div>
                                            
                                            <div class="row g-2">
                                                <template x-for="(image, index) in selectedImages" :key="index">
                                                    <div class="col-6 col-md-4 col-lg-3">
                                                        <div class="image-thumbnail">
                                                            <img :src="image.preview" 
                                                                 class="img-fluid rounded" 
                                                                 style="height: 120px; object-fit: cover; width: 100%;">
                                                            <button type="button" 
                                                                    class="image-remove-btn"
                                                                    @click="removeImage(index)">
                                                                <i class="bi bi-x"></i>
                                                            </button>
                                                            <div class="text-center mt-1">
                                                                <small class="text-muted d-block" x-text="truncateFileName(image.file.name)"></small>
                                                                <small class="text-muted" x-text="formatFileSize(image.file.size)"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <input type="file" 
                                               x-ref="imageInput"
                                               multiple 
                                               accept="image/*"
                                               @change="handleImageUpload($event)"
                                               style="display: none;">
                                    </div>
                                    
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Formatos aceitos: JPG, PNG, GIF, WEBP. Máximo 7MB por imagem.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resumo -->
                        <div class="form-section bg-light">
                            <h6>
                                <i class="bi bi-list-check me-2"></i>
                                Resumo do Relatório
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="small">
                                        <strong>Título:</strong> 
                                        <span x-text="formData.titulo || 'Não informado'" 
                                              :class="formData.titulo ? 'text-success' : 'text-muted'"></span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small">
                                        <strong>Status:</strong> 
                                        <span :class="getStatusBadgeClass()" 
                                              class="badge badge-sm" 
                                              x-text="getStatusLabel()"></span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small">
                                        <strong>Prioridade:</strong> 
                                        <span :class="getPriorityBadgeClass()" 
                                              class="badge badge-sm" 
                                              x-text="getPriorityLabel()"></span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small">
                                        <strong>Progresso:</strong> 
                                        <span class="text-primary fw-bold" x-text="formData.progresso + '%'"></span>
                                    </div>
                                </div>
                                <div class="col-12" x-show="selectedImages.length > 0">
                                    <div class="small">
                                        <strong>Imagens:</strong> 
                                        <span class="text-info" x-text="selectedImages.length + ' arquivo(s) selecionado(s)'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="text-center p-4">
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary btn-action">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-info btn-action" 
                                        @click="saveDraft(true)">
                                    <i class="bi bi-save me-2"></i>Salvar Rascunho
                                </button>
                                <button type="submit" 
                                        class="btn btn-primary btn-action"
                                        :disabled="!isFormValid() || submitting"
                                        :class="{ 'disabled': !isFormValid() }">
                                    <template x-if="!submitting">
                                        <span><i class="bi bi-check-circle me-2"></i>Criar Relatório</span>
                                    </template>
                                    <template x-if="submitting">
                                        <span><i class="bi bi-hourglass-split me-2"></i>Criando...</span>
                                    </template>
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
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Dados dos equipamentos em JSON -->
<script id="equipamentos-data" type="application/json">
{!! json_encode($equipamentos->map(function($equipamento) {
    return [
        'id' => $equipamento->id,
        'nome' => $equipamento->nome,
        'codigo' => $equipamento->codigo,
        'local_id' => $equipamento->local_id,
        'local_nome' => $equipamento->local ? $equipamento->local->nome : null,
        'display_name' => $equipamento->nome . 
            ($equipamento->codigo ? ' - ' . $equipamento->codigo : '') .
            ($equipamento->local ? ' (' . $equipamento->local->nome . ')' : '')
    ];
})) !!}
</script>

<script>

    function relatorioForm() {
        return {
            // Função para obter data/hora local correta
            getCurrentLocalDateTime() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            },

            // Estado do formulário
            formData: {
                titulo: '',
                descricao: '',
                data_ocorrencia: '',
                status: 'pendente',
                prioridade: 'media',
                progresso: 0,
                local_id: '',
                equipamento_id: ''
            },
            
            // Dados dos equipamentos
            allEquipamentos: JSON.parse(document.getElementById('equipamentos-data').textContent),
            
            filteredEquipamentos: [],
            
            // Estado da interface
            activeSection: 'basic',
            selectedImages: [],
            errors: {},
            submitting: false,
            saveIndicator: false,
            saveMessage: '',
            lastSaved: 'Nunca',

            // Inicialização
            init() {
                // Definir data/hora atual local
                if (!this.formData.data_ocorrencia) {
                    this.formData.data_ocorrencia = this.getCurrentLocalDateTime();
                }
                
                this.loadDraft();
                this.updateLastSaved();
                this.updateEquipamentosByLocal(); // Inicializar filtro de equipamentos
                
                // Auto-save a cada 30 segundos
                setInterval(() => {
                    this.saveDraft();
                }, 30000);
            },

            // Filtrar equipamentos por local
            updateEquipamentosByLocal() {
                if (!this.formData.local_id) {
                    this.filteredEquipamentos = [];
                    this.formData.equipamento_id = ''; // Limpar seleção de equipamento
                    return;
                }

                this.filteredEquipamentos = this.allEquipamentos.filter(equipamento => 
                    equipamento.local_id == this.formData.local_id
                );

                // Se o equipamento selecionado não pertence ao local atual, limpar seleção
                if (this.formData.equipamento_id) {
                    const equipamentoSelecionado = this.filteredEquipamentos.find(eq => 
                        eq.id == this.formData.equipamento_id
                    );
                    if (!equipamentoSelecionado) {
                        this.formData.equipamento_id = '';
                    }
                }
            },

            // Validações
            validateTitle() {
                this.errors.titulo = '';
                if (!this.formData.titulo.trim()) {
                    this.errors.titulo = 'Título é obrigatório';
                } else if (this.formData.titulo.length < 5) {
                    this.errors.titulo = 'Título deve ter pelo menos 5 caracteres';
                } else if (this.formData.titulo.length > 200) {
                    this.errors.titulo = 'Título não pode exceder 200 caracteres';
                }
            },

            validateDescription() {
                this.errors.descricao = '';
                if (!this.formData.descricao.trim()) {
                    this.errors.descricao = 'Descrição é obrigatória';
                } else if (this.formData.descricao.length < 10) {
                    this.errors.descricao = 'Descrição deve ter pelo menos 10 caracteres';
                } else if (this.formData.descricao.length > 1000) {
                    this.errors.descricao = 'Descrição não pode exceder 1000 caracteres';
                }
            },

            validateDate() {
                this.errors.data_ocorrencia = '';
                const selectedDate = new Date(this.formData.data_ocorrencia);
                const now = new Date();
                const oneYearFromNow = new Date();
                oneYearFromNow.setFullYear(now.getFullYear() + 1);

                if (selectedDate > oneYearFromNow) {
                    this.errors.data_ocorrencia = 'Data não pode ser superior a 1 ano no futuro';
                }
            },

            // Classes CSS dinâmicas
            getTitleValidationClass() {
                if (!this.formData.titulo) return '';
                return this.errors.titulo ? 'is-invalid' : 'is-valid';
            },

            getDescriptionValidationClass() {
                if (!this.formData.descricao) return '';
                return this.errors.descricao ? 'is-invalid' : 'is-valid';
            },

            getDateValidationClass() {
                if (!this.formData.data_ocorrencia) return '';
                return this.errors.data_ocorrencia ? 'is-invalid' : 'is-valid';
            },

            getStatusBadgeClass() {
                const classes = {
                    'pendente': 'bg-warning',
                    'em_andamento': 'bg-info',
                    'resolvido': 'bg-success'
                };
                return classes[this.formData.status] || 'bg-secondary';
            },

            getPriorityBadgeClass() {
                const classes = {
                    'baixa': 'bg-success',
                    'media': 'bg-warning',
                    'alta': 'bg-danger',
                    'critica': 'bg-dark'
                };
                return classes[this.formData.prioridade] || 'bg-secondary';
            },

            getProgressBarClass() {
                if (this.formData.progresso === 0) return '';
                if (this.formData.progresso < 50) return 'bg-warning';
                if (this.formData.progresso < 100) return 'bg-info';
                return 'bg-success';
            },

            // Labels
            getStatusLabel() {
                const labels = {
                    'pendente': 'Pendente',
                    'em_andamento': 'Em Andamento',
                    'resolvido': 'Resolvido'
                };
                return labels[this.formData.status] || 'Indefinido';
            },

            getPriorityLabel() {
                const labels = {
                    'baixa': 'Baixa',
                    'media': 'Média',
                    'alta': 'Alta',
                    'critica': 'Crítica'
                };
                return labels[this.formData.prioridade] || 'Indefinida';
            },

            // Sincronização status/progresso
            updateStatusBasedOnProgress() {
                if (this.formData.progresso == 0 && this.formData.status === 'em_andamento') {
                    this.formData.status = 'pendente';
                } else if (this.formData.progresso == 100) {
                    this.formData.status = 'resolvido';
                } else if (this.formData.progresso > 0 && this.formData.progresso < 100 && this.formData.status !== 'em_andamento') {
                    this.formData.status = 'em_andamento';
                }
            },

            updateProgressBasedOnStatus() {
                if (this.formData.status === 'pendente' && this.formData.progresso > 0) {
                    this.formData.progresso = 0;
                } else if (this.formData.status === 'resolvido' && this.formData.progresso < 100) {
                    this.formData.progresso = 100;
                } else if (this.formData.status === 'em_andamento' && this.formData.progresso == 0) {
                    this.formData.progresso = 25;
                }
            },

            // Manipulação de imagens
            handleImageUpload(event) {
                const files = Array.from(event.target.files);
                
                files.forEach(file => {
                    // Validar tamanho
                    if (file.size > 7 * 1024 * 1024) {
                        alert(`A imagem "${file.name}" excede o limite de 7MB.`);
                        return;
                    }
                    
                    // Validar tipo
                    if (!file.type.startsWith('image/')) {
                        alert(`O arquivo "${file.name}" não é uma imagem válida.`);
                        return;
                    }
                    
                    // Criar preview
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImages.push({
                            file: file,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                });
                
                // Limpar input
                event.target.value = '';
            },

            removeImage(index) {
                this.selectedImages.splice(index, 1);
                this.saveDraft();
            },

            // Utilitários
            truncateFileName(name, maxLength = 15) {
                if (name.length <= maxLength) return name;
                const extension = name.split('.').pop();
                const nameWithoutExt = name.substring(0, name.lastIndexOf('.'));
                const truncated = nameWithoutExt.substring(0, maxLength - extension.length - 4) + '...';
                return truncated + '.' + extension;
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            autoResize(event) {
                event.target.style.height = 'auto';
                event.target.style.height = (event.target.scrollHeight) + 'px';
            },

            // Validação geral
            isFormValid() {
                this.validateTitle();
                this.validateDescription();
                this.validateDate();
                
                return !this.errors.titulo && 
                       !this.errors.descricao && 
                       !this.errors.data_ocorrencia &&
                       this.formData.titulo.trim() &&
                       this.formData.descricao.trim();
            },

            // Rascunho
            saveDraft(showIndicator = false) {
                const draftData = {
                    ...this.formData,
                    timestamp: new Date().toISOString()
                };
                
                localStorage.setItem('relatorio_draft', JSON.stringify(draftData));
                this.updateLastSaved();
                
                if (showIndicator) {
                    this.showSaveIndicator('Rascunho salvo!');
                }
            },

            loadDraft() {
                const draft = localStorage.getItem('relatorio_draft');
                if (draft) {
                    try {
                        const data = JSON.parse(draft);
                        this.formData = { ...this.formData, ...data };
                        delete this.formData.timestamp;
                    } catch (e) {
                        console.error('Erro ao carregar rascunho:', e);
                    }
                }
            },

            updateLastSaved() {
                const draft = localStorage.getItem('relatorio_draft');
                if (draft) {
                    try {
                        const data = JSON.parse(draft);
                        if (data.timestamp) {
                            const date = new Date(data.timestamp);
                            this.lastSaved = date.toLocaleTimeString();
                        }
                    } catch (e) {
                        this.lastSaved = 'Nunca';
                    }
                } else {
                    this.lastSaved = 'Nunca';
                }
            },

            showSaveIndicator(message) {
                this.saveMessage = message;
                this.saveIndicator = true;
                setTimeout(() => {
                    this.saveIndicator = false;
                }, 2000);
            },

            // Submit
            handleSubmit(event) {
                if (!this.isFormValid()) {
                    event.preventDefault();
                    alert('Por favor, corrija os erros antes de continuar.');
                    return;
                }

                // Verificar se temos imagens selecionadas que precisam ser anexadas
                if (this.selectedImages.length > 0) {
                    event.preventDefault();
                    this.submitting = true;
                    
                    // Criar FormData com todos os dados do formulário
                    const form = this.$refs.form;
                    const formData = new FormData(form);
                    
                    // Remover qualquer input de imagens existente do FormData
                    formData.delete('imagens[]');
                    
                    // Adicionar imagens selecionadas
                    this.selectedImages.forEach(image => {
                        formData.append('imagens[]', image.file);
                    });
                    
                    // Enviar via AJAX usando fetch
                                         fetch(form.action, {
                         method: 'POST',
                         body: formData,
                         headers: {
                             'X-Requested-With': 'XMLHttpRequest',
                         }
                     })
                     .then(response => {
                         // Verificar se é JSON (resposta de API) ou HTML (redirect)
                         const contentType = response.headers.get('content-type');
                         if (contentType && contentType.includes('application/json')) {
                             return response.json().then(data => {
                                 if (!response.ok) {
                                     throw new Error(data.message || 'Erro na resposta: ' + response.status);
                                 }
                                 // Limpar rascunho após envio bem-sucedido
                                 localStorage.removeItem('relatorio_draft');
                                 return data;
                             });
                         } else {
                             if (response.ok) {
                                 // Limpar rascunho após envio bem-sucedido
                                 localStorage.removeItem('relatorio_draft');
                                 return response.text().then(text => ({ redirect: true, html: text }));
                             } else {
                                 throw new Error('Erro na resposta: ' + response.status);
                             }
                         }
                     })
                                         .then(data => {
                         if (data.success === true) {
                             // Resposta JSON de sucesso
                             if (data.redirect_url) {
                                 window.location.href = data.redirect_url;
                             } else if (data.relatorio_id) {
                                 window.location.href = `/relatorios/${data.relatorio_id}`;
                             } else {
                                 window.location.href = "{{ route('relatorios.index') }}";
                             }
                         } else if (data.redirect && data.html) {
                             // Resposta HTML - provavelmente um redirect
                             if (data.html.includes('relatorios/')) {
                                 // Extrair ID do relatório do HTML se possível, ou redirecionar para index
                                 const match = data.html.match(/relatorios\/(\d+)/);
                                 if (match) {
                                     window.location.href = `/relatorios/${match[1]}`;
                                 } else {
                                     window.location.href = "{{ route('relatorios.index') }}";
                                 }
                             } else {
                                 window.location.href = "{{ route('relatorios.index') }}";
                             }
                         } else if (data.success !== false) {
                             // Fallback - redirecionar para a página de relatórios
                             window.location.href = "{{ route('relatorios.index') }}";
                         } else {
                             throw new Error(data.message || 'Erro desconhecido');
                         }
                     })
                                         .catch(error => {
                         console.error('Erro:', error);
                         alert('Erro ao salvar o relatório: ' + error.message);
                         this.submitting = false;
                     });
                } else {
                    // Se não há imagens, permitir o envio normal do formulário
                    this.submitting = true;
                    localStorage.removeItem('relatorio_draft');
                }
            }
        }
    }
</script>
@endpush 