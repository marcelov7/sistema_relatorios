@extends('layouts.app')

@section('title', 'Teste Editar Relatório V2 - Sistema de Relatórios')

@section('content')
<div class="container">
    <h1>Editar Relatório V2 - TESTE</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Informações do Relatório:</h5>
            <p><strong>ID:</strong> {{ $relatorio->id }}</p>
            <p><strong>Título:</strong> {{ $relatorio->titulo }}</p>
            <p><strong>Status:</strong> {{ $relatorio->status }}</p>
            
            <h5>Dados Carregados:</h5>
            <p><strong>Itens:</strong> {{ $itens->count() }}</p>
            <p><strong>Locais:</strong> {{ $locais->count() }}</p>
            <p><strong>Equipamentos:</strong> {{ $equipamentos->count() }}</p>
            
            @if($itens->count() > 0)
                <h5>Itens do Relatório:</h5>
                <ul>
                @foreach($itens as $item)
                    <li>
                        Equipamento ID: {{ $item->equipamento_id ?? 'N/A' }} - 
                        Descrição: {{ $item->descricao_equipamento ?? 'N/A' }}
                    </li>
                @endforeach
                </ul>
            @endif
            
            <a href="{{ route('relatorios-v2.show', $relatorio) }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>
@endsection 