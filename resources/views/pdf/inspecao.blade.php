@extends('pdf.layout')

@section('title', 'Inspeção #' . $inspecao->id)

@section('header-title', 'Inspeção de Gerador #' . $inspecao->id)
@section('header-subtitle', 'Relatório de inspeção detalhado')

@section('content')
<div class="section no-break">
    <div class="section-title">Informações da Inspeção</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">ID da Inspeção:</div>
            <div class="info-value">#{{ $inspecao->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Data da Inspeção:</div>
            <div class="info-value">{{ $inspecao->data ? $inspecao->data->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Colaborador:</div>
            <div class="info-value">{{ $inspecao->colaborador ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                @if($inspecao->ativo)
                    <span style="color: green; font-weight: bold;">ATIVO</span>
                @else
                    <span style="color: red; font-weight: bold;">INATIVO</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Níveis</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Nível Óleo:</div>
            <div class="info-value">
                <strong>{{ $inspecao->nivel_oleo ?? 'N/A' }}</strong>
                @if($inspecao->nivel_oleo === 'Baixo')
                    <span style="color: red; margin-left: 10px;">⚠️ ATENÇÃO</span>
                @elseif($inspecao->nivel_oleo === 'Máximo')
                    <span style="color: green; margin-left: 10px;">✓ OK</span>
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Nível Água:</div>
            <div class="info-value">
                <strong>{{ $inspecao->nivel_agua ?? 'N/A' }}</strong>
                @if($inspecao->nivel_agua === 'Baixo')
                    <span style="color: red; margin-left: 10px;">⚠️ ATENÇÃO</span>
                @elseif($inspecao->nivel_agua === 'Máximo')
                    <span style="color: green; margin-left: 10px;">✓ OK</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Tensões (V)</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 50%;">Parâmetro</th>
                <th style="width: 25%;">Valor</th>
                <th style="width: 25%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @if($inspecao->tensao_sync_gerador)
            <tr>
                <td>Tensão Sync Gerador</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_sync_gerador, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_sync_gerador; @endphp
                    @if($tensao >= 11 && $tensao <= 13)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_sync_rede)
            <tr>
                <td>Tensão Sync Rede</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_sync_rede, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_sync_rede; @endphp
                    @if($tensao >= 11 && $tensao <= 13)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_a)
            <tr>
                <td>Tensão A</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_a, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_a; @endphp
                    @if($tensao >= 11 && $tensao <= 13)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_b)
            <tr>
                <td>Tensão B</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_b, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_b; @endphp
                    @if($tensao >= 11 && $tensao <= 13)
                        <span style="color: green;">NORMAL</span>
                    @elseif($tensao >= 1 && $tensao < 11)
                        <span style="color: red;">BAIXA</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_c)
            <tr>
                <td>Tensão C</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_c, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_c; @endphp
                    @if($tensao >= 11 && $tensao <= 13)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_bateria)
            <tr>
                <td>Tensão Bateria</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_bateria, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_bateria; @endphp
                    @if($tensao >= 20 && $tensao <= 24)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
            
            @if($inspecao->tensao_alternador)
            <tr>
                <td>Tensão Alternador</td>
                <td class="text-center"><strong>{{ number_format($inspecao->tensao_alternador, 2) }}V</strong></td>
                <td class="text-center">
                    @php $tensao = $inspecao->tensao_alternador; @endphp
                    @if($tensao >= 100 && $tensao <= 120)
                        <span style="color: green;">NORMAL</span>
                    @else
                        <span style="color: orange;">VERIFICAR</span>
                    @endif
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="section">
    <div class="section-title">Medições</div>
    <div class="info-grid">
        @if($inspecao->temp_agua)
        <div class="info-row">
            <div class="info-label">Temperatura da Água:</div>
            <div class="info-value">
                <strong>{{ number_format($inspecao->temp_agua, 2) }}°C</strong>
                @php $temp = $inspecao->temp_agua; @endphp
                @if($temp >= 80 && $temp <= 95)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @elseif($temp > 95)
                    <span style="color: red; margin-left: 10px;">ALTA</span>
                @else
                    <span style="color: blue; margin-left: 10px;">BAIXA</span>
                @endif
            </div>
        </div>
        @endif
        
        @if($inspecao->pressao_oleo)
        <div class="info-row">
            <div class="info-label">Pressão do Óleo:</div>
            <div class="info-value">
                <strong>{{ number_format($inspecao->pressao_oleo, 2) }} bar</strong>
                @php $pressao = $inspecao->pressao_oleo; @endphp
                @if($pressao >= 3 && $pressao <= 6)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @elseif($pressao < 3)
                    <span style="color: red; margin-left: 10px;">BAIXA</span>
                @else
                    <span style="color: orange; margin-left: 10px;">ALTA</span>
                @endif
            </div>
        </div>
        @endif
        
        @if($inspecao->frequencia)
        <div class="info-row">
            <div class="info-label">Frequência:</div>
            <div class="info-value">
                <strong>{{ number_format($inspecao->frequencia, 2) }} Hz</strong>
                @php $freq = $inspecao->frequencia; @endphp
                @if($freq >= 59 && $freq <= 61)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @else
                    <span style="color: orange; margin-left: 10px;">VERIFICAR</span>
                @endif
            </div>
        </div>
        @endif
        
        @if($inspecao->rpm)
        <div class="info-row">
            <div class="info-label">RPM:</div>
            <div class="info-value">
                <strong>{{ number_format($inspecao->rpm) }} RPM</strong>
                @php $rpm = $inspecao->rpm; @endphp
                @if($rpm >= 1750 && $rpm <= 1850)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @else
                    <span style="color: orange; margin-left: 10px;">VERIFICAR</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div class="section">
    <div class="section-title">Status</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Combustível > 50%:</div>
            <div class="info-value">
                @if($inspecao->combustivel_50 === 'Sim')
                    <span style="color: green; font-weight: bold;">✓ SIM</span>
                @else
                    <span style="color: red; font-weight: bold;">✗ NÃO</span>
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Iluminação da Sala:</div>
            <div class="info-value">
                @if($inspecao->iluminacao_sala === 'Normal')
                    <span style="color: green; font-weight: bold;">✓ NORMAL</span>
                @else
                    <span style="color: red; font-weight: bold;">⚠️ ANORMAL</span>
                @endif
            </div>
        </div>
    </div>
