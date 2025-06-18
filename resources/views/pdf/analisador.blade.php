@extends('pdf.layout')

@section('title', 'Analisador #' . $analisador->id)

@section('header-title', 'Analisador ' . $analisador->analyzer . ' #' . $analisador->id)
@section('header-subtitle', 'Relatório de inspeção de analisador')

@section('content')
<div class="section no-break">
    <div class="section-title">Informações do Analisador</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">ID do Analisador:</div>
            <div class="info-value">#{{ $analisador->id }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tipo de Analisador:</div>
            <div class="info-value">{{ $analisador->analyzer }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Data da Verificação:</div>
            <div class="info-value">{{ $analisador->check_date->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Responsável:</div>
            <div class="info-value">{{ $analisador->usuario->name ?? 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                @if($analisador->ativo)
                    <span style="color: green; font-weight: bold;">ATIVO</span>
                @else
                    <span style="color: red; font-weight: bold;">INATIVO</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="section-title">Status dos Componentes</div>
    <div class="info-grid">
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
        
        @foreach($componentes as $campo => $nome)
        <div class="info-row">
            <div class="info-label">{{ $nome }}:</div>
            <div class="info-value">
                @if($analisador->$campo)
                    <span style="color: green; font-weight: bold;">✓ FUNCIONANDO</span>
                @else
                    <span style="color: red; font-weight: bold;">✗ COM PROBLEMA</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="section">
    <div class="section-title">Medições Ambientais</div>
    <div class="info-grid">
        @if($analisador->room_temperature)
        <div class="info-row">
            <div class="info-label">Temperatura do Ambiente:</div>
            <div class="info-value">
                <strong>{{ number_format($analisador->room_temperature, 1) }}°C</strong>
                @php $temp = $analisador->room_temperature; @endphp
                @if($temp >= 15 && $temp <= 30)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @elseif(($temp >= 10 && $temp < 15) || ($temp > 30 && $temp <= 35))
                    <span style="color: orange; margin-left: 10px;">ATENÇÃO</span>
                @else
                    <span style="color: red; margin-left: 10px;">CRÍTICO</span>
                @endif
            </div>
        </div>
        @endif
        
        @if($analisador->air_pressure)
        <div class="info-row">
            <div class="info-label">Pressão do Ar:</div>
            <div class="info-value">
                <strong>{{ number_format($analisador->air_pressure, 2) }} bar</strong>
                @php $pressure = $analisador->air_pressure; @endphp
                @if($pressure >= 2.0 && $pressure <= 4.0)
                    <span style="color: green; margin-left: 10px;">NORMAL</span>
                @elseif(($pressure >= 1.5 && $pressure < 2.0) || ($pressure > 4.0 && $pressure <= 5.0))
                    <span style="color: orange; margin-left: 10px;">ATENÇÃO</span>
                @else
                    <span style="color: red; margin-left: 10px;">CRÍTICO</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@if($analisador->observation)
<div class="section">
    <div class="section-title">Observações</div>
    <div class="description-box">
        <p>{{ $analisador->observation }}</p>
    </div>
</div>
@endif

<div class="section">
    <div class="section-title">Avaliação Geral</div>
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <span class="stats-number" style="color: green;">
                    @php
                        $funcionando = 0;
                        foreach($componentes as $campo => $nome) {
                            if($analisador->$campo) $funcionando++;
                        }
                        echo $funcionando;
                    @endphp
                </span>
                <span class="stats-label">Funcionando</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: red;">
                    @php
                        $problemas = 0;
                        foreach($componentes as $campo => $nome) {
                            if(!$analisador->$campo) $problemas++;
                        }
                        echo $problemas;
                    @endphp
                </span>
                <span class="stats-label">Com Problema</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number">{{ count($componentes) }}</span>
                <span class="stats-label">Total</span>
            </div>
            <div class="stats-cell">
                <span class="stats-number" style="color: blue;">
                    {{ number_format(($funcionando / count($componentes)) * 100, 1) }}%
                </span>
                <span class="stats-label">Eficiência</span>
            </div>
        </div>
    </div>
</div>

@php
    $statusGeral = 'CRÍTICO';
    $corStatus = 'red';
    $eficiencia = ($funcionando / count($componentes)) * 100;
    
    if ($eficiencia == 100) {
        $statusGeral = 'TODOS OK';
        $corStatus = 'green';
    } elseif ($eficiencia >= 80) {
        $statusGeral = 'BOM';
        $corStatus = 'green';
    } elseif ($eficiencia >= 60) {
        $statusGeral = 'ATENÇÃO';
        $corStatus = 'orange';
    }
@endphp

<div class="section">
    <div class="section-title">Status Geral</div>
    <div class="description-box" style="text-align: center; padding: 20px;">
        <h2 style="color: {{ $corStatus }}; margin: 0; font-size: 24px;">{{ $statusGeral }}</h2>
        <p style="margin: 10px 0 0 0; font-size: 16px;">
            {{ $funcionando }} de {{ count($componentes) }} componentes funcionando 
            ({{ number_format($eficiencia, 1) }}% de eficiência)
        </p>
    </div>
</div>

<div class="section">
    <div class="section-title">Informações do Sistema</div>
    <div class="info-grid">
        <div class="info-row">
            <div class="info-label">Registrado em:</div>
            <div class="info-value">{{ $analisador->created_at->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Última atualização:</div>
            <div class="info-value">{{ $analisador->updated_at->format('d/m/Y H:i:s') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tenant ID:</div>
            <div class="info-value">#{{ $analisador->tenant_id }}</div>
        </div>
    </div>
</div>
@endsection 