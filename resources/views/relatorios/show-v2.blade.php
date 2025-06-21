@extends('layouts.app')

@section('title', 'Relatório V2: ' . $relatorio->titulo . ' - Sistema de Relatórios')

@push('styles')
<style>
    .relatorio-header {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 2rem;
    }

    .info-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .info-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .equipamento-item {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .equipamento-item:hover {
        border-color: #198754;
        box-shadow: 0 0.25rem 0.5rem rgba(25, 135, 84, 0.1);
    }

    .equipamento-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .equipamento-numero {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .badge-pendente { background: #fff3cd; color: #856404; }
    .badge-em_andamento { background: #d1ecf1; color: #0c5460; }
    .badge-concluido { background: #d4edda; color: #155724; }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .image-item img:hover {
        transform: scale(1.05);
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
<div class="container">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="bi bi-gear-wide-connected me-2 text-success"></i>
                Relatório Multi-Equipamento
                <span class="v2-badge ms-2">V2</span>
            </h2>
            <p class="text-muted mb-0">{{ $relatorio->titulo }}</p>
        </div>
        <div class="action-buttons">
            <a href="{{ route('relatorios-v2.edit', $relatorio) }}" class="btn btn-warning btn-action">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('relatorios-v2.pdf', $relatorio) }}" class="btn btn-danger btn-action" target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary btn-action">
                <i class="bi bi-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações Básicas -->
        <div class="col-12 col-lg-4">
            <div class="info-card">
                <div class="relatorio-header text-center">
                    <div class="mb-3">
                        <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">Informações Gerais</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-card-text me-2 text-success"></i>Título:
                        </label>
                        <p class="mb-0">{{ $relatorio->titulo }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-file-text me-2 text-success"></i>Descrição:
                        </label>
                        <p class="mb-0">{{ $relatorio->descricao }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-geo-alt me-2 text-success"></i>Local:
                        </label>
                        <p class="mb-0">{{ $relatorio->local->nome ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-date me-2 text-success"></i>Data da Ocorrência:
                        </label>
                        <p class="mb-0">{{ $relatorio->data_ocorrencia->format('d/m/Y') }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-clipboard-check me-2 text-success"></i>Status:
                        </label>
                        <span class="badge {{ $relatorio->status_badge }}">{{ $relatorio->status_label }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-exclamation-triangle me-2 text-success"></i>Prioridade:
                        </label>
                        <span class="badge {{ $relatorio->prioridade_badge }}">{{ $relatorio->prioridade_label }}</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-percent me-2 text-success"></i>Progresso:
                        </label>
                        <div class="progress mt-2" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: {{ $relatorio->progresso }}%">
                                {{ $relatorio->progresso }}%
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">
                            <i class="bi bi-person me-2 text-success"></i>Criado por:
                        </label>
                        <p class="mb-0">{{ $relatorio->usuario->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $relatorio->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipamentos -->
        <div class="col-12 col-lg-8">
            <div class="info-card">
                <div class="relatorio-header text-center">
                    <div class="mb-3">
                        <i class="bi bi-gear-wide-connected" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">Equipamentos e Atividades</h4>
                    <p class="mb-0 opacity-75">{{ $itens->count() }} equipamento(s) envolvido(s)</p>
                </div>
                <div class="card-body">
                    @forelse($itens as $index => $item)
                        <div class="equipamento-item">
                            <div class="equipamento-header">
                                <div class="d-flex align-items-center">
                                    <div class="equipamento-numero me-3">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h5 class="mb-1 fw-bold">{{ $item->equipamento_nome }}</h5>
                                        @if($item->equipamento_codigo)
                                            <small class="text-muted">Código: {{ $item->equipamento_codigo }}</small>
                                        @endif
                                    </div>
                                </div>
                                <span class="status-badge badge-{{ $item->status_item }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status_item)) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-tools me-2 text-success"></i>Descrição da Atividade:
                                </label>
                                <p class="mb-0">{{ $item->descricao_equipamento }}</p>
                            </div>

                            @if($item->observacoes)
                                <div class="mb-0">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-chat-dots me-2 text-success"></i>Observações:
                                    </label>
                                    <p class="mb-0">{{ $item->observacoes }}</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-gear text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">Nenhum equipamento encontrado</h5>
                            <p class="text-muted">Este relatório não possui equipamentos associados.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Imagens -->
            @if($relatorio->imagens->count() > 0)
                <div class="info-card">
                    <div class="relatorio-header text-center">
                        <div class="mb-3">
                            <i class="bi bi-images" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-0 fw-bold">Imagens do Relatório</h4>
                        <p class="mb-0 opacity-75">{{ $relatorio->imagens->count() }} imagem(ns)</p>
                    </div>
                    <div class="card-body">
                        <div class="image-gallery">
                            @foreach($relatorio->imagens as $imagem)
                                <div class="image-item">
                                    <img src="{{ Storage::url($imagem->caminho_arquivo) }}" 
                                         alt="{{ $imagem->nome_original }}"
                                         class="img-fluid"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal"
                                         onclick="showImage('{{ Storage::url($imagem->caminho_arquivo) }}', '{{ $imagem->nome_original }}')">
                                    <div class="text-center mt-2">
                                        <small class="text-muted">{{ $imagem->nome_original }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
function showImage(src, title) {
    document.getElementById('imageModalImg').src = src;
    document.getElementById('imageModalTitle').textContent = title;
}
</script>
@endpush 