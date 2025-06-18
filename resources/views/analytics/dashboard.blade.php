@extends('layouts.app')

@section('title', 'Analytics - Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Cabeçalho com Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Analytics Dashboard
                    </h1>
                    <p class="text-muted mb-0">Análise de relatórios e equipamentos problemáticos</p>
                </div>
                
                <!-- Filtros de Data -->
                <div class="d-flex flex-column flex-md-row gap-2">
                    <form method="GET" action="{{ route('analytics.dashboard') }}" class="d-flex flex-column flex-md-row gap-2">
                        <div class="input-group" style="min-width: 200px;">
                            <span class="input-group-text">
                                <i class="bi bi-calendar-range"></i>
                            </span>
                            <input type="date" name="data_inicio" class="form-control" value="{{ $dataInicio->format('Y-m-d') }}">
                        </div>
                        <div class="input-group" style="min-width: 200px;">
                            <span class="input-group-text">até</span>
                            <input type="date" name="data_fim" class="form-control" value="{{ $dataFim->format('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('pdf.analytics', ['data_inicio' => $dataInicio->format('Y-m-d'), 'data_fim' => $dataFim->format('Y-m-d')]) }}" 
                           class="btn btn-outline-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf me-1"></i>
                            PDF (DomPDF)
                        </a>
                        <a href="{{ route('analytics.pdf.browsershot', ['data_inicio' => $dataInicio->format('Y-m-d'), 'data_fim' => $dataFim->format('Y-m-d')]) }}" 
                           class="btn btn-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                            PDF Premium
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas Resumo -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #007bff !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="bi bi-file-text fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ number_format($estatisticas['total_relatorios']) }}</h3>
                            <p class="text-muted mb-0 small">Total de Relatórios</p>
                            <small class="text-muted">{{ $estatisticas['media_diaria'] }} por dia</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #28a745 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ number_format($estatisticas['resolvidos']) }}</h3>
                            <p class="text-muted mb-0 small">Resolvidos</p>
                            <small class="text-muted">{{ $estatisticas['total_relatorios'] > 0 ? round(($estatisticas['resolvidos']/$estatisticas['total_relatorios'])*100, 1) : 0 }}% do total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #ffc107 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ number_format($estatisticas['pendentes']) }}</h3>
                            <p class="text-muted mb-0 small">Pendentes</p>
                            <small class="text-muted">{{ $estatisticas['total_relatorios'] > 0 ? round(($estatisticas['pendentes']/$estatisticas['total_relatorios'])*100, 1) : 0 }}% do total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #9b59b6 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-purple bg-opacity-10 text-purple rounded-circle p-3 me-3">
                            <i class="bi bi-tools fs-4"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 fw-bold">{{ number_format($estatisticas['equipamentos_afetados']) }}</h3>
                            <p class="text-muted mb-0 small">Equipamentos Afetados</p>
                            <small class="text-muted">{{ $estatisticas['locais_afetados'] }} locais afetados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Evolução Temporal -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-graph-up text-primary me-2"></i>
                        Evolução dos Relatórios por Mês
                    </h5>
                    <p class="text-muted small mb-0">Acompanhe a tendência de problemas ao longo do tempo</p>
                </div>
                <div class="card-body">
                    <canvas id="evolucaoChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Distribuição por Status -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-pie-chart text-success me-2"></i>
                        Status dos Relatórios
                    </h5>
                    <p class="text-muted small mb-0">Distribuição atual por status</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" width="250" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Equipamentos com Problemas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Top 10 Equipamentos com Mais Problemas
                    </h5>
                    <p class="text-muted small mb-0">Equipamentos que mais geram relatórios</p>
                </div>
                <div class="card-body">
                    @if($equipamentosProblemas->count() > 0)
                        <canvas id="equipamentosChart" height="120"></canvas>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted fs-2"></i>
                            <p class="text-muted mt-2">Nenhum dado encontrado para o período selecionado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Locais Afetados -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-geo-alt-fill text-info me-2"></i>
                        Top 10 Áreas Mais Afetadas
                    </h5>
                    <p class="text-muted small mb-0">Locais com maior incidência de problemas</p>
                </div>
                <div class="card-body">
                    @if($locaisAfetados->count() > 0)
                        <canvas id="locaisChart" height="120"></canvas>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted fs-2"></i>
                            <p class="text-muted mt-2">Nenhum dado encontrado para o período selecionado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Distribuição por Prioridade e Equipamentos Críticos -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-speedometer2 text-warning me-2"></i>
                        Distribuição por Prioridade
                    </h5>
                    <p class="text-muted small mb-0">Classificação dos relatórios por nível de prioridade</p>
                </div>
                <div class="card-body">
                    @if($distribuicaoPrioridade->count() > 0)
                        <canvas id="prioridadeChart" height="100"></canvas>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted fs-2"></i>
                            <p class="text-muted mt-2">Nenhum dado encontrado para o período selecionado</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-shield-exclamation text-danger me-2"></i>
                        Equipamentos Críticos
                    </h5>
                    <p class="text-muted small mb-0">Prioridade alta/crítica</p>
                </div>
                <div class="card-body">
                    @if($equipamentosCriticos->count() > 0)
                        @foreach($equipamentosCriticos as $equipamento)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <div class="fw-semibold">{{ $equipamento['equipamento'] }}</div>
                            </div>
                            <span class="badge bg-danger">{{ $equipamento['total'] }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle text-success fs-2"></i>
                            <p class="text-muted mt-2 mb-0">Nenhum equipamento crítico no período</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.stat-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.bg-purple {
    background-color: #9b59b6 !important;
}

.text-purple {
    color: #9b59b6 !important;
}

.bg-opacity-10 {
    background-color: rgba(var(--bs-bg-opacity-rgb), 0.1) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurações globais do Chart.js
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.plugins.legend.position = 'top';
    Chart.defaults.plugins.legend.align = 'start';

    // 1. Gráfico de Evolução Temporal
    @if($evolucaoMensal->count() > 0)
    const evolucaoCtx = document.getElementById('evolucaoChart').getContext('2d');
    new Chart(evolucaoCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($evolucaoMensal->pluck('mes_ano')) !!},
            datasets: [{
                label: 'Total',
                data: {!! json_encode($evolucaoMensal->pluck('total')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Resolvidos',
                data: {!! json_encode($evolucaoMensal->pluck('resolvidos')) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }, {
                label: 'Pendentes',
                data: {!! json_encode($evolucaoMensal->pluck('pendentes')) !!},
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
    @endif

    // 2. Gráfico de Status (Pizza)
    @if($distribuicaoStatus->count() > 0)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($distribuicaoStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($distribuicaoStatus->pluck('total')) !!},
                backgroundColor: {!! json_encode($distribuicaoStatus->pluck('color')) !!},
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
    @endif

    // 3. Gráfico de Equipamentos (Barra Horizontal)
    @if($equipamentosProblemas->count() > 0)
    const equipamentosCtx = document.getElementById('equipamentosChart').getContext('2d');
    new Chart(equipamentosCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($equipamentosProblemas->pluck('equipamento')->map(function($nome) { return strlen($nome) > 25 ? substr($nome, 0, 25) . '...' : $nome; })) !!},
            datasets: [{
                label: 'Problemas',
                data: {!! json_encode($equipamentosProblemas->pluck('total')) !!},
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: '#dc3545',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif

    // 4. Gráfico de Locais (Barra Horizontal)
    @if($locaisAfetados->count() > 0)
    const locaisCtx = document.getElementById('locaisChart').getContext('2d');
    new Chart(locaisCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($locaisAfetados->pluck('local')->map(function($nome) { return strlen($nome) > 25 ? substr($nome, 0, 25) . '...' : $nome; })) !!},
            datasets: [{
                label: 'Problemas',
                data: {!! json_encode($locaisAfetados->pluck('total')) !!},
                backgroundColor: 'rgba(23, 162, 184, 0.8)',
                borderColor: '#17a2b8',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif

    // 5. Gráfico de Prioridade (Barra)
    @if($distribuicaoPrioridade->count() > 0)
    const prioridadeCtx = document.getElementById('prioridadeChart').getContext('2d');
    new Chart(prioridadeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribuicaoPrioridade->pluck('prioridade')) !!},
            datasets: [{
                label: 'Relatórios',
                data: {!! json_encode($distribuicaoPrioridade->pluck('total')) !!},
                backgroundColor: {!! json_encode($distribuicaoPrioridade->pluck('color')) !!},
                borderWidth: 1,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif
});
</script>
@endsection 