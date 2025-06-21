#!/bin/bash

echo "🚀 Deploy Sistema V2 - Múltiplos Equipamentos"
echo "=============================================="

# Navegar para o diretório do projeto
cd /home/user/htdocs/sistema-relatorios

echo "📥 Atualizando código do repositório..."
git pull origin main

echo "🧹 Limpando caches do Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "🔄 Recarregando configurações..."
php artisan config:cache
php artisan route:cache

echo "✅ Deploy concluído!"
echo ""
echo "🌐 Acesse: http://31.97.168.137/relatorios"
echo "🆕 Botão V2 deve aparecer ao lado do 'Novo Relatório'"
echo ""
echo "📋 Rotas V2 disponíveis:"
echo "- /relatorios-v2/create (Criar)"
echo "- /relatorios-v2/{id} (Visualizar)"
echo "- /relatorios-v2/{id}/edit (Editar)"
echo "- /relatorios-v2/{id}/pdf (PDF)" 