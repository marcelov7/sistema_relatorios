@extends('layouts.app')

@section('title', 'Motores')

@section('content')
<div class="container">


    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-3">
        <div class="mb-2 mb-lg-0">
            <h1 class="h4 mb-1">
                <i class="bi bi-gear-wide-connected me-2"></i>
                Motores
            </h1>
            <p class="text-muted mb-0 small">Gerencie os motores do sistema</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('motores.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                Novo Motor
            </a>
        </div>
    </div>

    <!-- Estatísticas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-gear-wide-connected text-primary fs-4 mb-2"></i>
                    <h6 class="card-title mb-1">Total</h6>
                    <h4 class="text-primary mb-0">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-camera text-success fs-4 mb-2"></i>
                    <h6 class="card-title mb-1">Com Foto</h6>
                    <h4 class="text-success mb-0">{{ $stats['com_foto'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-info bg-opacity-10">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-box-seam text-info fs-4 mb-2"></i>
                    <h6 class="card-title mb-1">Com Estoque</h6>
                    <h4 class="text-info mb-0">{{ $stats['com_estoque'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-tags text-warning fs-4 mb-2"></i>
                    <h6 class="card-title mb-1">Tipos</h6>
                    <h4 class="text-warning mb-0">{{ $stats['tipos_unicos'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('motores.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-semibold mb-1">Buscar:</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Tag, equipamento, fabricante...">
                </div>
                <div class="col-md-3">
                    <label for="stock_reserve" class="form-label">Estoque/Reserva</label>
                    <select name="stock_reserve" id="stock_reserve" class="form-select">
                        <option value="">Todos</option>
                        <option value="Estoque" {{ request('stock_reserve') == 'Estoque' ? 'selected' : '' }}>Estoque</option>
                        <option value="Reserva" {{ request('stock_reserve') == 'Reserva' ? 'selected' : '' }}>Reserva</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="location" class="form-label fw-semibold mb-1">Localização:</label>
                    <input type="text" class="form-control" id="location" name="location" 
                           value="{{ request('location') }}" 
                           placeholder="Local...">
                </div>
                
                <!-- Segunda linha de filtros -->
                <div class="col-md-3">
                    <label for="power_min" class="form-label fw-semibold mb-1">Potência Mín. (kW):</label>
                    <input type="number" name="power_min" id="power_min" class="form-control" 
                           value="{{ request('power_min') }}" placeholder="Ex: 1.5" step="0.1">
                </div>
                <div class="col-md-3">
                    <label for="power_max" class="form-label fw-semibold mb-1">Potência Máx. (kW):</label>
                    <input type="number" name="power_max" id="power_max" class="form-control" 
                           value="{{ request('power_max') }}" placeholder="Ex: 100" step="0.1">
                </div>
                <div class="col-md-3">
                    <label for="current_min" class="form-label fw-semibold mb-1">Corrente Mín. (A):</label>
                    <input type="number" name="current_min" id="current_min" class="form-control" 
                           value="{{ request('current_min') }}" placeholder="Ex: 5" step="0.1">
                </div>
                <div class="col-md-3">
                    <label for="current_max" class="form-label fw-semibold mb-1">Corrente Máx. (A):</label>
                    <input type="number" name="current_max" id="current_max" class="form-control" 
                           value="{{ request('current_max') }}" placeholder="Ex: 200" step="0.1">
                </div>
                
                <!-- Botões de ação centralizados -->
                <div class="col-12 d-flex justify-content-center gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('motores.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Motores -->
    @if($motores->count() > 0)
        <div class="row g-3">
            @foreach($motores as $motor)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($motor->photo)
                            <div class="position-relative">
                                <img src="{{ Storage::url($motor->photo) }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;"
                                     alt="Foto do Motor">
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="bi bi-camera me-1"></i>
                                        Foto
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-gear-wide-connected fs-1 mb-2"></i>
                                    <p class="mb-0 small">Sem foto</p>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title mb-0 fw-bold">
                                    {{ strlen($motor->equipment) > 30 ? substr($motor->equipment, 0, 30) . '...' : $motor->equipment }}
                                </h6>
                                @if($motor->tag)
                                    <span class="badge bg-primary">{{ $motor->tag }}</span>
                                @endif
                            </div>
                            
                            @if($motor->manufacturer)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-building me-1"></i>
                                    {{ $motor->manufacturer }}
                                </p>
                            @endif
                            
                            @if($motor->equipment_type)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag me-1"></i>
                                    {{ $motor->equipment_type }}
                                </p>
                            @endif
                            
                            <div class="row g-2 mb-2">
                                @if($motor->power_kw || $motor->power_cv)
                                    <div class="col-12">
                                        <small class="text-muted">
                                            <i class="bi bi-lightning me-1"></i>
                                            {{ $motor->power_display }}
                                        </small>
                                    </div>
                                @endif
                                
                                @if($motor->rotation)
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="bi bi-arrow-clockwise me-1"></i>
                                            {{ $motor->rotation_display }}
                                        </small>
                                    </div>
                                @endif
                                
                                @if($motor->stock_reserve)
                                    <div class="col-6">
                                        <small class="badge bg-{{ $motor->stock_reserve == 'Sim' ? 'success' : ($motor->stock_reserve == 'Não' ? 'danger' : 'warning') }}">
                                            {{ $motor->stock_reserve }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                            
                            @if($motor->location)
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ strlen($motor->location) > 25 ? substr($motor->location, 0, 25) . '...' : $motor->location }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 p-3 pt-0">
                            <div class="d-flex gap-2">
                                <a href="{{ route('motores.show', $motor) }}" 
                                   class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="bi bi-eye me-1"></i>
                                    Ver
                                </a>
                                <a href="{{ route('motores.edit', $motor) }}" 
                                   class="btn btn-outline-secondary btn-sm flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($motores->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <!-- Informações da paginação -->
                            <div class="mb-3 mb-md-0 d-flex flex-column flex-sm-row align-items-start align-items-sm-center">
                                <small class="text-muted me-3 mb-2 mb-sm-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Mostrando {{ $motores->firstItem() }} a {{ $motores->lastItem() }} 
                                    de {{ $motores->total() }} resultados
                                    @if(request()->hasAny(['search', 'location', 'stock_reserve', 'power_min', 'power_max', 'current_min', 'current_max']))
                                        <span class="badge bg-light text-dark ms-1">
                                            <i class="bi bi-funnel me-1"></i>Filtrado
                                        </span>
                                    @endif
                                </small>
                                
                                <!-- Seletor de itens por página -->
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Itens por página:</small>
                                    <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                        <option value="6" {{ request('per_page', 12) == 6 ? 'selected' : '' }}>6</option>
                                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                        <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24</option>
                                        <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Links de paginação -->
                            <div class="d-flex justify-content-center">
                                {{ $motores->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Informações quando não há paginação -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Mostrando {{ $motores->count() }} de {{ $motores->total() }} resultados
                            @if(request()->hasAny(['search', 'location', 'stock_reserve', 'power_min', 'power_max', 'current_min', 'current_max']))
                                <span class="badge bg-light text-dark ms-1">
                                    <i class="bi bi-funnel me-1"></i>Filtrado
                                </span>
                            @endif
                            @if($motores->total() > 12)
                                <span class="badge bg-primary ms-1">
                                    {{ request('per_page', 12) }} por página
                                </span>
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-gear-wide-connected text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">Nenhum motor encontrado</h5>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'equipment_type', 'manufacturer', 'location', 'stock_reserve']))
                        Tente ajustar os filtros ou 
                        <a href="{{ route('motores.index') }}" class="text-decoration-none">limpar a busca</a>.
                    @else
                        Comece criando seu primeiro motor.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'equipment_type', 'manufacturer', 'location', 'stock_reserve']))
                    <a href="{{ route('motores.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>
                        Criar Primeiro Motor
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Função para alterar itens por página
    function changePerPage(perPage) {
        const url = new URL(window.location);
        url.searchParams.set('per_page', perPage);
        url.searchParams.delete('page'); // Reset para primeira página
        window.location.href = url.toString();
    }

    // Smooth scroll para o topo quando mudar de página
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination .page-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Scroll suave para o topo
                setTimeout(() => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }, 100);
            });
        });
    });
</script>
@endpush 