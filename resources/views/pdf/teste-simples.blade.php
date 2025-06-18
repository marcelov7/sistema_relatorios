<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teste PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { color: #333; }
        p { margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Teste de PDF</h1>
    <p>Este e um teste simples de geracao de PDF.</p>
    <p>Data: {{ now()->format('d/m/Y H:i:s') }}</p>
    
    @if(isset($inspecao))
        <h2>Inspecao ID: {{ $inspecao->id }}</h2>
        <p>Colaborador: {{ $inspecao->colaborador ?? 'N/A' }}</p>
        <p>Data: {{ $inspecao->data ? $inspecao->data : 'N/A' }}</p>
    @endif
</body>
</html> 