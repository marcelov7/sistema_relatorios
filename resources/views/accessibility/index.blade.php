@extends('layouts.app')

@section('title', 'Configurações de Acessibilidade')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-universal-access text-primary me-2"></i>
                        Configurações de Acessibilidade
                    </h2>
                    <p class="text-muted mb-0">Personalize a interface para melhor experiência de uso</p>
                </div>
            </div>

            <form id="accessibilityForm">
                @csrf
                <div class="row g-4">
                    <!-- Tema de Cores -->
                    <div class="col-lg-6">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-palette me-2"></i>
                                    Tema de Cores
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Escolha um tema adaptado para diferentes tipos de daltonismo</p>
                                
                                @foreach($colorThemes as $key => $theme)
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="color_theme" 
                                           id="theme_{{ $key }}" value="{{ $key }}"
                                           {{ $settings['color_theme'] == $key ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="theme_{{ $key }}">
                                        <div class="me-3">
                                            <div class="d-flex">
                                                @foreach($theme['colors'] as $colorKey => $color)
                                                    <div class="color-preview me-1" 
                                                         style="background-color: {{ $color }}; width: 20px; height: 20px; border-radius: 3px;"></div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $theme['name'] }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $theme['description'] }}</small>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Configurações Visuais -->
                    <div class="col-lg-6">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye me-2"></i>
                                    Configurações Visuais
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Modo Escuro -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="dark_mode" 
                                               id="dark_mode" {{ $settings['dark_mode'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dark_mode">
                                            <i class="bi bi-moon me-2"></i>
                                            <strong>Modo Escuro</strong>
                                            <br>
                                            <small class="text-muted">Reduz o cansaço visual em ambientes com pouca luz</small>
                                        </label>
                                    </div>
                                </div>

                                <!-- Alto Contraste -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="high_contrast" 
                                               id="high_contrast" {{ $settings['high_contrast'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="high_contrast">
                                            <i class="bi bi-contrast me-2"></i>
                                            <strong>Alto Contraste</strong>
                                            <br>
                                            <small class="text-muted">Aumenta o contraste para melhor legibilidade</small>
                                        </label>
                                    </div>
                                </div>

                                <!-- Reduzir Animações -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="reduce_motion" 
                                               id="reduce_motion" {{ $settings['reduce_motion'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="reduce_motion">
                                            <i class="bi bi-pause-circle me-2"></i>
                                            <strong>Reduzir Animações</strong>
                                            <br>
                                            <small class="text-muted">Minimiza efeitos de movimento e transições</small>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tamanho da Fonte -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-fonts me-2"></i>
                                        Tamanho da Fonte
                                    </label>
                                    <select class="form-select" name="font_size">
                                        @foreach($fontSizes as $key => $size)
                                            <option value="{{ $key }}" {{ $settings['font_size'] == $key ? 'selected' : '' }}>
                                                {{ $size['name'] }} ({{ number_format($size['scale'] * 100) }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="col-12">
                        <div class="card shadow border-0">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-eye-fill me-2"></i>
                                    Pré-visualização
                                </h5>
                            </div>
                            <div class="card-body" id="preview-area">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card border-primary">
                                            <div class="card-body text-center">
                                                <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                                <h6 class="mt-2">Sucesso</h6>
                                                <small class="text-muted">Operação concluída</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-warning">
                                            <div class="card-body text-center">
                                                <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                                                <h6 class="mt-2">Atenção</h6>
                                                <small class="text-muted">Requer verificação</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-danger">
                                            <div class="card-body text-center">
                                                <i class="bi bi-x-circle-fill text-danger fs-2"></i>
                                                <h6 class="mt-2">Erro</h6>
                                                <small class="text-muted">Falha na operação</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-info">
                                            <div class="card-body text-center">
                                                <i class="bi bi-info-circle-fill text-info fs-2"></i>
                                                <h6 class="mt-2">Informação</h6>
                                                <small class="text-muted">Dados importantes</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" id="resetSettings">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Restaurar Padrão
                            </button>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" id="previewChanges">
                                    <i class="bi bi-eye me-2"></i>
                                    Pré-visualizar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast para feedback -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="accessibilityToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="bi bi-universal-access text-primary me-2"></i>
            <strong class="me-auto">Acessibilidade</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Configurações aplicadas com sucesso!
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('accessibilityForm');
    const previewArea = document.getElementById('preview-area');
    const toast = new bootstrap.Toast(document.getElementById('accessibilityToast'));

    // Aplicar configurações em tempo real
    function applySettings() {
        const formData = new FormData(form);
        const settings = Object.fromEntries(formData);
        
        // Aplicar tema de cores
        applyColorTheme(settings.color_theme || 'default');
        
        // Aplicar modo escuro
        document.body.classList.toggle('dark-mode', settings.dark_mode === 'on');
        
        // Aplicar alto contraste
        document.body.classList.toggle('high-contrast', settings.high_contrast === 'on');
        
        // Aplicar redução de movimento
        document.body.classList.toggle('reduce-motion', settings.reduce_motion === 'on');
        
        // Aplicar tamanho da fonte
        applyFontSize(settings.font_size || 'normal');
    }

    // Aplicar tema de cores
    function applyColorTheme(theme) {
        const themes = @json($colorThemes);
        const colors = themes[theme]?.colors || themes.default.colors;
        
        const root = document.documentElement;
        Object.entries(colors).forEach(([key, value]) => {
            root.style.setProperty(`--bs-${key}`, value);
        });
    }

    // Aplicar tamanho da fonte
    function applyFontSize(size) {
        const sizes = @json($fontSizes);
        const scale = sizes[size]?.scale || 1.0;
        
        document.documentElement.style.setProperty('--font-scale', scale);
        document.body.style.fontSize = `${scale}rem`;
    }

    // Event listeners
    form.addEventListener('change', applySettings);
    
    document.getElementById('previewChanges').addEventListener('click', applySettings);
    
    document.getElementById('resetSettings').addEventListener('click', function() {
        form.reset();
        // Marcar configurações padrão
        document.querySelector('input[name="color_theme"][value="default"]').checked = true;
        document.querySelector('select[name="font_size"]').value = 'normal';
        applySettings();
    });

    // Submissão do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('{{ route("accessibility.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toast.show();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    });

    // Aplicar configurações iniciais
    applySettings();
});
</script>

<style>
/* Estilos para pré-visualização */
.color-preview {
    border: 1px solid #dee2e6;
}

/* Modo escuro */
.dark-mode {
    background-color: #1a1a1a !important;
    color: #ffffff !important;
}

.dark-mode .card {
    background-color: #2d2d2d !important;
    border-color: #4a4a4a !important;
    color: #ffffff !important;
}

.dark-mode .navbar-modern {
    background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%) !important;
}

/* Alto contraste */
.high-contrast {
    filter: contrast(150%);
}

/* Redução de movimento */
.reduce-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}
</style>
@endsection 