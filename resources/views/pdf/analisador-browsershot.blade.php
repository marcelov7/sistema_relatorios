<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisador {{ $analisador->analyzer }} #{{ $analisador->id }}</title>
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
            max-width: 800px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
        }
        
        .header-content {
            position: relative;
            z-index: 1;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .meta-info {
            background: #e9ecef;
            padding: 15px 30px;
            border-bottom: 3px solid #667eea;
        }
        
        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #6c757d;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 2px solid #667eea;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
            margin: 0;
        }
        
        .section-body {
            padding: 20px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-ativo {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inativo {
            background: #f8d7da;
            color: #721c24;
        }
        
        .component-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .component-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid transparent;
        }
        
        .component-ok {
            border-left-color: #28a745;
            background: #d4edda;
        }
        
        .component-problema {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        
        .component-name {
            font-weight: 500;
            color: #495057;
        }
        
        .component-status {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-ok {
            color: #155724;
        }
        
        .status-problema {
            color: #721c24;
        }
        
        .measurement-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            margin-bottom: 10px;
        }
        
        .measurement-value {
            font-size: 20px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 5px;
        }
        
        .measurement-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .measurement-status {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 10px;
        }
        
        .status-normal {
            background: #d4edda;
            color: #155724;
        }
        
        .status-atencao {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-critico {
            background: #f8d7da;
            color: #721c24;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            text-align: center;
            padding: 20px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-top: 4px solid #667eea;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            display: block;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-geral {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .status-geral h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .status-geral p {
            font-size: 16px;
            color: #6c757d;
        }
        
        .observacoes {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            font-style: italic;
            color: #856404;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        
        @media print {
            body { background: white; }
            .container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <h1>{{ $analisador->analyzer }} #{{ $analisador->id }}</h1>
                <p>Relatório de Inspeção de Analisador</p>
            </div>
        </div>
        
        <div class="meta-info">
            <div class="meta-row">
                <span>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</span>
                <span>Sistema de Relatórios - Laravel</span>
            </div>
        </div>
        
        <div class="content">
            <!-- Informações Básicas -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Informações do Analisador</h2>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID do Analisador</div>
                            <div class="info-value">#{{ $analisador->id }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tipo de Analisador</div>
                            <div class="info-value">{{ $analisador->analyzer }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Data da Verificação</div>
                            <div class="info-value">{{ $analisador->check_date->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Responsável</div>
                            <div class="info-value">{{ $analisador->usuario->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge {{ $analisador->ativo ? 'status-ativo' : 'status-inativo' }}">
                                    {{ $analisador->ativo ? 'ATIVO' : 'INATIVO' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status dos Componentes -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Status dos Componentes</h2>
                </div>
                <div class="section-body">
                    @php
                        $componentes = [
                            'acid_filter' => 'Filtro Ácido',
                            'gas_dryer' => 'Secador de Gás', 
                            'paper_filter' => 'Filtro de Papel',
                            'peristaltic_pump' => 'Bomba Peristáltica',
                            'rotameter' => 'Rotâmetro',
                            'disposable_filter' => 'Filtro Descartável',
                            'blocking_filter' => 'Filtro de Bloqueio'
                        ];
                    @endphp
                    
                    <div class="component-grid">
                        @foreach($componentes as $campo => $nome)
                        <div class="component-item {{ $analisador->$campo ? 'component-ok' : 'component-problema' }}">
                            <span class="component-name">{{ $nome }}</span>
                            <span class="component-status {{ $analisador->$campo ? 'status-ok' : 'status-problema' }}">
                                {{ $analisador->$campo ? '✓ OK' : '✗ PROBLEMA' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Medições Ambientais -->
            @if($analisador->room_temperature || $analisador->air_pressure)
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Medições Ambientais</h2>
                </div>
                <div class="section-body">
                    @if($analisador->room_temperature)
                    <div class="measurement-item">
                        <div class="measurement-value">{{ number_format($analisador->room_temperature, 1) }}°C</div>
                        <div class="measurement-label">Temperatura do Ambiente</div>
                        @php $temp = $analisador->room_temperature; @endphp
                        <span class="measurement-status 
                            @if($temp >= 15 && $temp <= 30) status-normal
                            @elseif(($temp >= 10 && $temp < 15) || ($temp > 30 && $temp <= 35)) status-atencao
                            @else status-critico @endif">
                            @if($temp >= 15 && $temp <= 30) NORMAL
                            @elseif(($temp >= 10 && $temp < 15) || ($temp > 30 && $temp <= 35)) ATENÇÃO
                            @else CRÍTICO @endif
                        </span>
                    </div>
                    @endif
                    
                    @if($analisador->air_pressure)
                    <div class="measurement-item">
                        <div class="measurement-value">{{ number_format($analisador->air_pressure, 2) }} bar</div>
                        <div class="measurement-label">Pressão do Ar</div>
                        @php $pressure = $analisador->air_pressure; @endphp
                        <span class="measurement-status 
                            @if($pressure >= 2.0 && $pressure <= 4.0) status-normal
                            @elseif(($pressure >= 1.5 && $pressure < 2.0) || ($pressure > 4.0 && $pressure <= 5.0)) status-atencao
                            @else status-critico @endif">
                            @if($pressure >= 2.0 && $pressure <= 4.0) NORMAL
                            @elseif(($pressure >= 1.5 && $pressure < 2.0) || ($pressure > 4.0 && $pressure <= 5.0)) ATENÇÃO
                            @else CRÍTICO @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Estatísticas -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Avaliação Geral</h2>
                </div>
                <div class="section-body">
                    @php
                        $funcionando = 0;
                        $problemas = 0;
                        foreach($componentes as $campo => $nome) {
                            if($analisador->$campo) {
                                $funcionando++;
                            } else {
                                $problemas++;
                            }
                        }
                        $total = count($componentes);
                        $eficiencia = ($funcionando / $total) * 100;
                    @endphp
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-number" style="color: #28a745;">{{ $funcionando }}</span>
                            <span class="stat-label">Funcionando</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number" style="color: #dc3545;">{{ $problemas }}</span>
                            <span class="stat-label">Com Problema</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number" style="color: #6c757d;">{{ $total }}</span>
                            <span class="stat-label">Total</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number" style="color: #007bff;">{{ number_format($eficiencia, 1) }}%</span>
                            <span class="stat-label">Eficiência</span>
                        </div>
                    </div>
                    
                    @php
                        $statusGeral = 'CRÍTICO';
                        $corStatus = '#dc3545';
                        
                        if ($eficiencia == 100) {
                            $statusGeral = 'TODOS OK';
                            $corStatus = '#28a745';
                        } elseif ($eficiencia >= 80) {
                            $statusGeral = 'BOM';
                            $corStatus = '#28a745';
                        } elseif ($eficiencia >= 60) {
                            $statusGeral = 'ATENÇÃO';
                            $corStatus = '#ffc107';
                        }
                    @endphp
                    
                    <div class="status-geral">
                        <h2 style="color: {{ $corStatus }};">{{ $statusGeral }}</h2>
                        <p>{{ $funcionando }} de {{ $total }} componentes funcionando ({{ number_format($eficiencia, 1) }}% de eficiência)</p>
                    </div>
                </div>
            </div>
            
            <!-- Observações -->
            @if($analisador->observation)
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Observações</h2>
                </div>
                <div class="section-body">
                    <div class="observacoes">
                        {{ $analisador->observation }}
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Informações do Sistema -->
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title">Informações do Sistema</h2>
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Registrado em</div>
                            <div class="info-value">{{ $analisador->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Última atualização</div>
                            <div class="info-value">{{ $analisador->updated_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tenant ID</div>
                            <div class="info-value">#{{ $analisador->tenant_id }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Usuário ID</div>
                            <div class="info-value">#{{ $analisador->user_id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Sistema de Relatórios - Laravel | Gerado automaticamente em {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html> 