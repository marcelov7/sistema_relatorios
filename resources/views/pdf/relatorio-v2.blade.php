<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório V2 - {{ $relatorio->titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 12px;
            opacity: 0.9;
        }

        .header .v2-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-top: 10px;
        }

        /* Layout */
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-6 {
            width: 50%;
            padding: 0 10px;
        }

        .col-12 {
            width: 100%;
            padding: 0 10px;
        }

        /* Cards */
        .info-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 12px;
            color: #495057;
        }

        .card-body {
            padding: 15px;
        }

        /* Informações básicas */
        .info-row {
            display: flex;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f3f4;
        }

        .info-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            width: 40%;
            color: #495057;
        }

        .info-value {
            width: 60%;
            color: #212529;
        }

        /* Status badges */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pendente { background: #fff3cd; color: #856404; }
        .badge-em-andamento { background: #d1ecf1; color: #0c5460; }
        .badge-resolvido { background: #d4edda; color: #155724; }
        .badge-baixa { background: #d4edda; color: #155724; }
        .badge-media { background: #fff3cd; color: #856404; }
        .badge-alta { background: #f8d7da; color: #721c24; }
        .badge-critica { background: #f5c6cb; color: #721c24; }
        .badge-concluido { background: #d4edda; color: #155724; }

        /* Progress bar */
        .progress {
            background: #e9ecef;
            border-radius: 10px;
            height: 16px;
            overflow: hidden;
        }

        .progress-bar {
            background: #198754;
            height: 100%;
            color: white;
            text-align: center;
            line-height: 16px;
            font-size: 10px;
            font-weight: bold;
        }

        /* Equipamentos */
        .equipment-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .equipment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .equipment-number {
            background: #198754;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }

        .equipment-title {
            flex: 1;
            margin-left: 10px;
        }

        .equipment-title h4 {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
            color: #212529;
        }

        .equipment-title .code {
            font-size: 10px;
            color: #6c757d;
            margin-top: 2px;
        }

        .equipment-content {
            margin-top: 10px;
        }

        .equipment-section {
            margin-bottom: 10px;
        }

        .equipment-section:last-child {
            margin-bottom: 0;
        }

        .equipment-section-title {
            font-weight: bold;
            font-size: 11px;
            color: #495057;
            margin-bottom: 4px;
        }

        .equipment-section-content {
            font-size: 10px;
            color: #212529;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #198754;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }

        /* Quebra de página */
        .page-break {
            page-break-before: always;
        }

        /* Responsivo para PDF */
        @media print {
            .info-card {
                break-inside: avoid;
            }
            
            .equipment-item {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $relatorio->titulo }}</h1>
            <div class="subtitle">Relatório Multi-Equipamento</div>
            <div class="v2-badge">Sistema V2</div>
        </div>

        <div class="row">
            <!-- Informações Gerais -->
            <div class="col-6">
                <div class="info-card">
                    <div class="card-header">
                        📋 Informações Gerais
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Título:</div>
                            <div class="info-value">{{ $relatorio->titulo }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Local:</div>
                            <div class="info-value">{{ $relatorio->local->nome ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Data:</div>
                            <div class="info-value">{{ $relatorio->data_ocorrencia->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status:</div>
                            <div class="info-value">
                                <span class="badge badge-{{ $relatorio->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Prioridade:</div>
                            <div class="info-value">
                                <span class="badge badge-{{ $relatorio->prioridade }}">
                                    {{ ucfirst($relatorio->prioridade) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Progresso:</div>
                            <div class="info-value">
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $relatorio->progresso }}%">
                                        {{ $relatorio->progresso }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Criado por:</div>
                            <div class="info-value">{{ $relatorio->usuario->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Data Criação:</div>
                            <div class="info-value">{{ $relatorio->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumo da Atividade -->
            <div class="col-6">
                <div class="info-card">
                    <div class="card-header">
                        📝 Descrição da Atividade
                    </div>
                    <div class="card-body">
                        <div style="text-align: justify;">
                            {{ $relatorio->descricao }}
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="card-header">
                        📊 Resumo dos Equipamentos
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Total de Equipamentos:</div>
                            <div class="info-value">{{ $itens->count() }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pendentes:</div>
                            <div class="info-value">{{ $itens->where('status_item', 'pendente')->count() }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Em Andamento:</div>
                            <div class="info-value">{{ $itens->where('status_item', 'em_andamento')->count() }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Concluídos:</div>
                            <div class="info-value">{{ $itens->where('status_item', 'concluido')->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipamentos Detalhados -->
        @if($itens->count() > 0)
            <div class="info-card">
                <div class="card-header">
                    ⚙️ Equipamentos e Atividades Detalhadas
                </div>
                <div class="card-body">
                    @foreach($itens as $index => $item)
                        <div class="equipment-item">
                            <div class="equipment-header">
                                <div style="display: flex; align-items: center;">
                                    <div class="equipment-number">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="equipment-title">
                                        <h4>{{ $item->equipamento_nome }}</h4>
                                        @if($item->equipamento_codigo)
                                            <div class="code">Código: {{ $item->equipamento_codigo }}</div>
                                        @endif
                                    </div>
                                </div>
                                <span class="badge badge-{{ $item->status_item }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status_item)) }}
                                </span>
                            </div>

                            <div class="equipment-content">
                                <div class="equipment-section">
                                    <div class="equipment-section-title">🔧 Descrição da Atividade:</div>
                                    <div class="equipment-section-content">
                                        {{ $item->descricao_equipamento }}
                                    </div>
                                </div>

                                @if($item->observacoes)
                                    <div class="equipment-section">
                                        <div class="equipment-section-title">💬 Observações:</div>
                                        <div class="equipment-section-content">
                                            {{ $item->observacoes }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($index < $itens->count() - 1 && ($index + 1) % 4 === 0)
                            <div class="page-break"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Imagens -->
        @if($relatorio->imagens->count() > 0)
            <div class="info-card">
                <div class="card-header">
                    🖼️ Imagens do Relatório
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                        @foreach($relatorio->imagens as $imagem)
                            <div style="text-align: center;">
                                <img src="{{ public_path('storage/' . $imagem->caminho) }}" 
                                     alt="{{ $imagem->nome_original }}"
                                     style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #dee2e6;">
                                <div style="margin-top: 5px; font-size: 9px; color: #6c757d;">
                                    {{ $imagem->nome_original }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>
                Relatório gerado em {{ now()->format('d/m/Y H:i') }}
            </div>
            <div>
                Sistema de Relatórios V2 - Multi-Equipamento
            </div>
        </div>
    </div>
</body>
</html> 