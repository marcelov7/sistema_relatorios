<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Acessibilidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Teste de Acessibilidade</h1>
        <p>Esta é uma página de teste para verificar se o componente de acessibilidade está funcionando.</p>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Card de Teste</h5>
                <p class="card-text">Este é um card de teste para verificar as funcionalidades de acessibilidade.</p>
                <button class="btn btn-primary">Botão de Teste</button>
            </div>
        </div>
        
        <div class="mt-4">
            <button id="testButton" class="btn btn-success">Testar Botão de Acessibilidade</button>
        </div>
    </div>

    @include('components.accessibility-toolbar')

    <script>
        document.getElementById('testButton').addEventListener('click', function() {
            const toggle = document.getElementById('accessibilityToggle');
            if (toggle) {
                console.log('Simulando clique no botão de acessibilidade...');
                toggle.click();
            } else {
                alert('Botão de acessibilidade não encontrado!');
            }
        });
        
        // Teste adicional
        setTimeout(function() {
            console.log('Verificando elementos após 1 segundo...');
            const toggle = document.getElementById('accessibilityToggle');
            const panel = document.getElementById('accessibilityPanel');
            
            console.log('Toggle encontrado:', !!toggle);
            console.log('Panel encontrado:', !!panel);
            
            if (toggle) {
                console.log('Elemento toggle existe');
                console.log('Onclick atual:', toggle.onclick);
            }
        }, 1000);
    </script>
</body>
</html> 