<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório #{{ $relatorio->id }} - {{ $relatorio->titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 16px;
            color: #495057;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #333;
        }
        
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status.pendente { background: #fff3cd; color: #856404; }
        .status.em_andamento { background: #cce5ff; color: #004085; }
        .status.resolvido { background: #d4edda; color: #155724; }
        .status.cancelado { background: #f8d7da; color: #721c24; }
        
        .description-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px 12px;
            text-align: left;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .images-section {
            page-break-before: auto;
        }
        
        .image-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            background: #fafafa;
            page-break-inside: avoid;
        }
        
        .image-header {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .image-info {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .image-info .label {
            font-weight: bold;
            color: #666;
        }
        
        .image-container {
            text-align: center;
            margin: 15px 0;
        }
        
        .image-container img {
            max-width: 100%;
            max-height: 300px;
            width: auto;
            height: auto;
            border: 2px solid #28a745;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .image-caption {
            margin-top: 8px;
            font-size: 10px;
            color: #28a745;
            font-weight: bold;
        }
        
        .status-box {
            padding: 12px;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }
        
        .status-box.success {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
        }
        
        .status-box.warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
        }
        
        .status-box.error {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #721c24;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .page-break { page-break-before: always; }
            .no-break { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>Relatório #{{ $relatorio->id }}</h1>
            <div class="subtitle">{{ $relatorio->titulo }}</div>
        </div>

        <!-- Informações Básicas -->
        <div class="section">
            <div class="section-title">Informações Básicas</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status {{ $relatorio->status }}">{{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Prioridade</div>
                    <div class="info-value">{{ ucfirst($relatorio->prioridade) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Responsável</div>
                    <div class="info-value">{{ $relatorio->usuario->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Local</div>
                    <div class="info-value">{{ $relatorio->local->nome ?? 'N/A' }}</div>
                </div>
                @if($relatorio->equipamento)
                <div class="info-item">
                    <div class="info-label">Equipamento</div>
                    <div class="info-value">{{ $relatorio->equipamento->nome }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Modelo</div>
                    <div class="info-value">{{ $relatorio->equipamento->modelo ?? 'N/A' }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Descrição -->
        <div class="section">
            <div class="section-title">Descrição do Problema</div>
            <div class="description-box">
                {{ $relatorio->descricao }}
            </div>
        </div>

        <!-- Observações -->
        @if($relatorio->observacoes)
        <div class="section">
            <div class="section-title">Observações</div>
            <div class="description-box">
                {{ $relatorio->observacoes }}
            </div>
        </div>
        @endif

        <!-- Histórico -->
        @if($relatorio->historicos && $relatorio->historicos->count() > 0)
        <div class="section">
            <div class="section-title">Histórico de Alterações</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relatorio->historicos as $historico)
                    <tr>
                        <td>{{ $historico->data_atualizacao ? $historico->data_atualizacao->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td>{{ $historico->usuario->name ?? 'Sistema' }}</td>
                        <td>
                            @if($historico->status_anterior && $historico->status_novo)
                                Status: {{ ucfirst(str_replace('_', ' ', $historico->status_anterior)) }} → {{ ucfirst(str_replace('_', ' ', $historico->status_novo)) }}
                            @else
                                Atualização
                            @endif
                        </td>
                        <td>{{ $historico->descricao ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Imagens -->
        @if($relatorio->imagens && $relatorio->imagens->count() > 0)
        <div class="section images-section">
            <div class="section-title">Imagens Anexadas ({{ $relatorio->imagens->count() }})</div>
            
            @foreach($relatorio->imagens as $index => $imagem)
            <div class="image-item no-break">
                <div class="image-header">📷 Imagem {{ $index + 1 }}: {{ $imagem->nome_original ?: $imagem->nome_arquivo }}</div>
                
                @php
                    $caminhoCompleto = storage_path('app/public/' . $imagem->caminho_arquivo);
                    $imagemExiste = file_exists($caminhoCompleto);
                    $tamanhoArquivo = $imagemExiste ? filesize($caminhoCompleto) : 0;
                    $tamanhoKB = number_format($tamanhoArquivo / 1024, 2);
                @endphp
                
                <div class="image-info">
                    <div class="label">Adicionada em:</div>
                    <div>{{ $imagem->data_upload ? $imagem->data_upload->format('d/m/Y H:i') : 'N/A' }}</div>
                    @if($imagem->descricao)
                    <div class="label">Descrição:</div>
                    <div>{{ $imagem->descricao }}</div>
                    @endif
                </div>
                
                @if(!$imagemExiste)
                    <div class="status-box error">
                        ❌ Arquivo não encontrado
                    </div>
                @elseif(!$imagem->isImagem())
                    <div class="status-box warning">
                        📎 Arquivo anexado
                    </div>
                @elseif($tamanhoArquivo > 2 * 1024 * 1024)
                    <div class="status-box warning">
                        ⚠️ Imagem muito grande
                    </div>
                @else
                    <div class="status-box success">
                        ✅ Imagem incluída
                    </div>
                    <div class="image-container">
                        <img src="{{ $caminhoCompleto }}" alt="Imagem {{ $index + 1 }}">
                        <div class="image-caption">{{ $imagem->nome_original ?: $imagem->nome_arquivo }}</div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Informações do Sistema -->
        <div class="section">
            <div class="section-title">Informações do Sistema</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Criado em</div>
                    <div class="info-value">{{ $relatorio->data_criacao ? $relatorio->data_criacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Última atualização</div>
                    <div class="info-value">{{ $relatorio->data_atualizacao ? $relatorio->data_atualizacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tempo total</div>
                    <div class="info-value">
                        @if($relatorio->status === 'resolvido' && $relatorio->data_criacao && $relatorio->data_atualizacao)
                            @php
                                $tempoTotal = (int) $relatorio->data_criacao->diffInDays($relatorio->data_atualizacao);
                                $tempoHoras = $relatorio->data_criacao->diffInHours($relatorio->data_atualizacao) % 24;
                                $tempoMinutos = $relatorio->data_criacao->diffInMinutes($relatorio->data_atualizacao) % 60;
                            @endphp
                            @if($tempoTotal > 0)
                                {{ $tempoTotal }} dia(s)
                                @if($tempoHoras > 0) {{ $tempoHoras }} hora(s) @endif
                            @elseif($tempoHoras > 0)
                                {{ $tempoHoras }} hora(s)
                                @if($tempoMinutos > 0) {{ $tempoMinutos }} minuto(s) @endif
                            @else
                                {{ $tempoMinutos }} minuto(s)
                            @endif
                        @elseif($relatorio->status !== 'resolvido')
                            Em aberto há {{ $relatorio->data_criacao ? $relatorio->data_criacao->diffForHumans() : 'N/A' }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>
                @if($relatorio->progresso)
                <div class="info-item">
                    <div class="info-label">Progresso</div>
                    <div class="info-value">{{ $relatorio->progresso }}%</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Rodapé -->
        <div class="footer">
            <p>Sistema de Relatórios - Laravel</p>
            <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html> 