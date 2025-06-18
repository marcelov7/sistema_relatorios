@extends('pdf.layout')

@section('title', 'Relatório Analytics')

@section('header-title', 'Relatório de Analytics')
@section('header-subtitle', 'Análise estatística do período de ' . $dataInicio->format('d/m/Y') . ' a ' . $dataFim->format('d/m/Y'))

@section('content')
<div class="section no-break">
    <div class="section-title">Resumo Executivo</div>
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <span class="stats-number">{{ number_format($dados['estatisticas']['total_relatorios']) }}</span>
                <span class="stats-label">Total Relatórios</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: green;">{{ number_format($dados['estatisticas']['resolvidos']) }}</span>
                <span class="stats-label">Resolvidos</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: orange;">{{ number_format($dados['estatisticas']['em_andamento']) }}</span>
                <span class="stats-label">Em Andamento</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: red;">{{ number_format($dados['estatisticas']['pendentes']) }}</span>
                <span class="stats-label">Pendentes</span>
            </div>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Período Analisado:</div>
            <div class="info-value">{{ $dados['estatisticas']['periodo_dias'] }} dias</div>
        </div>
        <div class="info-row">
            <div class="info-label">Equipamentos Afetados:</div>
            <div class="info-value">{{ number_format($dados['estatisticas']['equipamentos_afetados']) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Locais Afetados:</div>
            <div class="info-value">{{ number_format($dados['estatisticas']['locais_afetados']) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Taxa de Resolução:</div>
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

@if($dados['equipamentosProblemas']->count() > 0)
<div class="section page-break">
    <div class="section-title">Top 10 - Equipamentos com Mais Problemas</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%;">Posição</th>
                <th style="width: 40%;">Equipamento</th>
                <th style="width: 25%;">Código</th>
                <th style="width: 25%;">Total de Problemas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados['equipamentosProblemas'] as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}º</td>
                <td>{{ $item['equipamento'] }}</td>
                <td>{{ $item['codigo'] }}</td>
                <td class="text-center">
                    <strong style="color: #dc3545;">{{ number_format($item['total']) }}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($dados['locaisAfetados']->count() > 0)
<div class="section">
    <div class="section-title">Top 10 - Locais Mais Afetados</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%;">Posição</th>
                <th style="width: 40%;">Local</th>
                <th style="width: 25%;">Endereço</th>
                <th style="width: 25%;">Total de Problemas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dados['locaisAfetados'] as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}º</td>
                <td>{{ $item['local'] }}</td>
                <td>{{ $item['endereco'] }}</td>
                <td class="text-center">
                    <strong style="color: #dc3545;">{{ number_format($item['total']) }}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($dados['distribuicaoStatus']->count() > 0)
<div class="section page-break">
    <div class="section-title">Distribuição por Status</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 50%;">Status</th>
                <th style="width: 25%;">Quantidade</th>
                <th style="width: 25%;">Percentual</th>
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
                <td class="text-center">{{ number_format($item['total']) }}</td>
                <td class="text-center">
                    {{ number_format(($item['total'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if($dados['distribuicaoPrioridade']->count() > 0)
<div class="section">
    <div class="section-title">Distribuição por Prioridade</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 50%;">Prioridade</th>
                <th style="width: 25%;">Quantidade</th>
                <th style="width: 25%;">Percentual</th>
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
                <td class="text-center">{{ number_format($item['total']) }}</td>
                <td class="text-center">
                    {{ number_format(($item['total'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div class="section page-break">
    <div class="section-title">Análise e Recomendações</div>
    
    <div class="description-box">
        <h4>Equipamentos Críticos</h4>
        @if($dados['equipamentosProblemas']->count() > 0)
            <p>Os equipamentos que mais apresentaram problemas no período foram:</p>
            <ul>
                @foreach($dados['equipamentosProblemas']->take(3) as $item)
                <li><strong>{{ $item['equipamento'] }}</strong> ({{ $item['codigo'] }}) - {{ $item['total'] }} problema(s)</li>
                @endforeach
            </ul>
            <p>Recomenda-se uma revisão preventiva destes equipamentos para reduzir a incidência de problemas.</p>
        @else
            <p>Nenhum equipamento apresentou problemas significativos no período analisado.</p>
        @endif
    </div>

    <div class="description-box">
        <h4>Locais de Atenção</h4>
        @if($dados['locaisAfetados']->count() > 0)
            <p>Os locais com maior concentração de problemas foram:</p>
            <ul>
                @foreach($dados['locaisAfetados']->take(3) as $item)
                <li><strong>{{ $item['local'] }}</strong> - {{ $item['total'] }} problema(s)</li>
                @endforeach
            </ul>
            <p>Sugere-se uma análise das condições ambientais e operacionais destes locais.</p>
        @else
            <p>A distribuição de problemas por local está equilibrada no período analisado.</p>
        @endif
    </div>

    <div class="description-box">
        <h4>Performance Geral</h4>
        <p>
            No período de {{ $dados['estatisticas']['periodo_dias'] }} dias foram registrados 
            {{ number_format($dados['estatisticas']['total_relatorios']) }} relatórios, com uma taxa de resolução de 
            @if($dados['estatisticas']['total_relatorios'] > 0)
                {{ number_format(($dados['estatisticas']['resolvidos'] / $dados['estatisticas']['total_relatorios']) * 100, 1) }}%.
            @else
                0%.
            @endif
        </p>
        
        @php
            $taxaResolucao = $dados['estatisticas']['total_relatorios'] > 0 
                ? ($dados['estatisticas']['resolvidos'] / $dados['estatisticas']['total_relatorios']) * 100 
                : 0;
        @endphp
        
        @if($taxaResolucao >= 80)
            <p style="color: green;"><strong>Excelente:</strong> A taxa de resolução está acima de 80%, indicando boa eficiência na resolução de problemas.</p>
        @elseif($taxaResolucao >= 60)
            <p style="color: orange;"><strong>Boa:</strong> A taxa de resolução está entre 60-80%, há espaço para melhorias.</p>
        @else
            <p style="color: red;"><strong>Atenção:</strong> A taxa de resolução está abaixo de 60%, recomenda-se revisar os processos.</p>
        @endif
    </div>
</div>

<div class="section">
    <div class="section-title">Informações do Relatório</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Período:</div>
            <div class="info-value">{{ $dataInicio->format('d/m/Y') }} a {{ $dataFim->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Gerado em:</div>
            <div class="info-value">{{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dados baseados em:</div>
            <div class="info-value">{{ number_format($dados['estatisticas']['total_relatorios']) }} relatórios</div>
        </div>
    </div>
</div>
@endsection 