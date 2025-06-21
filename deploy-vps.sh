#!/bin/bash

echo "🚀 Iniciando deploy seguro na VPS..."
echo "========================================"

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Não está no diretório do Laravel! Execute este script na pasta do projeto."
    exit 1
fi

echo "✅ Diretório do projeto confirmado"

# 2. Backup do .env (caso existam mudanças locais)
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    echo "✅ Backup do .env criado"
fi

# 3. Fazer pull das mudanças
echo "📥 Baixando mudanças do repositório..."
git pull origin main
if [ $? -ne 0 ]; then
    echo "❌ Erro no git pull! Verifique conflitos."
    exit 1
fi
echo "✅ Código atualizado com sucesso"

# 4. Verificar e instalar dependências do Composer
echo "📦 Verificando dependências do Composer..."
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo "❌ Erro ao instalar dependências do Composer!"
    exit 1
fi
echo "✅ Dependências do Composer atualizadas"

# 5. Verificar permissões dos diretórios críticos
echo "🔒 Verificando e corrigindo permissões..."
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/
echo "✅ Permissões corrigidas"

# 6. Verificar se o diretório público de storage existe
if [ ! -d "storage/app/public" ]; then
    mkdir -p storage/app/public
    echo "✅ Diretório storage/app/public criado"
fi

if [ ! -d "storage/app/public/relatorios" ]; then
    mkdir -p storage/app/public/relatorios
    echo "✅ Diretório storage/app/public/relatorios criado"
fi

# 7. Verificar e criar link simbólico do storage
if [ ! -L "public/storage" ] && [ ! -d "public/storage" ]; then
    php artisan storage:link
    echo "✅ Link simbólico do storage criado"
else
    echo "✅ Link simbólico do storage já existe"
fi

# 8. Limpar todos os caches
echo "🧹 Limpando caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✅ Caches limpos"

# 9. Verificar configurações críticas
echo "🔍 Verificando configurações..."

# Verificar se o arquivo .env existe
if [ ! -f ".env" ]; then
    echo "❌ Arquivo .env não encontrado! Copie de .env.example e configure."
    exit 1
fi

# Verificar se APP_KEY está definida
if ! grep -q "APP_KEY=base64:" .env; then
    echo "⚠️ APP_KEY não está definida. Gerando..."
    php artisan key:generate
fi

echo "✅ Configurações verificadas"

# 10. Executar migrações (se necessário)
echo "🗄️ Verificando migrações do banco de dados..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "⚠️ Algumas migrações falharam, mas continuando..."
fi

# 11. Verificar status dos arquivos importantes
echo "📋 Status final dos arquivos..."
echo "- .env: $([ -f .env ] && echo '✅ Existe' || echo '❌ Não existe')"
echo "- storage/app/public: $([ -d storage/app/public ] && echo '✅ Existe' || echo '❌ Não existe')"
echo "- public/storage: $([ -L public/storage ] && echo '✅ Link existe' || echo '❌ Link não existe')"
echo "- storage/logs: $([ -d storage/logs ] && echo '✅ Existe' || echo '❌ Não existe')"

# 12. Testar se o Laravel está funcionando
echo "🧪 Testando Laravel..."
php artisan --version
if [ $? -eq 0 ]; then
    echo "✅ Laravel está funcionando!"
else
    echo "❌ Problema com o Laravel!"
fi

echo "========================================"
echo "🎉 Deploy concluído!"
echo ""
echo "📋 Próximos passos:"
echo "1. Acesse o sistema no navegador"
echo "2. Teste a criação de relatório com imagens"
echo "3. Verifique se as imagens são salvas corretamente"
echo ""
echo "🐛 Se houver problemas, verifique:"
echo "- Log do Laravel: tail -f storage/logs/laravel.log"
echo "- Log do Apache/Nginx"
echo "- Permissões dos diretórios" 