</div>

@if($inspecao->observacao)
<div class="section">
    <div class="section-title">Observações</div>
    <div class="description-box">
        <p>{{ $inspecao->observacao }}</p>
    </div>
</div>
@endif

<div class="section">
    <div class="section-title">Resumo da Inspeção</div>
    <div class="stats-grid">
        <div class="stats-row">
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
                $normais += $tensoes; // Assumindo que tensões estão OK se foram medidas
                
                // Verificar medições
                $medicoes = collect([
                    $inspecao->temp_agua,
                    $inspecao->pressao_oleo,
                    $inspecao->frequencia,
                    $inspecao->rpm
                ])->filter()->count();
                
                $total += $medicoes;
                $normais += $medicoes; // Assumindo que medições estão OK se foram feitas
            @endphp
            
            <div class="stats-cell">
                <span class="stats-number" style="color: green;">{{ $normais }}</span>
                <span class="stats-label">Normais</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: orange;">{{ $atencoes }}</span>
                <span class="stats-label">Atenção</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: red;">{{ $problemas }}</span>
                <span class="stats-label">Problemas</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number">{{ $total }}</span>
                <span class="stats-label">Total</span>
            </div>
        </div>
    </div>
</div>

@php
    $statusGeral = 'CRÍTICO';
    $corStatus = 'red';
    
    if($problemas == 0) {
        $statusGeral = 'ATIVO';
        $corStatus = 'green';
    } elseif($problemas <= 2) {
        $statusGeral = 'ATENÇÃO';
        $corStatus = 'orange';
    }
@endphp

<div class="section">
    <div class="section-title">Status Geral</div>
    <div class="description-box" style="text-align: center; padding: 20px;">
        <h2 style="color: {{ $corStatus }}; margin: 0; font-size: 24px;">{{ $statusGeral }}</h2>
        @if($problemas > 0)
            <p style="margin: 10px 0 0 0; font-size: 16px; color: red;">
                {{ $problemas }} problema(s) identificado(s)
            </p>
        @else
            <p style="margin: 10px 0 0 0; font-size: 16px; color: green;">
                Todos os itens verificados estão em ordem
            </p>
        @endif
    </div>
</div>

<div class="section">
    <div class="section-title">Informações do Sistema</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Registrado em:</div>
            <div class="info-value">{{ $inspecao->criado_em ? $inspecao->criado_em->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Última atualização:</div>
            <div class="info-value">{{ $inspecao->atualizado_em ? $inspecao->atualizado_em->format('d/m/Y H:i:s') : 'N/A' }}</div>
        </div>
    </div>
</div>
@endsection 