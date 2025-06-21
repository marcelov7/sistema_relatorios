#!/bin/bash

echo "🚀 Deploy Simples - Sistema de Relatórios V2"
echo "============================================="

# Navegar para o diretório correto
cd /home/user/htdocs/sistema-relatorios

echo "📁 Diretório atual: $(pwd)"

# Fazer backup rápido
echo "💾 Fazendo backup..."
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Puxar mudanças
echo "⬇️ Baixando mudanças..."
git pull origin main

# Instalar dependências
echo "📦 Instalando dependências..."
composer install --no-dev --optimize-autoloader

# Executar migrations
echo "🗄️ Executando migrations..."
php artisan migrate --force

# Limpar e refazer cache
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "🔄 Criando novo cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verificar permissões
echo "🔐 Ajustando permissões..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Deploy concluído!"
echo ""
echo "🔗 Rotas V2 disponíveis:"
echo "   - /relatorios-v2/create"
echo "   - /api/equipamentos-por-local"
echo ""
echo "🧪 Para testar: https://seusite.com/relatorios-v2/create" 