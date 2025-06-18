@extends('pdf.layout')

@section('title', 'Relatórios em Lote')

@section('header-title', 'Relatórios em Lote')
@section('header-subtitle', 'Compilação de relatórios do período')

@section('content')
<div class="section no-break">
    <div class="section-title">Filtros Aplicados</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Período:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }}</div>
        </div>
        @if($request->status)
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</div>
        </div>
        @endif
        @if($request->prioridade)
        <div class="info-row">
            <div class="info-label">Prioridade:</div>
            <div class="info-value">{{ ucfirst($request->prioridade) }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Total de Relatórios:</div>
            <div class="info-value"><strong>{{ number_format($relatorios->count()) }}</strong></div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Resumo Estatístico</div>
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <span class="stats-number">{{ number_format($relatorios->count()) }}</span>
                <span class="stats-label">Total</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: red;">{{ number_format($relatorios->where('status', 'pendente')->count()) }}</span>
                <span class="stats-label">Pendentes</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: orange;">{{ number_format($relatorios->where('status', 'em_andamento')->count()) }}</span>
                <span class="stats-label">Em Andamento</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: green;">{{ number_format($relatorios->where('status', 'resolvido')->count()) }}</span>
                <span class="stats-label">Resolvidos</span>
            </div>
        </div>
    </div>
</div>

<div class="section page-break">
    <div class="section-title">Lista de Relatórios</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 12%;">Data</th>
                <th style="width: 25%;">Título</th>
                <th style="width: 15%;">Local</th>
                <th style="width: 15%;">Equipamento</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Prioridade</th>
                <th style="width: 5%;">Usuário</th>
            </tr>
        </thead>
        <tbody>
            @foreach($relatorios as $relatorio)
            <tr>
                <td class="text-center">#{{ $relatorio->id }}</td>
                <td>{{ $relatorio->data_ocorrencia->format('d/m/Y') }}</td>
                <td>{{ Str::limit($relatorio->titulo, 40) }}</td>
                <td>{{ $relatorio->local->nome ?? 'N/A' }}</td>
                <td>{{ $relatorio->equipamento->nome ?? 'N/A' }}</td>
                <td class="text-center">
                    <span class="status-badge status-{{ $relatorio->status }}">
                        {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                    </span>
                </td>
                <td class="text-center">
                    <span class="priority-badge priority-{{ $relatorio->prioridade }}">
                        {{ ucfirst($relatorio->prioridade) }}
                    </span>
                </td>
                <td>{{ $relatorio->usuario->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($relatorios as $index => $relatorio)
<div class="section page-break">
    <div class="section-title">Relatório #{{ $relatorio->id }} - Detalhes</div>
    
    <div class="info-grid mb-20">
        <div class="info-row">
            <div class="info-label">Título:</div>
            <div class="info-value"><strong>{{ $relatorio->titulo }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Data da Ocorrência:</div>
            <div class="info-value">{{ $relatorio->data_ocorrencia->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Local:</div>
            <div class="info-value">{{ $relatorio->local->nome ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Equipamento:</div>
            <div class="info-value">{{ $relatorio->equipamento->nome ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge status-{{ $relatorio->status }}">
                    {{ ucfirst(str_replace('_', ' ', $relatorio->status)) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Prioridade:</div>
            <div class="info-value">
                <span class="priority-badge priority-{{ $relatorio->prioridade }}">
                    {{ ucfirst($relatorio->prioridade) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Criado por:</div>
            <div class="info-value">{{ $relatorio->usuario->name ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="description-box">
        <h4>Descrição:</h4>
        <p>{{ $relatorio->descricao }}</p>
    </div>

    @if($relatorio->solucao)
    <div class="description-box">
        <h4>Solução:</h4>
        <p>{{ $relatorio->solucao }}</p>
    </div>
    @endif

    @if($relatorio->observacoes)
    <div class="description-box">
        <h4>Observações:</h4>
        <p>{{ $relatorio->observacoes }}</p>
    </div>
    @endif

    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Criado em:</div>
            <div class="info-value">{{ $relatorio->data_criacao ? $relatorio->data_criacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Atualizado em:</div>
            <div class="info-value">{{ $relatorio->data_atualizacao ? $relatorio->data_atualizacao->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
    </div>
</div>
@endforeach

<div class="section page-break">
    <div class="section-title">Análise do Lote</div>
    
    <div class="description-box">
        <h4>Distribuição por Status</h4>
        <ul>
            <li><strong>Pendentes:</strong> {{ $relatorios->where('status', 'pendente')->count() }} ({{ $relatorios->count() > 0 ? number_format(($relatorios->where('status', 'pendente')->count() / $relatorios->count()) * 100, 1) : 0 }}%)</li>
            <li><strong>Em Andamento:</strong> {{ $relatorios->where('status', 'em_andamento')->count() }} ({{ $relatorios->count() > 0 ? number_format(($relatorios->where('status', 'em_andamento')->count() / $relatorios->count()) * 100, 1) : 0 }}%)</li>
            <li><strong>Resolvidos:</strong> {{ $relatorios->where('status', 'resolvido')->count() }} ({{ $relatorios->count() > 0 ? number_format(($relatorios->where('status', 'resolvido')->count() / $relatorios->count()) * 100, 1) : 0 }}%)</li>
        </ul>
    </div>

    <div class="description-box">
        <h4>Distribuição por Prioridade</h4>
        <ul>
            <li><strong>Baixa:</strong> {{ $relatorios->where('prioridade', 'baixa')->count() }}</li>
            <li><strong>Média:</strong> {{ $relatorios->where('prioridade', 'media')->count() }}</li>
            <li><strong>Alta:</strong> {{ $relatorios->where('prioridade', 'alta')->count() }}</li>
            <li><strong>Crítica:</strong> {{ $relatorios->where('prioridade', 'critica')->count() }}</li>
        </ul>
    </div>

    @php
        $equipamentosAfetados = $relatorios->whereNotNull('equipamento_id')->groupBy('equipamento_id');
        $locaisAfetados = $relatorios->whereNotNull('local_id')->groupBy('local_id');
    @endphp

    <div class="description-box">
        <h4>Equipamentos Mais Afetados</h4>
        @if($equipamentosAfetados->count() > 0)
            <ul>
                @foreach($equipamentosAfetados->sortByDesc(function($group) { return $group->count(); })->take(5) as $equipamentoId => $group)
                    <li><strong>{{ $group->first()->equipamento->nome ?? 'Equipamento #' . $equipamentoId }}:</strong> {{ $group->count() }} problema(s)</li>
                @endforeach
            </ul>
        @else
            <p>Nenhum equipamento específico identificado.</p>
        @endif
    </div>

    <div class="description-box">
        <h4>Locais Mais Afetados</h4>
        @if($locaisAfetados->count() > 0)
            <ul>
                @foreach($locaisAfetados->sortByDesc(function($group) { return $group->count(); })->take(5) as $localId => $group)
                    <li><strong>{{ $group->first()->local->nome ?? 'Local #' . $localId }}:</strong> {{ $group->count() }} problema(s)</li>
                @endforeach
            </ul>
        @else
            <p>Nenhum local específico identificado.</p>
        @endif
    </div>
</div>
@endsection 