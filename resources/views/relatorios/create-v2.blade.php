@extends('layouts.app')

@section('title', 'Novo Relatório V2 (Múltiplos Equipamentos) - Sistema de Relatórios')

@push('styles')
<style>
    [x-cloak] { 
        display: none !important; 
    }
    
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
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
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
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-section:hover {
        border-color: #198754;
        box-shadow: 0 0.125rem 0.25rem rgba(25, 135, 84, 0.1);
    }

    .form-section.active {
        border-color: #198754;
        background: #f8f9fa;
        box-shadow: 0 0.25rem 0.5rem rgba(25, 135, 84, 0.1);
    }

    .form-section h6 {
        color: #198754;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .equipment-item {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .equipment-item:hover {
        border-color: #198754;
        box-shadow: 0 0.125rem 0.25rem rgba(25, 135, 84, 0.1);
    }

    .equipment-item.error {
        border-color: #dc3545;
        background-color: #fff5f5;
    }

    .remove-equipment {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .remove-equipment:hover {
        background: #bb2d3b;
        transform: scale(1.1);
    }

    .add-equipment-btn {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 0.75rem;
        color: white;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .add-equipment-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.375rem 0.75rem rgba(25, 135, 84, 0.25);
        color: white;
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

    .draft-indicator {
        background: #d1ecf1;
        border: 1px solid #bee5eb;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-size: 0.875rem;
        display: none;
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
        color: #198754;
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
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1);
    }

    .image-preview {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #e9ecef;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-preview:hover {
        border-color: #198754;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .form-header {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .equipment-item {
            padding: 1rem;
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
<div class="container" x-data="relatorioFormV2()" x-cloak>
    
    <!-- Header -->
    <div class="mb-3 mb-md-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-1 fw-bold fs-4 fs-md-2">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Novo Relatório V2
                    <span class="badge bg-success ms-2">TESTE</span>
                </h2>
                <p class="text-muted mb-0 d-none d-md-block">
                    Versão de teste com múltiplos equipamentos por relatório
                </p>
            </div>
        </div>
    </div>

    <!-- Indicador de Rascunho -->
    <div x-show="draftSaved" x-transition class="draft-indicator mb-3">
        <i class="bi bi-cloud-check me-2"></i>
        Rascunho salvo automaticamente
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <form @submit.prevent="handleSubmit" id="relatorioFormV2" enctype="multipart/form-data">
                @csrf
                
                <div class="card form-card">
                    <div class="form-header text-center">
                        <div class="icon-section">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Relatório Multi-Equipamento</h4>
                        <p class="mb-0 opacity-75">Um local, múltiplos equipamentos, descrições individuais</p>
                    </div>

                    <div class="card-body p-0">
                        
                        <!-- Informações Básicas -->
                        <div class="form-section" :class="{ 'active': activeSection === 'basic' }" 
                             @click="activeSection = 'basic'">
                            <h6>
                                <i class="bi bi-info-circle me-2"></i>
                                Informações Básicas *
                                <span x-show="isBasicComplete()" class="ms-2">
                                    <i class="bi bi-check-circle text-success"></i>
                                </span>
                            </h6>
                            
                            <div x-show="activeSection === 'basic'" x-transition class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" 
                                               class="form-control" 
                                               id="titulo" 
                                               name="titulo" 
                                               x-model="formData.titulo"
                                               @input="saveDraft()"
                                               :class="{ 'is-invalid': errors.titulo }"
                                               required>
                                        <label for="titulo">
                                            <i class="bi bi-card-text me-2"></i>Título do Relatório *
                                        </label>
                                        <div x-show="errors.titulo" class="invalid-feedback" x-text="errors.titulo"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" 
                                                  id="descricao" 
                                                  name="descricao" 
                                                  x-model="formData.descricao"
                                                  @input="saveDraft()"
                                                  :class="{ 'is-invalid': errors.descricao }"
                                                  style="height: 120px" 
                                                  required></textarea>
                                        <label for="descricao">
                                            <i class="bi bi-file-text me-2"></i>Descrição Geral da Atividade *
                                        </label>
                                        <div x-show="errors.descricao" class="invalid-feedback" x-text="errors.descricao"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="date" 
                                               class="form-control" 
                                               id="data_ocorrencia" 
                                               name="data_ocorrencia" 
                                               x-model="formData.data_ocorrencia"
                                               @input="saveDraft()"
                                               :class="{ 'is-invalid': errors.data_ocorrencia }"
                                               required>
                                        <label for="data_ocorrencia">
                                            <i class="bi bi-calendar-date me-2"></i>Data da Ocorrência *
                                        </label>
                                        <div x-show="errors.data_ocorrencia" class="invalid-feedback" x-text="errors.data_ocorrencia"></div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="local_id" 
                                                name="local_id" 
                                                x-model="formData.local_id"
                                                @change="updateEquipamentosByLocal(); saveDraft()"
                                                required>
                                            <option value="">Selecione um local *</option>
                                            @foreach($locais as $local)
                                                <option value="{{ $local->id }}">{{ $local->nome }}</option>
                                            @endforeach
                                        </select>
                                        <label for="local_id">
                                            <i class="bi bi-geo-alt me-2"></i>Local *
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status e Progresso -->
                        <div class="form-section" :class="{ 'active': activeSection === 'status' }" 
                             @click="activeSection = 'status'">
                            <h6>
                                <i class="bi bi-bar-chart me-2"></i>
                                Status e Progresso
                            </h6>
                            
                            <div x-show="activeSection === 'status'" x-transition class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="status" 
                                                name="status" 
                                                x-model="formData.status"
                                                @change="saveDraft()">
                                            <option value="pendente">Pendente</option>
                                            <option value="em_andamento">Em Andamento</option>
                                            <option value="resolvido">Resolvido</option>
                                        </select>
                                        <label for="status">
                                            <i class="bi bi-clipboard-check me-2"></i>Status
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="prioridade" 
                                                name="prioridade" 
                                                x-model="formData.prioridade"
                                                @change="saveDraft()">
                                            <option value="baixa">Baixa</option>
                                            <option value="media">Média</option>
                                            <option value="alta">Alta</option>
                                            <option value="critica">Crítica</option>
                                        </select>
                                        <label for="prioridade">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Prioridade
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="progresso" class="form-label">
                                        <i class="bi bi-percent me-2"></i>Progresso: <span x-text="formData.progresso">0</span>%
                                    </label>
                                    <div class="progress-container">
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar bg-success" 
                                                 role="progressbar" 
                                                 :style="{ width: formData.progresso + '%' }" 
                                                 :aria-valuenow="formData.progresso" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <div class="progress-value" x-text="formData.progresso + '%'"></div>
                                    </div>
                                    <input type="range" 
                                           class="form-range" 
                                           id="progresso" 
                                           name="progresso" 
                                           min="0" 
                                           max="100" 
                                           x-model="formData.progresso"
                                           @input="saveDraft()">
                                </div>
                            </div>
                        </div>

                        <!-- Equipamentos (Principal) -->
                        <div class="form-section" :class="{ 'active': activeSection === 'equipment' }" 
                             @click="activeSection = 'equipment'">
                            <h6>
                                <i class="bi bi-gear-wide-connected me-2"></i>
                                Equipamentos e Atividades *
                                <span x-show="formData.itens.length > 0" class="ms-2">
                                    <span class="badge bg-success" x-text="formData.itens.length"></span>
                                </span>
                            </h6>
                            
                            <div x-show="activeSection === 'equipment'" x-transition>
                                
                                <!-- Lista de Equipamentos -->
                                <div x-show="formData.itens.length > 0" class="mb-3">
                                    <template x-for="(item, index) in formData.itens" :key="index">
                                        <div class="equipment-item" :class="{ 'error': itemHasError(index) }">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-gear me-2"></i>
                                                    Equipamento <span x-text="index + 1"></span>
                                                </h6>
                                                <button type="button" 
                                                        class="remove-equipment"
                                                        @click="removeEquipment(index)"
                                                        x-show="formData.itens.length > 1">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" 
                                                                x-model="item.equipamento_id"
                                                                @change="saveDraft()"
                                                                :name="`itens[${index}][equipamento_id]`"
                                                                required>
                                                            <option value="">Selecione um equipamento *</option>
                                                            <template x-for="equipamento in filteredEquipamentos" :key="equipamento.id">
                                                                <option :value="equipamento.id" x-text="equipamento.nome + (equipamento.codigo ? ' - ' + equipamento.codigo : '')"></option>
                                                            </template>
                                                        </select>
                                                        <label>
                                                            <i class="bi bi-cpu me-2"></i>Equipamento *
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" 
                                                                x-model="item.status_item"
                                                                @change="saveDraft()"
                                                                :name="`itens[${index}][status_item]`">
                                                            <option value="pendente">Pendente</option>
                                                            <option value="em_andamento">Em Andamento</option>
                                                            <option value="concluido">Concluído</option>
                                                        </select>
                                                        <label>
                                                            <i class="bi bi-check-circle me-2"></i>Status do Item
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" 
                                                                  x-model="item.descricao_equipamento"
                                                                  @input="saveDraft()"
                                                                  :name="`itens[${index}][descricao_equipamento]`"
                                                                  style="height: 100px" 
                                                                  required></textarea>
                                                        <label>
                                                            <i class="bi bi-file-text me-2"></i>Descrição da Atividade *
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" 
                                                                  x-model="item.observacoes"
                                                                  @input="saveDraft()"
                                                                  :name="`itens[${index}][observacoes]`"
                                                                  style="height: 80px"></textarea>
                                                        <label>
                                                            <i class="bi bi-chat-dots me-2"></i>Observações (Opcional)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Botão Adicionar Equipamento -->
                                <div class="text-center">
                                    <button type="button" 
                                            class="btn add-equipment-btn"
                                            @click="addEquipment()"
                                            :disabled="!formData.local_id">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Adicionar Equipamento
                                    </button>
                                    <div x-show="!formData.local_id" class="form-text text-warning mt-2">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Primeiro selecione um local
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Imagens -->
                        <div class="form-section" :class="{ 'active': activeSection === 'images' }" 
                             @click="activeSection = 'images'">
                            <h6>
                                <i class="bi bi-images me-2"></i>
                                Imagens do Relatório
                                <small class="text-muted fw-normal">(Máximo 7MB por imagem)</small>
                                <span x-show="selectedImages.length > 0" class="ms-2">
                                    <span class="badge bg-info" x-text="selectedImages.length"></span>
                                </span>
                            </h6>
                            
                            <div x-show="activeSection === 'images'" x-transition class="row g-3">
                                <div class="col-12">
                                    <label for="imagens" class="form-label fw-semibold">
                                        <i class="bi bi-cloud-upload me-2"></i>Selecionar Imagens
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="imagens" 
                                           name="imagens[]" 
                                           multiple 
                                           accept="image/*"
                                           @change="handleImageSelection">
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Formatos aceitos: JPG, PNG, GIF, WEBP. Máximo 7MB por imagem.
                                    </div>
                                </div>

                                <!-- Preview das Imagens -->
                                <div x-show="selectedImages.length > 0" class="col-12">
                                    <h6 class="small fw-bold text-muted mb-2">Preview das Imagens:</h6>
                                    <div class="row g-2">
                                        <template x-for="(image, index) in selectedImages" :key="index">
                                            <div class="col-6 col-md-3">
                                                <div class="position-relative">
                                                    <img :src="image.url" 
                                                         class="image-preview w-100" 
                                                         :alt="image.file.name"
                                                         @click="openImageModal(image.url, image.file.name)">
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                                            @click="removeImage(index)"
                                                            style="border-radius: 50%; width: 25px; height: 25px; padding: 0;">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                    <div class="text-center mt-1">
                                                        <small class="text-muted" x-text="image.file.name"></small><br>
                                                        <small class="text-muted" x-text="formatFileSize(image.file.size)"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
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
                                <button type="button" class="btn btn-outline-info btn-action" @click="clearForm()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Limpar
                                </button>
                                <button type="submit" class="btn btn-success btn-action" :disabled="!isFormValid()">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span x-show="!isSubmitting">Criar Relatório</span>
                                    <span x-show="isSubmitting">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Criando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para visualizar imagens -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Visualizar Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imageModalImg" src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Aguardar Alpine.js ser carregado
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js foi inicializado!');
});

