@extends('pdf.layout')

@section('title', 'Inspeções em Lote')

@section('header-title', 'Inspeções em Lote')
@section('header-subtitle', 'Compilação de inspeções do período')

@section('content')
<div class="section no-break">
    <div class="section-title">Filtros Aplicados</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Período:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }}</div>
        </div>
        @if($request->colaborador)
        <div class="info-row">
            <div class="info-label">Colaborador:</div>
            <div class="info-value">{{ $request->colaborador }}</div>
        </div>
        @endif
        <div class="info-row">
            <div class="info-label">Total de Inspeções:</div>
            <div class="info-value"><strong>{{ number_format($inspecoes->count()) }}</strong></div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Resumo Estatístico</div>
    @php
        $totalItens = $inspecoes->count() * 10; // 10 itens por inspeção
        $itensOk = 0;
        $itensAtencao = 0;
        $itensProblema = 0;
        
        foreach($inspecoes as $inspecao) {
            $itens = [
                $inspecao->nivel_oleo_motor,
                $inspecao->vazamentos,
                $inspecao->arrefecimento,
                $inspecao->combustivel,
                $inspecao->bateria,
                $inspecao->alternador,
                $inspecao->correias,
                $inspecao->mangueiras,
                $inspecao->filtros,
                $inspecao->painel_controle
            ];
            
            foreach($itens as $item) {
                if($item === 'ok') $itensOk++;
                elseif($item === 'atencao') $itensAtencao++;
                elseif($item === 'problema') $itensProblema++;
            }
        }
    @endphp
    
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <span class="stats-number">{{ number_format($inspecoes->count()) }}</span>
                <span class="stats-label">Inspeções</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: green;">{{ number_format($itensOk) }}</span>
                <span class="stats-label">Itens OK</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: orange;">{{ number_format($itensAtencao) }}</span>
                <span class="stats-label">Atenção</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: red;">{{ number_format($itensProblema) }}</span>
                <span class="stats-label">Problemas</span>
            </div>
        </div>
    </div>
</div>

