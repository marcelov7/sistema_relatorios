#!/bin/bash

echo "=== VERIFICANDO ERROS DE ACESSO NULL EM VIEWS ==="
echo ""

echo "1. Verificando correção aplicada em index.blade.php:"
grep -n "usuario ?" resources/views/relatorios/index.blade.php || echo "Correção não encontrada!"
echo ""

echo "2. Verificando se ainda existem acessos inseguros em index.blade.php:"
grep -n "usuario->name\|usuario->email" resources/views/relatorios/index.blade.php || echo "Nenhum acesso inseguro encontrado"
echo ""

echo "3. Verificando outros acessos inseguros em todas as views de relatórios:"
find resources/views/relatorios -name "*.blade.php" -exec grep -Hn "->name\|->email\|->nome" {} \; || echo "Nenhum encontrado"
echo ""

echo "4. Verificando acessos a relacionamentos sem verificação null:"
find resources/views -name "*.blade.php" -exec grep -Hn "\$[a-zA-Z_]*->[a-zA-Z_]*->" {} \; || echo "Nenhum encontrado"
echo ""

echo "5. Últimos 10 erros do log:"
tail -10 storage/logs/laravel.log
echo ""

echo "6. Testando sintaxe do arquivo corrigido:"
php -l resources/views/relatorios/index.blade.php 