function relatorioFormV2() {
    return {
        // Estado do formulário
        activeSection: 'basic',
        isSubmitting: false,
        draftSaved: false,
        
        // Dados do formulário
        formData: {
            titulo: '',
            descricao: '',
            data_ocorrencia: new Date().toISOString().split('T')[0],
            status: 'pendente',
            prioridade: 'media',
            progresso: 0,
            local_id: '',
            itens: []
        },
        
        // Equipamentos e imagens
        equipamentos: @json($equipamentos),
        selectedImages: [],
        errors: {},
        
        init() {
            console.log('Inicializando componente Alpine...');
            
            // Garantir que isSubmitting inicia como false
            this.isSubmitting = false;
            
            // Carregar draft se existir
            this.loadDraft();
            
            // Inicializar com um equipamento vazio se não houver nenhum
            if (this.formData.itens.length === 0) {
                this.addEquipment();
            }
            
            console.log('Componente inicializado com sucesso!');
        },
        
        // Computed: equipamentos filtrados pelo local
        get filteredEquipamentos() {
            if (!this.formData.local_id) return [];
            return this.equipamentos.filter(eq => eq.local_id == this.formData.local_id);
        },
        
        // Verificar se seção básica está completa
        isBasicComplete() {
            return this.formData.titulo.trim() && 
                   this.formData.descricao.trim() && 
                   this.formData.data_ocorrencia && 
                   this.formData.local_id;
        },
        
        // Verificar se formulário é válido
        isFormValid() {
            return this.isBasicComplete() && 
                   this.formData.itens.length > 0 && 
                   this.formData.itens.every(item => 
                       item.equipamento_id && item.descricao_equipamento.trim()
                   );
        },
        
        // Verificar se item tem erro
        itemHasError(index) {
            const item = this.formData.itens[index];
            return !item.equipamento_id || !item.descricao_equipamento.trim();
        },
        

        
        removeEquipment(index) {
            this.formData.itens.splice(index, 1);
            this.saveDraft();
        },
        
        // Atualizar equipamentos quando local muda
        updateEquipamentosByLocal() {
            // Limpar equipamentos selecionados do local anterior
            this.formData.itens.forEach(item => {
                item.equipamento_id = '';
            });
            this.saveDraft();
        },
        
        // Debug: Adicionar logs
        addEquipment() {
            console.log('Adicionando equipamento...');
            this.formData.itens.push({
                equipamento_id: '',
                descricao_equipamento: '',
                observacoes: '',
                status_item: 'pendente'
            });
            console.log('Total de itens:', this.formData.itens.length);
            this.saveDraft();
        },
        
        // Gerenciar imagens
        handleImageSelection(event) {
            const files = Array.from(event.target.files);
            this.selectedImages = [];
            
            files.forEach(file => {
                if (file.size > 7 * 1024 * 1024) { // 7MB
                    alert(`Arquivo ${file.name} é muito grande. Máximo 7MB.`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.selectedImages.push({
                        file: file,
                        url: e.target.result
                    });
                };
                reader.readAsDataURL(file);
            });
        },
        
        removeImage(index) {
            this.selectedImages.splice(index, 1);
            
            // Atualizar o input file
            const fileInput = document.getElementById('imagens');
            const dt = new DataTransfer();
            this.selectedImages.forEach(img => dt.items.add(img.file));
            fileInput.files = dt.files;
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },
        
        // Submissão do formulário
        async handleSubmit() {
            if (!this.isFormValid()) {
                alert('Por favor, preencha todos os campos obrigatórios.');
                return;
            }
            
            this.isSubmitting = true;
            
            try {
                const formData = new FormData();
                
                // Dados básicos
                Object.keys(this.formData).forEach(key => {
                    if (key !== 'itens') {
                        formData.append(key, this.formData[key]);
                    }
                });
                
                // Itens (equipamentos)
                this.formData.itens.forEach((item, index) => {
                    Object.keys(item).forEach(key => {
                        formData.append(`itens[${index}][${key}]`, item[key]);
                    });
                });
                
                // Imagens
                this.selectedImages.forEach(img => {
                    formData.append('imagens[]', img.file);
                });
                
                const response = await fetch('{{ route("relatorios-v2.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Limpar draft
                    localStorage.removeItem('relatorio_v2_draft');
                    
                    // Redirecionar
                    window.location.href = result.redirect;
                } else {
                    alert('Erro: ' + result.message);
                }
                
            } catch (error) {
                console.error('Erro ao enviar formulário:', error);
                alert('Erro ao criar relatório. Tente novamente.');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        // Gerenciar rascunho
        saveDraft() {
            const draft = {
                formData: this.formData,
                selectedImages: this.selectedImages.map(img => ({
                    name: img.file.name,
                    size: img.file.size
                }))
            };
            
            localStorage.setItem('relatorio_v2_draft', JSON.stringify(draft));
            this.draftSaved = true;
            
            setTimeout(() => {
                this.draftSaved = false;
            }, 2000);
        },
        
        loadDraft() {
            const draft = localStorage.getItem('relatorio_v2_draft');
            if (draft) {
                const parsed = JSON.parse(draft);
                this.formData = { ...this.formData, ...parsed.formData };
            }
        },
        
        clearForm() {
            if (confirm('Tem certeza que deseja limpar o formulário?')) {
                this.formData = {
                    titulo: '',
                    descricao: '',
                    data_ocorrencia: new Date().toISOString().split('T')[0],
                    status: 'pendente',
                    prioridade: 'media',
                    progresso: 0,
                    local_id: '',
                    itens: []
                };
                this.selectedImages = [];
                this.addEquipment();
                localStorage.removeItem('relatorio_v2_draft');
            }
        }
    }
}

// Função para abrir modal de imagem
function openImageModal(src, title) {
    document.getElementById('imageModalImg').src = src;
    document.getElementById('imageModalTitle').textContent = title;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush 