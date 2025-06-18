@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Relatórios')

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(111, 66, 193, 0.1);
    }

    .module-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 280px;
    }

    .module-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), #8b5cf6);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .module-card:hover::before {
        transform: scaleX(1);
    }

    .module-card:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        transform: translateY(-8px);
        border-color: var(--primary-color);
        text-decoration: none;
        color: inherit;
    }

    .module-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.75rem;
        color: white;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .module-icon::after {
        content: '';
        position: absolute;
        inset: 0;
        background: inherit;
        opacity: 0.1;
        border-radius: 50%;
    }

    .module-card.analisadores .module-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .module-card.relatorios .module-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .module-card.gerador .module-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .module-card.equipamentos .module-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .module-card.motores .module-icon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .module-card.checklists .module-icon {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .module-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #2d3748;
        flex-shrink: 0;
    }

    .module-description {
        color: #718096;
        font-size: 0.85rem;
        line-height: 1.4;
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .stats-overview {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #718096;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .welcome-message {
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-message::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .module-card {
            padding: 1.25rem;
            margin-bottom: 1rem;
            min-height: 240px;
        }

        .module-icon {
            width: 55px;
            height: 55px;
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
        }

        .module-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .module-description {
            font-size: 0.8rem;
            line-height: 1.3;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .welcome-message {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .module-card {
            padding: 1rem;
            min-height: 220px;
        }

        .module-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .module-title {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .module-description {
            font-size: 0.75rem;
            line-height: 1.2;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Welcome Message -->
    <div class="welcome-message">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">Bem-vindo, {{ auth()->user()->name }}! 👋</h2>
                <p class="mb-0 opacity-75">
                    Acesse rapidamente os módulos do sistema e gerencie seus relatórios de forma eficiente.
                </p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-speedometer2" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-overview">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Relatórios</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Equipamentos</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Check Lists</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">24</div>
                    <div class="stat-label">Análises</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module Cards -->
    <div class="row g-4">
                 <!-- Analisadores -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('analisadores.index') }}" class="module-card analisadores">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-graph-up"></i>
                     </div>
                     <h3 class="module-title">Analisadores</h3>
                 </div>
                 <p class="module-description">
                     Inspecionar analisadores para coleta de dados em tempo real.
                 </p>
             </a>
         </div>

                 <!-- Relatórios -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('relatorios.index') }}" class="module-card relatorios">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-file-earmark-text"></i>
                     </div>
                     <h3 class="module-title">Relatórios</h3>
                 </div>
                 <p class="module-description">
                     Crie, edite e gerencie relatórios de ocorrências do sistema.
                 </p>
             </a>
         </div>

                 <!-- Inspeções de Gerador -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('inspecoes-gerador.index') }}" class="module-card gerador">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-lightning-charge"></i>
                     </div>
                     <h3 class="module-title">Inspeções de Gerador</h3>
                 </div>
                 <p class="module-description">
                     Gerencie inspeções de gerador com controle de níveis e medições.
                 </p>
             </a>
         </div>

         <!-- Equipamentos -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('equipamentos.index') }}" class="module-card equipamentos">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-gear-wide-connected"></i>
                     </div>
                     <h3 class="module-title">Equipamentos</h3>
                 </div>
                 <p class="module-description">
                     Cadastre e gerencie equipamentos com controle de manutenção.
                 </p>
             </a>
         </div>

         <!-- Motores -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('motores.index') }}" class="module-card motores">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-cpu"></i>
                     </div>
                     <h3 class="module-title">Motores</h3>
                 </div>
                 <p class="module-description">
                     Monitore motores com análise de vibração e temperatura.
                 </p>
             </a>
         </div>

         <!-- Locais -->
         <div class="col-6 col-md-6 col-lg-4">
             <a href="{{ route('locais.index') }}" class="module-card checklists">
                 <div>
                     <div class="module-icon">
                         <i class="bi bi-geo-alt"></i>
                     </div>
                     <h3 class="module-title">Locais</h3>
                 </div>
                 <p class="module-description">
                     Gerencie locais e suas informações de endereço e contato.
                 </p>
             </a>
         </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="hero-section mt-5">
        <div class="row">
            <div class="col-md-8">
                <h4 class="mb-3">
                    <i class="bi bi-clock-history me-2 text-primary"></i>
                    Atividades Recentes
                </h4>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-file-earmark-text text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Relatório de Performance gerado</div>
                                <small class="text-muted">Há 2 horas</small>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--success-color); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-circle text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Checklist de manutenção concluído</div>
                                <small class="text-muted">Há 4 horas</small>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3" style="width: 40px; height: 40px; border-radius: 50%; background: var(--warning-color); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-triangle text-white"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">Alerta de temperatura do Motor #3</div>
                                <small class="text-muted">Há 6 horas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block text-center">
                <div class="mt-4">
                    <i class="bi bi-activity" style="font-size: 6rem; color: var(--primary-color); opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate cards on load
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.module-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });

    // Animate stats numbers
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent);
            const increment = target / 30;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                stat.textContent = Math.floor(current);
            }, 50);
        });
    }

    // Start animations
    window.addEventListener('load', () => {
        setTimeout(animateStats, 500);
    });
</script>
@endpush
