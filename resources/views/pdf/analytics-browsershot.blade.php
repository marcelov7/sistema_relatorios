<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Analytics - {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</title>
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
            padding: 40px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }

        .header h1 {
            font-size: 3em;
            margin-bottom: 15px;
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.3em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .header .period {
            font-size: 1.1em;
            margin-top: 10px;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 25px;
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        .section {
            margin-bottom: 40px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .section-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 20px 30px;
            font-size: 1.4em;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 15px;
            font-size: 1.3em;
        }

        .section-content {
            padding: 30px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
        }

        .stat-card.total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .stat-number {
            font-size: 3.5em;
            font-weight: 700;
            margin-bottom: 10px;
            display: block;
            position: relative;
            z-index: 1;
        }

        .stat-label {
            font-size: 1em;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #007bff;
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
            font-size: 1.2em;
            color: #212529;
            font-weight: 600;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .modern-table th {
            background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
            color: white;
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 0.5px;
        }

        .modern-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .modern-table tr:hover {
            background: #f8f9fa;
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .ranking-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9em;
        }

        .problem-count {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pendente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-em_andamento {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-resolvido {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .priority-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .priority-baixa {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .priority-media {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .priority-alta {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .priority-critica {
            background: #721c24;
            color: white;
            border: 1px solid #721c24;
        }

        .analysis-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            border-left: 5px solid #007bff;
        }

        .analysis-section h4 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.3em;
            display: flex;
            align-items: center;
        }

        .analysis-section h4::before {
            content: '💡';
            margin-right: 10px;
            font-size: 1.2em;
        }

        .analysis-section p {
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .analysis-section ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .analysis-section li {
            margin-bottom: 8px;
            color: #495057;
        }

        .performance-indicator {
            text-align: center;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
            color: white;
            font-size: 1.1em;
        }

        .performance-excellent {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .performance-good {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .performance-attention {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }

        .chart-placeholder {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            color: #6c757d;
            margin: 20px 0;
        }

        .footer {
            margin-top: 50px;
            padding: 30px;
            background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
            color: white;
            border-radius: 15px;
            text-align: center;
        }

        .footer h3 {
            margin-bottom: 15px;
            font-weight: 300;
        }

        .footer p {
            opacity: 0.9;
            margin-bottom: 5px;
        }

        @media print {
            body { background: white; }
            .container { box-shadow: none; }
            .section { break-inside: avoid; }
            .stat-card { break-inside: avoid; }
        }

        .percentage-bar {
            background: #e9ecef;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .percentage-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .percentage-fill.success { background: #28a745; }
        .percentage-fill.warning { background: #ffc107; }
        .percentage-fill.danger { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Relatório de Analytics</h1>
            <p>Análise Estatística Completa</p>
            <div class="period">
                📅 {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}
            </div>
        </div>

        <!-- Resumo Executivo -->
        <div class="section">
            <div class="section-header">
                <span>📈 Resumo Executivo</span>
            </div>
            <div class="section-content">
                <div class="stats-container">
                    <div class="stat-card total">
                        <span class="stat-number">{{ number_format($dados['estatisticas']['total_relatorios']) }}</span>
                        <span class="stat-label">Total de Relatórios</span>
                    </div>
                    <div class="stat-card success">
                        <span class="stat-number">{{ number_format($dados['estatisticas']['resolvidos']) }}</span>
                        <span class="stat-label">Resolvidos</span>
                    </div>
                    <div class="stat-card warning">
                        <span class="stat-number">{{ number_format($dados['estatisticas']['em_andamento']) }}</span>
                        <span class="stat-label">Em Andamento</span>
                    </div>
                    <div class="stat-card danger">
                        <span class="stat-number">{{ number_format($dados['estatisticas']['pendentes']) }}</span>
                        <span class="stat-label">Pendentes</span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Período Analisado</div>
                        <div class="info-value">{{ $dados['estatisticas']['periodo_dias'] }} dias</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Equipamentos Afetados</div>
                        <div class="info-value">{{ number_format($dados['estatisticas']['equipamentos_afetados']) }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Locais Afetados</div>
                        <div class="info-value">{{ number_format($dados['estatisticas']['locais_afetados']) }}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">Taxa de Resolução</div>
                        <div class="info-value">
                            @if($dados['estatisticas']['total_relatorios'] > 0)
                                {{ number_format(($dados['estatisticas']['resolvidos'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Equipamentos -->
        @if($dados['equipamentosProblemas']->count() > 0)
        <div class="section">
            <div class="section-header">
                <span>🔧 Top 10 - Equipamentos com Mais Problemas</span>
            </div>
            <div class="section-content">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Ranking</th>
                            <th style="width: 40%;">Equipamento</th>
                            <th style="width: 25%;">Código</th>
                            <th style="width: 25%;">Problemas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados['equipamentosProblemas'] as $index => $item)
                        <tr>
                            <td>
                                <div class="ranking-number">{{ $index + 1 }}</div>
                            </td>
                            <td><strong>{{ $item['equipamento'] }}</strong></td>
                            <td><code>{{ $item['codigo'] }}</code></td>
                            <td>
                                <span class="problem-count">{{ number_format($item['total']) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Top Locais -->
        @if($dados['locaisAfetados']->count() > 0)
        <div class="section">
            <div class="section-header">
                <span>📍 Top 10 - Locais Mais Afetados</span>
            </div>
            <div class="section-content">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Ranking</th>
                            <th style="width: 40%;">Local</th>
                            <th style="width: 25%;">Endereço</th>
                            <th style="width: 25%;">Problemas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados['locaisAfetados'] as $index => $item)
                        <tr>
                            <td>
                                <div class="ranking-number">{{ $index + 1 }}</div>
                            </td>
                            <td><strong>{{ $item['local'] }}</strong></td>
                            <td>{{ $item['endereco'] }}</td>
                            <td>
                                <span class="problem-count">{{ number_format($item['total']) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Distribuição por Status -->
        @if($dados['distribuicaoStatus']->count() > 0)
        <div class="section">
            <div class="section-header">
                <span>📊 Distribuição por Status</span>
            </div>
            <div class="section-content">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Status</th>
                            <th style="width: 20%;">Quantidade</th>
                            <th style="width: 20%;">Percentual</th>
                            <th style="width: 20%;">Progresso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados['distribuicaoStatus'] as $item)
                        <tr>
                            <td>
                                @if($item['status'] === 'Pendente')
                                    <span class="status-badge status-pendente">{{ $item['status'] }}</span>
                                @elseif($item['status'] === 'Em Andamento')
                                    <span class="status-badge status-em_andamento">{{ $item['status'] }}</span>
                                @else
                                    <span class="status-badge status-resolvido">{{ $item['status'] }}</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($item['total']) }}</strong></td>
                            <td><strong>{{ number_format(($item['total'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%</strong></td>
                            <td>
                                <div class="percentage-bar">
                                    <div class="percentage-fill 
                                        @if($item['status'] === 'Pendente') danger
                                        @elseif($item['status'] === 'Em Andamento') warning
                                        @else success @endif"
                                        style="width: {{ ($item['total'] / $dados['estatisticas']['total_relatorios']) * 100 }}%">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Distribuição por Prioridade -->
        @if($dados['distribuicaoPrioridade']->count() > 0)
        <div class="section">
            <div class="section-header">
                <span>⚡ Distribuição por Prioridade</span>
            </div>
            <div class="section-content">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Prioridade</th>
                            <th style="width: 20%;">Quantidade</th>
                            <th style="width: 20%;">Percentual</th>
                            <th style="width: 20%;">Progresso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dados['distribuicaoPrioridade'] as $item)
                        <tr>
                            <td>
                                @if($item['prioridade'] === 'Baixa')
                                    <span class="priority-badge priority-baixa">{{ $item['prioridade'] }}</span>
                                @elseif($item['prioridade'] === 'Média')
                                    <span class="priority-badge priority-media">{{ $item['prioridade'] }}</span>
                                @elseif($item['prioridade'] === 'Alta')
                                    <span class="priority-badge priority-alta">{{ $item['prioridade'] }}</span>
                                @else
                                    <span class="priority-badge priority-critica">{{ $item['prioridade'] }}</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($item['total']) }}</strong></td>
                            <td><strong>{{ number_format(($item['total'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%</strong></td>
                            <td>
                                <div class="percentage-bar">
                                    <div class="percentage-fill 
                                        @if($item['prioridade'] === 'Baixa') success
                                        @elseif($item['prioridade'] === 'Média') warning
                                        @else danger @endif"
                                        style="width: {{ ($item['total'] / $dados['estatisticas']['total_relatorios']) * 100 }}%">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Análise e Recomendações -->
        <div class="section">
            <div class="section-header">
                <span>🎯 Análise e Recomendações</span>
            </div>
            <div class="section-content">
                <div class="analysis-section">
                    <h4>Equipamentos Críticos</h4>
                    @if($dados['equipamentosProblemas']->count() > 0)
                        <p>Os equipamentos que mais apresentaram problemas no período foram:</p>
                        <ul>
                            @foreach($dados['equipamentosProblemas']->take(3) as $item)
                            <li><strong>{{ $item['equipamento'] }}</strong> ({{ $item['codigo'] }}) - {{ $item['total'] }} problema(s)</li>
                            @endforeach
                        </ul>
                        <p><strong>Recomendação:</strong> Implementar manutenção preventiva intensiva nestes equipamentos para reduzir a incidência de problemas.</p>
                    @else
                        <p>✅ Nenhum equipamento apresentou problemas significativos no período analisado.</p>
                    @endif
                </div>

                <div class="analysis-section">
                    <h4>Locais de Atenção</h4>
                    @if($dados['locaisAfetados']->count() > 0)
                        <p>Os locais com maior concentração de problemas foram:</p>
                        <ul>
                            @foreach($dados['locaisAfetados']->take(3) as $item)
                            <li><strong>{{ $item['local'] }}</strong> - {{ $item['total'] }} problema(s)</li>
                            @endforeach
                        </ul>
                        <p><strong>Recomendação:</strong> Realizar auditoria das condições ambientais e operacionais destes locais.</p>
                    @else
                        <p>✅ A distribuição de problemas por local está equilibrada no período analisado.</p>
                    @endif
                </div>

                @php
                    $taxaResolucao = $dados['estatisticas']['total_relatorios'] > 0 
                        ? ($dados['estatisticas']['resolvidos'] / $dados['estatisticas']['total_relatorios']) * 100 
                        : 0;
                @endphp

                <div class="performance-indicator 
                    @if($taxaResolucao >= 80) performance-excellent
                    @elseif($taxaResolucao >= 60) performance-good
                    @else performance-attention @endif">
                    
                    <h3>
                        @if($taxaResolucao >= 80) 🏆 Performance Excelente
                        @elseif($taxaResolucao >= 60) 👍 Performance Boa
                        @else ⚠️ Requer Atenção @endif
                    </h3>
                    
                    <p>
                        Taxa de Resolução: <strong>{{ number_format($taxaResolucao, 1) }}%</strong>
                        ({{ number_format($dados['estatisticas']['resolvidos']) }} de {{ number_format($dados['estatisticas']['total_relatorios']) }} relatórios)
                    </p>
                    
                    @if($taxaResolucao >= 80)
                        <p>A equipe está demonstrando excelente eficiência na resolução de problemas. Continue com as práticas atuais!</p>
                    @elseif($taxaResolucao >= 60)
                        <p>Boa performance geral, mas há espaço para melhorias nos processos de resolução.</p>
                    @else
                        <p>A taxa de resolução precisa de atenção imediata. Recomenda-se revisar os processos e recursos disponíveis.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <h3>📋 Sistema de Relatórios - Laravel</h3>
            <p><strong>Relatório gerado em:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p><strong>Período analisado:</strong> {{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</p>
            <p><strong>Base de dados:</strong> {{ number_format($dados['estatisticas']['total_relatorios']) }} relatórios</p>
        </div>
    </div>
</body>
</html> 