<div class="section page-break">
    <div class="section-title">Lista de Inspeções</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 15%;">Data</th>
                <th style="width: 25%;">Colaborador</th>
                <th style="width: 15%;">Horário</th>
                <th style="width: 10%;">OK</th>
                <th style="width: 10%;">Atenção</th>
                <th style="width: 10%;">Problemas</th>
                <th style="width: 5%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspecoes as $inspecao)
            @php
                $itens = [
                    $inspecao->nivel_oleo_motor,
                    $inspecao->vazamentos,
                    $inspecao->arrefecimento,
                    $inspecao->combustivel,
                    $inspecao->bateria,
                    $inspecao->alternador,
                    $inspecao->correias,
                    $inspecao->mangueiras,
                    $inspecao->filtros,
                    $inspecao->painel_controle
                ];
                
                $ok = collect($itens)->filter(fn($item) => $item === 'ok')->count();
                $atencao = collect($itens)->filter(fn($item) => $item === 'atencao')->count();
                $problema = collect($itens)->filter(fn($item) => $item === 'problema')->count();
                
                $status = 'ok';
                if($problema > 0) $status = 'problema';
                elseif($atencao > 0) $status = 'atencao';
            @endphp
            <tr>
                <td class="text-center">#{{ $inspecao->id }}</td>
                <td>{{ $inspecao->data ? \Carbon\Carbon::parse($inspecao->data)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $inspecao->colaborador ?? 'N/A' }}</td>
                <td>{{ $inspecao->horario ?? 'N/A' }}</td>
                <td class="text-center" style="color: green;"><strong>{{ $ok }}</strong></td>
                <td class="text-center" style="color: orange;"><strong>{{ $atencao }}</strong></td>
                <td class="text-center" style="color: red;"><strong>{{ $problema }}</strong></td>
                <td class="text-center">
                    @if($status === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($status === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($inspecoes as $index => $inspecao)
<div class="section page-break">
    <div class="section-title">Inspeção #{{ $inspecao->id }} - Detalhes</div>
    
    <div class="info-grid mb-20">
        <div class="info-row">
            <div class="info-label">Data:</div>
            <div class="info-value">{{ $inspecao->data ? \Carbon\Carbon::parse($inspecao->data)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Colaborador:</div>
            <div class="info-value">{{ $inspecao->colaborador }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Horário:</div>
            <div class="info-value">{{ $inspecao->horario ?? 'N/A' }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 45%;">Observações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nível de Óleo do Motor</td>
                <td class="text-center">
                    @if($inspecao->nivel_oleo_motor === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->nivel_oleo_motor === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_nivel_oleo_motor ?? '-' }}</td>
            </tr>
            <tr>
                <td>Vazamentos</td>
                <td class="text-center">
                    @if($inspecao->vazamentos === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->vazamentos === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_vazamentos ?? '-' }}</td>
            </tr>
            <tr>
                <td>Arrefecimento</td>
                <td class="text-center">
                    @if($inspecao->arrefecimento === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->arrefecimento === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_arrefecimento ?? '-' }}</td>
            </tr>
            <tr>
                <td>Combustível</td>
                <td class="text-center">
                    @if($inspecao->combustivel === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->combustivel === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_combustivel ?? '-' }}</td>
            </tr>
            <tr>
                <td>Bateria</td>
                <td class="text-center">
                    @if($inspecao->bateria === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->bateria === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_bateria ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alternador</td>
                <td class="text-center">
                    @if($inspecao->alternador === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->alternador === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_alternador ?? '-' }}</td>
            </tr>
            <tr>
                <td>Correias</td>
                <td class="text-center">
                    @if($inspecao->correias === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->correias === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_correias ?? '-' }}</td>
            </tr>
            <tr>
                <td>Mangueiras</td>
                <td class="text-center">
                    @if($inspecao->mangueiras === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->mangueiras === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_mangueiras ?? '-' }}</td>
            </tr>
            <tr>
                <td>Filtros</td>
                <td class="text-center">
                    @if($inspecao->filtros === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->filtros === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_filtros ?? '-' }}</td>
            </tr>
            <tr>
                <td>Painel de Controle</td>
                <td class="text-center">
                    @if($inspecao->painel_controle === 'ok')
                        <span style="color: green;">OK</span>
                    @elseif($inspecao->painel_controle === 'atencao')
                        <span style="color: orange;">ATENCAO</span>
                    @else
                        <span style="color: red;">PROBLEMA</span>
                    @endif
                </td>
                <td>{{ $inspecao->obs_painel_controle ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    @if($inspecao->observacoes_gerais)
    <div class="description-box">
        <h4>Observações Gerais:</h4>
        <p>{{ $inspecao->observacoes_gerais }}</p>
    </div>
    @endif
</div>
@endforeach

<div class="section page-break">
    <div class="section-title">Análise do Lote</div>
    
    <div class="description-box">
        <h4>Resumo Geral</h4>
        <p>
            No período analisado foram realizadas <strong>{{ number_format($inspecoes->count()) }}</strong> inspeções, 
            totalizando <strong>{{ number_format($totalItens) }}</strong> itens verificados.
        </p>
        <ul>
            <li><strong>Itens OK:</strong> {{ number_format($itensOk) }} ({{ $totalItens > 0 ? number_format(($itensOk / $totalItens) * 100, 1) : 0 }}%)</li>
            <li><strong>Itens com Atenção:</strong> {{ number_format($itensAtencao) }} ({{ $totalItens > 0 ? number_format(($itensAtencao / $totalItens) * 100, 1) : 0 }}%)</li>
            <li><strong>Itens com Problema:</strong> {{ number_format($itensProblema) }} ({{ $totalItens > 0 ? number_format(($itensProblema / $totalItens) * 100, 1) : 0 }}%)</li>
        </ul>
    </div>

    @php
        $colaboradores = $inspecoes->groupBy('colaborador');
    @endphp

    <div class="description-box">
        <h4>Colaboradores</h4>
        @if($colaboradores->count() > 0)
            <ul>
                @foreach($colaboradores as $colaborador => $inspecoesColaborador)
                    <li><strong>{{ $colaborador }}:</strong> {{ $inspecoesColaborador->count() }} inspeção(ões)</li>
                @endforeach
            </ul>
        @else
            <p>Nenhum colaborador específico identificado.</p>
        @endif
    </div>

    @php
        $itensProblematicos = [];
        $campos = [
            'nivel_oleo_motor' => 'Nível de Óleo do Motor',
            'vazamentos' => 'Vazamentos',
            'arrefecimento' => 'Arrefecimento',
            'combustivel' => 'Combustível',
            'bateria' => 'Bateria',
            'alternador' => 'Alternador',
            'correias' => 'Correias',
            'mangueiras' => 'Mangueiras',
            'filtros' => 'Filtros',
            'painel_controle' => 'Painel de Controle'
        ];
        
        foreach($campos as $campo => $nome) {
            $problemas = $inspecoes->where($campo, 'problema')->count();
            $atencoes = $inspecoes->where($campo, 'atencao')->count();
            if($problemas > 0 || $atencoes > 0) {
                $itensProblematicos[$nome] = ['problemas' => $problemas, 'atencoes' => $atencoes];
            }
        }
    @endphp

    @if(count($itensProblematicos) > 0)
    <div class="description-box">
        <h4>Itens que Mais Apresentaram Problemas</h4>
        <ul>
            @foreach($itensProblematicos as $item => $dados)
                <li><strong>{{ $item }}:</strong> {{ $dados['problemas'] }} problema(s), {{ $dados['atencoes'] }} atenção(ões)</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="description-box">
        <h4>Recomendações</h4>
        @php
            $percentualOk = $totalItens > 0 ? ($itensOk / $totalItens) * 100 : 0;
        @endphp
        
        @if($percentualOk >= 90)
            <p style="color: green;"><strong>Excelente:</strong> Mais de 90% dos itens estão em condições normais. Continue com a manutenção preventiva.</p>
        @elseif($percentualOk >= 80)
            <p style="color: orange;"><strong>Bom:</strong> Entre 80-90% dos itens estão normais. Monitore os itens em atenção.</p>
        @elseif($percentualOk >= 70)
            <p style="color: orange;"><strong>Atenção:</strong> Entre 70-80% dos itens estão normais. Planeje manutenções corretivas.</p>
        @else
            <p style="color: red;"><strong>Crítico:</strong> Menos de 70% dos itens estão normais. Ação imediata necessária.</p>
        @endif
        
        @if($itensProblema > 0)
            <p>Priorize a correção dos {{ $itensProblema }} itens com problema identificados.</p>
        @endif
        
        @if($itensAtencao > 0)
            <p>Monitore de perto os {{ $itensAtencao }} itens que requerem atenção.</p>
        @endif
    </div>
</div>
@endsection 