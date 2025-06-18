@extends('layouts.app')

@section('title', 'Editar Motor')

@section('content')
<div class="container">


    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-2 mb-lg-0">
            <h1 class="h4 mb-1">
                <i class="bi bi-pencil me-2"></i>
                Editar Motor
                @if($motor->tag)
                    <span class="badge bg-primary ms-2">{{ $motor->tag }}</span>
                @endif
            </h1>
            <p class="text-muted mb-0 small">{{ $motor->equipment ?? 'Motor #' . $motor->id }}</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('motores.show', $motor) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Voltar
            </a>
        </div>
    </div>

    <form action="{{ route('motores.update', $motor) }}" method="POST" enctype="multipart/form-data">
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
                            <div class="col-md-8">
                                <label for="equipment" class="form-label fw-semibold mb-1">Nome do Equipamento: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('equipment') is-invalid @enderror" 
                                       id="equipment" name="equipment" 
                                       value="{{ old('equipment', $motor->equipment) }}" 
                                       placeholder="Ex: Bomba Centrífuga Principal"
                                       required>
                                @error('equipment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label for="tag" class="form-label fw-semibold mb-1">Tag:</label>
                                <input type="text" class="form-control @error('tag') is-invalid @enderror" 
                                       id="tag" name="tag" 
                                       value="{{ old('tag', $motor->tag) }}" 
                                       placeholder="Ex: MOT-001">
                                @error('tag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="equipment_type" class="form-label fw-semibold mb-1">Tipo de Equipamento:</label>
                                <input type="text" class="form-control @error('equipment_type') is-invalid @enderror" 
                                       id="equipment_type" name="equipment_type" 
                                       value="{{ old('equipment_type', $motor->equipment_type) }}" 
                                       placeholder="Ex: Bomba, Ventilador, Compressor">
                                @error('equipment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="manufacturer" class="form-label fw-semibold mb-1">Fabricante:</label>
                                <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" 
                                       id="manufacturer" name="manufacturer" 
                                       value="{{ old('manufacturer', $motor->manufacturer) }}" 
                                       placeholder="Ex: WEG, Siemens, ABB">
                                @error('manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="frame_manufacturer" class="form-label fw-semibold mb-1">Fabricante do Frame:</label>
                                <input type="text" class="form-control @error('frame_manufacturer') is-invalid @enderror" 
                                       id="frame_manufacturer" name="frame_manufacturer" 
                                       value="{{ old('frame_manufacturer', $motor->frame_manufacturer) }}" 
                                       placeholder="Ex: WEG">
                                @error('frame_manufacturer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="location" class="form-label fw-semibold mb-1">Localização:</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" 
                                       value="{{ old('location', $motor->location) }}" 
                                       placeholder="Ex: Sala de Bombas - Setor A">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <div class="col-md-6">
                                <label for="power_kw" class="form-label fw-semibold mb-1">Potência (kW):</label>
                                <input type="number" step="0.01" min="0" max="9999.99"
                                       class="form-control @error('power_kw') is-invalid @enderror" 
                                       id="power_kw" name="power_kw" 
                                       value="{{ old('power_kw', $motor->power_kw) }}" 
                                       placeholder="Ex: 15.5">
                                @error('power_kw')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="power_cv" class="form-label fw-semibold mb-1">Potência (CV):</label>
                                <input type="number" step="0.01" min="0" max="9999.99"
                                       class="form-control @error('power_cv') is-invalid @enderror" 
                                       id="power_cv" name="power_cv" 
                                       value="{{ old('power_cv', $motor->power_cv) }}" 
                                       placeholder="Ex: 20.8">
                                @error('power_cv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="rotation" class="form-label fw-semibold mb-1">Rotação (RPM):</label>
                                <input type="number" min="0" max="999999"
                                       class="form-control @error('rotation') is-invalid @enderror" 
                                       id="rotation" name="rotation" 
                                       value="{{ old('rotation', $motor->rotation) }}" 
                                       placeholder="Ex: 1750">
                                @error('rotation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="rated_current" class="form-label fw-semibold mb-1">Corrente Nominal (A):</label>
                                <input type="number" step="0.01" min="0" max="9999.99"
                                       class="form-control @error('rated_current') is-invalid @enderror" 
                                       id="rated_current" name="rated_current" 
                                       value="{{ old('rated_current', $motor->rated_current) }}" 
                                       placeholder="Ex: 28.5">
                                @error('rated_current')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="configured_current" class="form-label fw-semibold mb-1">Corrente Configurada (A):</label>
                                <input type="number" step="0.01" min="0" max="9999.99"
                                       class="form-control @error('configured_current') is-invalid @enderror" 
                                       id="configured_current" name="configured_current" 
                                       value="{{ old('configured_current', $motor->configured_current) }}" 
                                       placeholder="Ex: 25.0">
                                @error('configured_current')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estoque e Configurações -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-box-seam me-2"></i>
                            Estoque e Configurações
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="stock_reserve" class="form-label fw-semibold mb-1">Estoque Reserva:</label>
                                <select class="form-select @error('stock_reserve') is-invalid @enderror" 
                                        id="stock_reserve" name="stock_reserve">
                                    <option value="">Selecione</option>
                                    @foreach($stockReserveOptions as $key => $option)
                                        <option value="{{ $key }}" {{ old('stock_reserve', $motor->stock_reserve) == $key ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('stock_reserve')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="storage" class="form-label fw-semibold mb-1">Armazenamento:</label>
                                <input type="text" class="form-control @error('storage') is-invalid @enderror" 
                                       id="storage" name="storage" 
                                       value="{{ old('storage', $motor->storage) }}" 
                                       placeholder="Ex: Almoxarifado Central - Prateleira A3">
                                @error('storage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="photo" class="form-label fw-semibold mb-1">Foto do Motor:</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <div class="form-text small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Formatos aceitos: JPEG, PNG, JPG, GIF, WEBP. Tamanho máximo: 7MB
                                    @if($motor->photo)
                                        <br><strong>Foto atual:</strong> {{ basename($motor->photo) }}
                                    @endif
                                </div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <a href="{{ route('motores.show', $motor) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-check-lg me-1"></i>
                        Atualizar Motor
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Foto Atual -->
                @if($motor->photo)
                    <div class="card mb-4" id="currentPhotoCard">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-camera me-2"></i>
                                Foto Atual
                            </h6>
                            <button type="button" class="btn btn-outline-light btn-sm" 
                                    onclick="deletePhoto({{ $motor->id }})"
                                    title="Excluir foto">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="card-body p-3 text-center">
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 class="img-fluid rounded shadow" 
                                 style="max-width: 100%; max-height: 200px;"
                                 alt="Foto Atual do Motor">
                            <div class="mt-2 small text-muted">
                                {{ basename($motor->photo) }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Resumo Atual -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dados Atuais
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2">
                            <strong class="text-muted small">Equipamento:</strong><br>
                            <span class="fw-semibold">{{ $motor->equipment ?? 'N/A' }}</span>
                        </div>
                        
                        @if($motor->tag)
                            <div class="mb-2">
                                <strong class="text-muted small">Tag:</strong><br>
                                <span class="badge bg-primary">{{ $motor->tag }}</span>
                            </div>
                        @endif
                        
                        @if($motor->power_kw || $motor->power_cv)
                            <div class="mb-2">
                                <strong class="text-muted small">Potência:</strong><br>
                                <span class="fw-semibold text-success">{{ $motor->power_display }}</span>
                            </div>
                        @endif
                        
                        @if($motor->rotation)
                            <div class="mb-2">
                                <strong class="text-muted small">Rotação:</strong><br>
                                <span class="fw-semibold text-info">{{ $motor->rotation_display }}</span>
                            </div>
                        @endif
                        
                        @if($motor->stock_reserve)
                            <div class="mb-2">
                                <strong class="text-muted small">Estoque:</strong><br>
                                <span class="badge bg-{{ $motor->stock_reserve == 'Sim' ? 'success' : ($motor->stock_reserve == 'Não' ? 'danger' : 'warning') }}">
                                    {{ $motor->stock_reserve }}
                                </span>
                            </div>
                        @endif
                        
                        <hr class="my-2">
                        
                        <div class="small">
                            <strong class="text-muted">Criado em:</strong><br>
                            <span>{{ $motor->data_criacao->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        @if($motor->data_atualizacao != $motor->data_criacao)
                            <div class="small mt-1">
                                <strong class="text-muted">Última atualização:</strong><br>
                                <span>{{ $motor->data_atualizacao->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ajuda -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle me-2"></i>
                            Ajuda
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="text-primary">Campos Obrigatórios</h6>
                        <ul class="list-unstyled mb-3 small">
                            <li><strong>Nome do Equipamento:</strong> Identificação principal do motor</li>
                        </ul>

                        <h6 class="text-primary">Foto</h6>
                        <ul class="list-unstyled mb-3 small">
                            <li>• Selecione uma nova foto para substituir a atual</li>
                            <li>• Deixe em branco para manter a foto atual</li>
                        </ul>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Atenção:</strong> As alterações serão salvas permanentemente.
                        </div>
                    </div>
                </div>

                <!-- Preview da Nova Imagem -->
                <div class="card" id="imagePreviewCard" style="display: none;">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-camera me-2"></i>
                            Nova Foto
                        </h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('photo').addEventListener('change', function(e) {
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

function deletePhoto(motorId) {
    if (confirm('Tem certeza que deseja excluir esta foto? Esta ação não pode ser desfeita.')) {
        fetch(`/motores/${motorId}/photo`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove o card da foto atual
                document.getElementById('currentPhotoCard').style.display = 'none';
                
                // Mostra mensagem de sucesso
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showAlert('error', 'Erro ao excluir a foto. Tente novamente.');
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Adiciona o alerta no topo da página
    const container = document.querySelector('.container');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Remove o alerta após 5 segundos
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection 