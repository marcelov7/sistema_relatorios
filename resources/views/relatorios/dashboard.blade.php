@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Relatórios')

@push('styles')
<style>
    .dashboard-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .stats-section {
        margin-bottom: 3rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-left: 4px solid;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.relatorios { border-left-color: #e74c3c; }
    .stat-card.motores { border-left-color: #f39c12; }
    .stat-card.analisadores { border-left-color: #3498db; }
    .stat-card.inspecoes { border-left-color: #2ecc71; }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1;
    }

    .stat-card.relatorios .stat-number { color: #e74c3c; }
    .stat-card.motores .stat-number { color: #f39c12; }
    .stat-card.analisadores .stat-number { color: #3498db; }
    .stat-card.inspecoes .stat-number { color: #2ecc71; }

    .stat-label {
        font-size: 1rem;
        color: #666;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .activities-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .activities-header {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .activity-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        align-items: center;
        transition: background 0.3s ease;
    }

    .activity-item:hover {
        background: #f8f9fa;
    }

    .activity-arrow {
        margin-left: auto;
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }

    .activity-item:hover .activity-arrow {
        opacity: 1;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .activity-icon.relatorio { background: #e74c3c; color: white; }
    .activity-icon.motor { background: #f39c12; color: white; }
    .activity-icon.analisador { background: #3498db; color: white; }
    .activity-icon.inspecao { background: #2ecc71; color: white; }
    .activity-icon.equipamento { background: #9b59b6; color: white; }
    .activity-icon.local { background: #34495e; color: white; }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        margin: 0;
        color: #333;
    }

    .activity-meta {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .module-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .module-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .module-icon.relatorio { background: linear-gradient(135deg, #e74c3c, #c0392b); }
    .module-icon.motor { background: linear-gradient(135deg, #f39c12, #e67e22); }
    .module-icon.analisador { background: linear-gradient(135deg, #3498db, #2980b9); }
    .module-icon.inspecao { background: linear-gradient(135deg, #2ecc71, #27ae60); }
    .module-icon.equipamento { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
    .module-icon.local { background: linear-gradient(135deg, #34495e, #2c3e50); }

    .module-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.75rem;
    }

    .module-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .module-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .module-actions .btn {
        flex: 1;
        max-width: 100px;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="mb-0">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard do Sistema
            </h1>
            <p class="mb-0 mt-2">Visão geral de todos os módulos do sistema</p>
        </div>

        <!-- Estatísticas Resumidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card relatorios text-center">
                    <div class="stat-number">{{ $statsRelatorios['total'] }}</div>
                    <div class="stat-label">Relatórios</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card motores text-center">
                    <div class="stat-number">{{ $statsMotores['total'] }}</div>
                    <div class="stat-label">Motores</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card analisadores text-center">
                    <div class="stat-number">{{ $statsAnalisadores['total'] }}</div>
                    <div class="stat-label">Analisadores</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card inspecoes text-center">
                    <div class="stat-number">{{ $statsInspecoes['total'] }}</div>
                    <div class="stat-label">Inspeções</div>
                </div>
            </div>
        </div>

        <!-- Módulos do Sistema com Botões -->
        <div class="row">
            <!-- Relatórios -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon relatorio">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <h5 class="module-title">Relatórios</h5>
                    <p class="module-description">Crie, edite e gerencie relatórios de ocorrências do sistema.</p>
                    <div class="module-actions">
                        <a href="{{ route('relatorios.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
                        <a href="{{ route('relatorios.create') }}" class="btn btn-success btn-sm">Criar Novo</a>
                    </div>
                </div>
            </div>

            <!-- Motores -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon motor">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h5 class="module-title">Motores</h5>
                    <p class="module-description">Sistema de gerenciamento de motores.</p>
                    <div class="module-actions">
                        <a href="{{ route('motores.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
                        <a href="{{ route('motores.create') }}" class="btn btn-success btn-sm">Criar Novo</a>
                    </div>
                </div>
            </div>

            <!-- Analisadores -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon analisador">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h5 class="module-title">Analisadores</h5>
                    <p class="module-description">Inspecionar analisadores para coleta de dados em tempo real.</p>
                    <div class="module-actions">
                        <a href="{{ route('analisadores.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
                        <a href="{{ route('analisadores.create') }}" class="btn btn-success btn-sm">Criar Novo</a>
                    </div>
                </div>
            </div>

            <!-- Inspeções de Gerador -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon inspecao">
                        <i class="bi bi-lightning"></i>
                    </div>
                    <h5 class="module-title">Inspeções de Gerador</h5>
                    <p class="module-description">Gerencie inspeções de gerador com controle de níveis e medições.</p>
                    <div class="module-actions">
                        <a href="{{ route('inspecoes-gerador.index') }}" class="btn btn-primary btn-sm">Ver Todas</a>
                        <a href="{{ route('inspecoes-gerador.create') }}" class="btn btn-success btn-sm">Criar Nova</a>
                    </div>
                </div>
            </div>

            <!-- Equipamentos -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon equipamento">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h5 class="module-title">Equipamentos</h5>
                    <p class="module-description">Cadastre e gerencie equipamentos para manutenção e inspeção.</p>
                    <div class="module-actions">
                        <a href="{{ route('equipamentos.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
                        <a href="{{ route('equipamentos.create') }}" class="btn btn-success btn-sm">Criar Novo</a>
                    </div>
                </div>
            </div>

            <!-- Locais -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="module-card text-center">
                    <div class="module-icon local">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5 class="module-title">Locais</h5>
                    <p class="module-description">Gerencie locais de instalação e armazenamento de equipamentos.</p>
                    <div class="module-actions">
                        <a href="{{ route('locais.index') }}" class="btn btn-primary btn-sm">Ver Todos</a>
                        <a href="{{ route('locais.create') }}" class="btn btn-success btn-sm">Criar Novo</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Atividades das Últimas 24h -->
        <div class="activities-card">
            <div class="activities-header">
                <h2 class="section-title mb-0">
                    <i class="bi bi-graph-up text-primary"></i>
                    Analytics Avançadas
                </h2>
            </div>
            
            <div class="activity-item" onclick="window.location.href='{{ route('analytics.dashboard') }}'" style="cursor: pointer;">
                <div class="activity-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Dashboard de Análises e Gráficos</div>
                    <div class="activity-meta">
                        <strong>Visualize</strong> equipamentos com mais problemas, áreas afetadas, evolução temporal
                        <span class="badge bg-primary ms-2">Filtros por Data</span>
                        <span class="badge bg-success ms-1">Gráficos Interativos</span>
                    </div>
                </div>
                <div class="activity-arrow">
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>

            <div class="activity-item" onclick="window.location.href='{{ route('analytics.dashboard') }}'" style="cursor: pointer;">
                <div class="activity-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <i class="bi bi-pie-chart"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Análise de Equipamentos Problemáticos</div>
                    <div class="activity-meta">
                        <strong>Identifique</strong> quais equipamentos geram mais relatórios de problemas
                        <span class="badge bg-warning ms-2">Top 10</span>
                        <span class="badge bg-info ms-1">Por Período</span>
                    </div>
                </div>
                <div class="activity-arrow">
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>

            <div class="activity-item" onclick="window.location.href='{{ route('analytics.dashboard') }}'" style="cursor: pointer;">
                <div class="activity-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Mapeamento de Áreas Críticas</div>
                    <div class="activity-meta">
                        <strong>Monitore</strong> quais locais têm maior incidência de problemas
                        <span class="badge bg-danger ms-2">Áreas Críticas</span>
                        <span class="badge bg-secondary ms-1">Tendências</span>
                    </div>
                </div>
                <div class="activity-arrow">
                    <i class="bi bi-chevron-right text-muted"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 