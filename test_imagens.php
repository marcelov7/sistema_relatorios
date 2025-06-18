<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Relatorio;

$relatorio = Relatorio::with('imagens')->find(20);

if ($relatorio) {
    echo "Relatório: " . $relatorio->titulo . "\n";
    echo "Imagens: " . $relatorio->imagens->count() . "\n";
    
    foreach($relatorio->imagens as $img) {
        echo "ID: " . $img->id . "\n";
        echo "Nome: " . $img->nome_original . "\n";
        echo "Caminho: " . $img->caminho_arquivo . "\n";
        echo "URL: " . $img->url . "\n";
        echo "---\n";
    }
} else {
    echo "Relatório não encontrado\n";
} 