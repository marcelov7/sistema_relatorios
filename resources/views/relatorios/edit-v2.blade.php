@extends('layouts.app')

@section('title', 'Editar Relatório V2: ' . $relatorio->titulo . ' - Sistema de Relatórios')

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

    .form-header {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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
        border-color: #ffc107;
        box-shadow: 0 0.125rem 0.25rem rgba(255, 193, 7, 0.1);
    }

    .form-section.active {
        border-color: #ffc107;
        background: #f8f9fa;
        box-shadow: 0 0.25rem 0.5rem rgba(255, 193, 7, 0.1);
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
        border-color: #ffc107;
        box-shadow: 0 0.125rem 0.25rem rgba(255, 193, 7, 0.1);
    }

    .btn-action {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 0.375rem 0.75rem rgba(0, 0, 0, 0.15);
    }

    .v2-badge {
        background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@section('content')
<div class="container" x-data="relatorioEditV2()" x-cloak>
    
    <!-- Header -->
    <div class="mb-3 mb-md-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('relatorios-v2.show', $relatorio) }}" class="btn btn-outline-secondary me-3 d-md-none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-1 fw-bold fs-4 fs-md-2">
                    <i class="bi bi-pencil me-2 text-warning"></i>
                    Editar Relatório V2
                    <span class="v2-badge ms-2">EDIT</span>
                </h2>
                <p class="text-muted mb-0 d-none d-md-block">
                    Editando: {{ $relatorio->titulo }}
                </p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <form @submit.prevent="handleSubmit" id="relatorioEditFormV2" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card form-card">
                    <div class="form-header text-center">
                        <div class="icon-section">
                            <i class="bi bi-pencil-square" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Editar Relatório Multi-Equipamento</h4>
                        <p class="mb-0 opacity-75">Modifique as informações do relatório</p>
                    </div>

                    <div class="card-body p-0">
                        
                        <!-- Informações Básicas -->
                        <div class="form-section" :class="{ 'active': activeSection === 'basic' }" 
                             @click="activeSection = 'basic'">
                            <h6>
                                <i class="bi bi-info-circle me-2"></i>
                                Informações Básicas *
                            </h6>
                            
                            <div x-show="activeSection === 'basic'" x-transition class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" 
                                               class="form-control" 
                                               id="titulo" 
                                               name="titulo" 
                                               x-model="formData.titulo"
                                               required>
                                        <label for="titulo">
                                            <i class="bi bi-card-text me-2"></i>Título do Relatório *
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" 
                                                  id="descricao" 
                                                  name="descricao" 
                                                  x-model="formData.descricao"
                                                  style="height: 120px" 
                                                  required></textarea>
                                        <label for="descricao">
                                            <i class="bi bi-file-text me-2"></i>Descrição Geral da Atividade *
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="date" 
                                               class="form-control" 
                                               id="data_ocorrencia" 
                                               name="data_ocorrencia" 
                                               x-model="formData.data_ocorrencia"
                                               required>
                                        <label for="data_ocorrencia">
                                            <i class="bi bi-calendar-date me-2"></i>Data da Ocorrência *
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="local_id" 
                                                name="local_id" 
                                                x-model="formData.local_id"
                                                @change="updateEquipamentosByLocal()"
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

                                <div class="col-12 col-md-4">
                                    <div class="form-floating">
                                        <select class="form-select" 
                                                id="status" 
                                                name="status" 
                                                x-model="formData.status">
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
                                                x-model="formData.prioridade">
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
                                    <input type="range" 
                                           class="form-range" 
                                           id="progresso" 
                                           name="progresso" 
                                           min="0" 
                                           max="100" 
                                           x-model="formData.progresso">
                                </div>
                            </div>
                        </div>

                        <!-- Equipamentos -->
                        <div class="form-section" :class="{ 'active': activeSection === 'equipment' }" 
                             @click="activeSection = 'equipment'">
                            <h6>
                                <i class="bi bi-gear-wide-connected me-2"></i>
                                Equipamentos e Atividades *
                                <span x-show="formData.itens.length > 0" class="ms-2">
                                    <span class="badge bg-warning" x-text="formData.itens.length"></span>
                                </span>
                            </h6>
                            
                            <div x-show="activeSection === 'equipment'" x-transition>
                                
                                <!-- Lista de Equipamentos -->
                                <div x-show="formData.itens.length > 0" class="mb-3">
                                    <template x-for="(item, index) in formData.itens" :key="index">
                                        <div class="equipment-item">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-gear me-2"></i>
                                                    Equipamento <span x-text="index + 1"></span>
                                                </h6>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm"
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
                                                                x-model="item.status_item">
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
                                            class="btn btn-warning btn-action"
                                            @click="addEquipment()"
                                            :disabled="!formData.local_id">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Adicionar Equipamento
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="text-center p-4">
                            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                <a href="{{ route('relatorios-v2.show', $relatorio) }}" class="btn btn-outline-secondary btn-action">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning btn-action" :disabled="!isFormValid()">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <span x-show="!isSubmitting">Atualizar Relatório</span>
                                    <span x-show="isSubmitting">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Atualizando...
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
@endsection

@push('scripts')
<script>
function relatorioEditV2() {
    return {
        // Estado do formulário
        activeSection: 'basic',
        isSubmitting: false,
        
        // Dados do formulário
        formData: {
            titulo: '{{ $relatorio->titulo }}',
            descricao: '{{ addslashes($relatorio->descricao) }}',
            data_ocorrencia: '{{ $relatorio->data_ocorrencia->format('Y-m-d') }}',
            status: '{{ $relatorio->status }}',
            prioridade: '{{ $relatorio->prioridade }}',
            progresso: {{ $relatorio->progresso }},
            local_id: '{{ $relatorio->local_id }}',
            itens: @json($itens->map(function($item) {
                return [
                    'equipamento_id' => $item->equipamento_id,
                    'descricao_equipamento' => $item->descricao_equipamento,
                    'observacoes' => $item->observacoes,
                    'status_item' => $item->status_item
                ];
            }))
        },
        
        // Equipamentos
        equipamentos: @json($equipamentos),
        
        init() {
            console.log('Formulário de edição inicializado');
            console.log('Itens carregados:', this.formData.itens.length);
        },
        
        // Computed: equipamentos filtrados pelo local
        get filteredEquipamentos() {
            if (!this.formData.local_id) return [];
            return this.equipamentos.filter(eq => eq.local_id == this.formData.local_id);
        },
        
        // Verificar se formulário é válido
        isFormValid() {
            return this.formData.titulo.trim() && 
                   this.formData.descricao.trim() && 
                   this.formData.data_ocorrencia && 
                   this.formData.local_id &&
                   this.formData.itens.length > 0 && 
                   this.formData.itens.every(item => 
                       item.equipamento_id && item.descricao_equipamento.trim()
                   );
        },
        
        addEquipment() {
            this.formData.itens.push({
                equipamento_id: '',
                descricao_equipamento: '',
                observacoes: '',
                status_item: 'pendente'
            });
        },
        
        removeEquipment(index) {
            this.formData.itens.splice(index, 1);
        },
        
        // Atualizar equipamentos quando local muda
        updateEquipamentosByLocal() {
            this.formData.itens.forEach(item => {
                item.equipamento_id = '';
            });
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
                
                const response = await fetch('{{ route("relatorios-v2.update", $relatorio) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    alert('Erro: ' + result.message);
                }
                
            } catch (error) {
                console.error('Erro ao enviar formulário:', error);
                alert('Erro ao atualizar relatório. Tente novamente.');
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
@endpush 