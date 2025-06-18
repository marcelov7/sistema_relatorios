<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspeção de Gerador #{{ $inspecao->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            font-weight: 300;
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .section {
            margin-bottom: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .section-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 15px 25px;
            font-size: 1.3em;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .section-content {
            padding: 25px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1em;
            color: #212529;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 10px;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .measurements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .measurement-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .measurement-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }

        .measurement-value {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }

        .measurement-label {
            font-size: 0.9em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .measurement-status {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            z-index: 2;
        }

        .status-normal { background: #28a745; }
        .status-warning { background: #ffc107; }
        .status-critical { background: #dc3545; }

        .voltage-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .voltage-table th,
        .voltage-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .voltage-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }

        .voltage-table tr:hover {
            background: #f8f9fa;
        }

        .voltage-value {
            font-size: 1.2em;
            font-weight: 700;
            color: #007bff;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 20px 0;
        }

        .stat-card {
            text-align: center;
            padding: 25px;
            border-radius: 10px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stat-card.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        .stat-number {
            font-size: 3em;
            font-weight: 700;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        .status-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
        }

        .status-summary h2 {
            font-size: 2.5em;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .status-summary p {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .observations {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #007bff;
            margin: 20px 0;
        }

        .observations h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.2em;
        }

        .observations p {
            line-height: 1.8;
            color: #6c757d;
        }

        .footer {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9em;
        }

        @media print {
            body { background: white; }
            .container { box-shadow: none; }
            .section { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inspeção de Gerador #{{ $inspecao->id }}</h1>
            <p>Relatório Técnico Detalhado</p>
            <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <!-- Informações Básicas -->
        <div class="section">
            <div class="section-header">
                <span>📋 Informações da Inspeção</span>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">ID da Inspeção</div>
                        <div class="info-value">#{{ $inspecao->id }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Data da Inspeção</div>
                        <div class="info-value">{{ $inspecao->data ? $inspecao->data->format('d/m/Y') : 'N/A' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Colaborador</div>
                        <div class="info-value">{{ $inspecao->colaborador ?? 'N/A' }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Status do Sistema</div>
                        <div class="info-value">
                            @if($inspecao->ativo)
                                <span class="status-badge status-success">✓ ATIVO</span>
                            @else
                                <span class="status-badge status-danger">✗ INATIVO</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Níveis -->
        <div class="section">
            <div class="section-header">
                <span>📊 Níveis do Sistema</span>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Nível de Óleo</div>
                        <div class="info-value">
                            <strong>{{ $inspecao->nivel_oleo ?? 'N/A' }}</strong>
                            @if($inspecao->nivel_oleo === 'Baixo')
                                <span class="status-badge status-danger">⚠️ CRÍTICO</span>
                            @elseif($inspecao->nivel_oleo === 'Máximo')
                                <span class="status-badge status-success">✓ ÓTIMO</span>
                            @elseif($inspecao->nivel_oleo === 'Normal')
                                <span class="status-badge status-success">✓ NORMAL</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Nível de Água</div>
                        <div class="info-value">
                            <strong>{{ $inspecao->nivel_agua ?? 'N/A' }}</strong>
                            @if($inspecao->nivel_agua === 'Baixo')
                                <span class="status-badge status-danger">⚠️ CRÍTICO</span>
                            @elseif($inspecao->nivel_agua === 'Máximo')
                                <span class="status-badge status-success">✓ ÓTIMO</span>
                            @elseif($inspecao->nivel_agua === 'Normal')
                                <span class="status-badge status-success">✓ NORMAL</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tensões -->
        @if($inspecao->tensao_sync_gerador || $inspecao->tensao_sync_rede || $inspecao->tensao_a || $inspecao->tensao_b || $inspecao->tensao_c || $inspecao->tensao_bateria || $inspecao->tensao_alternador)
        <div class="section">
            <div class="section-header">
                <span>⚡ Tensões Elétricas</span>
            </div>
            <div class="section-content">
                <table class="voltage-table">
                    <thead>
                        <tr>
                            <th>Parâmetro</th>
                            <th>Valor Medido</th>
                            <th>Status</th>
                            <th>Faixa Normal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($inspecao->tensao_sync_gerador)
                        <tr>
                            <td><strong>Tensão Sync Gerador</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_sync_gerador, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_sync_gerador; @endphp
                                @if($tensao >= 11 && $tensao <= 13)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>11,0V - 13,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_sync_rede)
                        <tr>
                            <td><strong>Tensão Sync Rede</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_sync_rede, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_sync_rede; @endphp
                                @if($tensao >= 11 && $tensao <= 13)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>11,0V - 13,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_a)
                        <tr>
                            <td><strong>Tensão Fase A</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_a, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_a; @endphp
                                @if($tensao >= 11 && $tensao <= 13)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>11,0V - 13,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_b)
                        <tr>
                            <td><strong>Tensão Fase B</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_b, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_b; @endphp
                                @if($tensao >= 11 && $tensao <= 13)
                                    <span class="status-badge status-success">NORMAL</span>
                                @elseif($tensao >= 1 && $tensao < 11)
                                    <span class="status-badge status-danger">BAIXA</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>11,0V - 13,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_c)
                        <tr>
                            <td><strong>Tensão Fase C</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_c, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_c; @endphp
                                @if($tensao >= 11 && $tensao <= 13)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>11,0V - 13,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_bateria)
                        <tr>
                            <td><strong>Tensão da Bateria</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_bateria, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_bateria; @endphp
                                @if($tensao >= 20 && $tensao <= 24)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>20,0V - 24,0V</td>
                        </tr>
                        @endif
                        
                        @if($inspecao->tensao_alternador)
                        <tr>
                            <td><strong>Tensão do Alternador</strong></td>
                            <td><span class="voltage-value">{{ number_format($inspecao->tensao_alternador, 2) }}V</span></td>
                            <td>
                                @php $tensao = $inspecao->tensao_alternador; @endphp
                                @if($tensao >= 100 && $tensao <= 120)
                                    <span class="status-badge status-success">NORMAL</span>
                                @else
                                    <span class="status-badge status-warning">VERIFICAR</span>
                                @endif
                            </td>
                            <td>100V - 120V</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Medições -->
        @if($inspecao->temp_agua || $inspecao->pressao_oleo || $inspecao->frequencia || $inspecao->rpm)
        <div class="section">
            <div class="section-header">
                <span>🌡️ Medições Operacionais</span>
            </div>
            <div class="section-content">
                <div class="measurements-grid">
                    @if($inspecao->temp_agua)
                    <div class="measurement-card">
                        @php $temp = $inspecao->temp_agua; @endphp
                        <div class="measurement-status 
                            @if($temp >= 80 && $temp <= 95) status-normal
                            @elseif($temp > 95) status-critical
                            @else status-warning @endif">
                        </div>
                        <div class="measurement-value">{{ number_format($inspecao->temp_agua, 1) }}°C</div>
                        <div class="measurement-label">Temperatura da Água</div>
                    </div>
                    @endif
                    
                    @if($inspecao->pressao_oleo)
                    <div class="measurement-card">
                        @php $pressao = $inspecao->pressao_oleo; @endphp
                        <div class="measurement-status 
                            @if($pressao >= 3 && $pressao <= 6) status-normal
                            @elseif($pressao < 3) status-critical
                            @else status-warning @endif">
                        </div>
                        <div class="measurement-value">{{ number_format($inspecao->pressao_oleo, 1) }}</div>
                        <div class="measurement-label">Pressão do Óleo (bar)</div>
                    </div>
                    @endif
                    
                    @if($inspecao->frequencia)
                    <div class="measurement-card">
                        @php $freq = $inspecao->frequencia; @endphp
                        <div class="measurement-status 
                            @if($freq >= 59 && $freq <= 61) status-normal
                            @else status-warning @endif">
                        </div>
                        <div class="measurement-value">{{ number_format($inspecao->frequencia, 1) }}</div>
                        <div class="measurement-label">Frequência (Hz)</div>
                    </div>
                    @endif
                    
                    @if($inspecao->rpm)
                    <div class="measurement-card">
                        @php $rpm = $inspecao->rpm; @endphp
                        <div class="measurement-status 
                            @if($rpm >= 1750 && $rpm <= 1850) status-normal
                            @else status-warning @endif">
                        </div>
                        <div class="measurement-value">{{ number_format($inspecao->rpm) }}</div>
                        <div class="measurement-label">Rotação (RPM)</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Status Operacional -->
        <div class="section">
            <div class="section-header">
                <span>🔧 Status Operacional</span>
            </div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Nível de Combustível</div>
                        <div class="info-value">
                            @if($inspecao->combustivel_50 === 'Sim')
                                <span class="status-badge status-success">✓ ACIMA DE 50%</span>
                            @else
                                <span class="status-badge status-danger">⚠️ ABAIXO DE 50%</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Iluminação da Sala</div>
                        <div class="info-value">
                            @if($inspecao->iluminacao_sala === 'Normal')
                                <span class="status-badge status-success">✓ FUNCIONANDO</span>
                            @else
                                <span class="status-badge status-danger">⚠️ COM PROBLEMAS</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo Estatístico -->
        @php
            $problemas = 0;
            $atencoes = 0;
            $normais = 0;
            $total = 0;
            
            // Verificar níveis
            if($inspecao->nivel_oleo) {
                $total++;
                if($inspecao->nivel_oleo === 'Baixo') $problemas++;
                else $normais++;
            }
            
            if($inspecao->nivel_agua) {
                $total++;
                if($inspecao->nivel_agua === 'Baixo') $problemas++;
                else $normais++;
            }
            
            // Verificar combustível
            if($inspecao->combustivel_50) {
                $total++;
                if($inspecao->combustivel_50 === 'Não') $problemas++;
                else $normais++;
            }
            
            // Verificar iluminação
            if($inspecao->iluminacao_sala) {
                $total++;
                if($inspecao->iluminacao_sala === 'Anormal') $problemas++;
                else $normais++;
            }
            
            // Verificar tensões (contagem básica)
            $tensoes = collect([
                $inspecao->tensao_sync_gerador,
                $inspecao->tensao_sync_rede,
                $inspecao->tensao_a,
                $inspecao->tensao_b,
                $inspecao->tensao_c,
                $inspecao->tensao_bateria,
                $inspecao->tensao_alternador
            ])->filter()->count();
            
            $total += $tensoes;
            $normais += $tensoes;
            
            // Verificar medições
            $medicoes = collect([
                $inspecao->temp_agua,
                $inspecao->pressao_oleo,
                $inspecao->frequencia,
                $inspecao->rpm
            ])->filter()->count();
            
            $total += $medicoes;
            $normais += $medicoes;
        @endphp

        <div class="section">
            <div class="section-header">
                <span>📈 Resumo da Inspeção</span>
            </div>
            <div class="section-content">
                <div class="stats-container">
                    <div class="stat-card success">
                        <span class="stat-number">{{ $normais }}</span>
                        <span class="stat-label">Itens Normais</span>
                    </div>
                    <div class="stat-card warning">
                        <span class="stat-number">{{ $atencoes }}</span>
                        <span class="stat-label">Requer Atenção</span>
                    </div>
                    <div class="stat-card danger">
                        <span class="stat-number">{{ $problemas }}</span>
                        <span class="stat-label">Problemas</span>
                    </div>
                    <div class="stat-card info">
                        <span class="stat-number">{{ $total }}</span>
                        <span class="stat-label">Total Verificado</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Geral -->
        @php
            $statusGeral = 'CRÍTICO';
            $corStatus = '#dc3545';
            $iconeStatus = '🔴';
            
            if($problemas == 0) {
                $statusGeral = 'OPERACIONAL';
                $corStatus = '#28a745';
                $iconeStatus = '🟢';
            } elseif($problemas <= 2) {
                $statusGeral = 'ATENÇÃO REQUERIDA';
                $corStatus = '#ffc107';
                $iconeStatus = '🟡';
            }
        @endphp

        <div class="status-summary" style="background: linear-gradient(135deg, {{ $corStatus }} 0%, {{ $corStatus }}dd 100%);">
            <h2>{{ $iconeStatus }} {{ $statusGeral }}</h2>
            @if($problemas > 0)
                <p>{{ $problemas }} problema(s) crítico(s) identificado(s) - Intervenção necessária</p>
            @else
                <p>Todos os sistemas operando dentro dos parâmetros normais</p>
            @endif
        </div>

        <!-- Observações -->
        @if($inspecao->observacao)
        <div class="observations">
            <h3>📝 Observações Técnicas</h3>
            <p>{{ $inspecao->observacao }}</p>
        </div>
        @endif

        <!-- Rodapé -->
        <div class="footer">
            <p><strong>Sistema de Relatórios - Laravel</strong></p>
            <p>Inspeção registrada em: {{ $inspecao->criado_em ? $inspecao->criado_em->format('d/m/Y H:i:s') : 'N/A' }}</p>
            @if($inspecao->atualizado_em && $inspecao->atualizado_em != $inspecao->criado_em)
                <p>Última atualização: {{ $inspecao->atualizado_em->format('d/m/Y H:i:s') }}</p>
            @endif
        </div>
    </div>
</body>
</html> 