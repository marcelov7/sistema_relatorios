<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "=== TESTE DE CONEXÃO ===\n";
echo "Banco configurado: " . env('DB_DATABASE') . "\n";
echo "Host: " . env('DB_HOST') . "\n";
echo "Usuário: " . env('DB_USERNAME') . "\n\n";

try {
    // Teste de conexão
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexão com banco OK!\n\n";
    
    // Teste com tabela usuarios
    echo "=== TESTANDO TABELA USUARIOS ===\n";
    $count = DB::table('usuarios')->count();
    echo "Total de usuários: $count\n";
    
    if ($count > 0) {
        $usuarios = DB::table('usuarios')->select('id', 'nome', 'email')->limit(3)->get();
        echo "Primeiros usuários:\n";
        foreach ($usuarios as $user) {
            echo "- ID: {$user->id}, Nome: {$user->nome}, Email: {$user->email}\n";
        }
    }
    echo "\n";
    
    // Teste com tabela users (do Laravel)
    echo "=== TESTANDO TABELA USERS (LARAVEL) ===\n";
    try {
        $userCount = User::count();
        echo "Total de users (Laravel): $userCount\n";
    } catch (Exception $e) {
        echo "Tabela users não existe ou erro: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Listar todas as tabelas
    echo "=== TABELAS DISPONÍVEIS ===\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array) $table)[0];
        echo "- $tableName\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO TESTE ===\n"; 