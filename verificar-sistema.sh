#!/bin/bash

echo "🔍 Verificação Completa do Sistema - Upload de Imagens"
echo "======================================================"

# 1. Verificar se estamos no diretório correto
if [ ! -f "artisan" ]; then
    echo "❌ Erro: Execute este script na pasta do projeto Laravel."
    exit 1
fi

echo "✅ Diretório do projeto confirmado"
echo ""

# 2. Verificar PHP e extensões necessárias
echo "🐘 Verificando PHP e extensões..."
php_version=$(php -v | head -n1)
echo "- Versão PHP: $php_version"

# Verificar extensões críticas para upload
extensions=("gd" "fileinfo" "mbstring" "openssl")
for ext in "${extensions[@]}"; do
    if php -m | grep -q "^$ext$"; then
        echo "✅ Extensão $ext: Instalada"
    else
        echo "❌ Extensão $ext: NÃO instalada"
    fi
done

# Verificar configurações PHP para upload
echo ""
echo "📋 Configurações PHP para upload:"
echo "- upload_max_filesize: $(php -r 'echo ini_get("upload_max_filesize");')"
echo "- post_max_size: $(php -r 'echo ini_get("post_max_size");')"
echo "- max_file_uploads: $(php -r 'echo ini_get("max_file_uploads");')"
echo "- memory_limit: $(php -r 'echo ini_get("memory_limit");')"
echo "- max_execution_time: $(php -r 'echo ini_get("max_execution_time");')"

echo ""

# 3. Verificar diretórios e permissões
echo "📁 Verificando diretórios e permissões..."

directories=(
    "storage"
    "storage/app"
    "storage/app/public"
    "storage/app/public/relatorios"
    "storage/logs"
    "bootstrap/cache"
    "public/storage"
)

for dir in "${directories[@]}"; do
    if [ -d "$dir" ] || [ -L "$dir" ]; then
        permissions=$(stat -c "%a" "$dir" 2>/dev/null || stat -f "%A" "$dir" 2>/dev/null)
        owner=$(stat -c "%U:%G" "$dir" 2>/dev/null || stat -f "%Su:%Sg" "$dir" 2>/dev/null)
        if [ -w "$dir" ]; then
            echo "✅ $dir - Permissões: $permissions - Dono: $owner - Gravável: SIM"
        else
            echo "⚠️ $dir - Permissões: $permissions - Dono: $owner - Gravável: NÃO"
        fi
    else
        echo "❌ $dir - NÃO EXISTE"
    fi
done

echo ""

# 4. Verificar link simbólico do storage
echo "🔗 Verificando link simbólico..."
if [ -L "public/storage" ]; then
    target=$(readlink public/storage)
    echo "✅ Link simbólico existe: public/storage -> $target"
    
    if [ -d "$target" ]; then
        echo "✅ Destino do link é válido"
    else
        echo "❌ Destino do link NÃO existe: $target"
    fi
else
    echo "❌ Link simbólico NÃO existe"
    echo "💡 Execute: php artisan storage:link"
fi

echo ""

# 5. Verificar configurações do Laravel
echo "⚙️ Verificando configurações do Laravel..."
if [ -f ".env" ]; then
    echo "✅ Arquivo .env existe"
    
    # Verificar APP_KEY
    if grep -q "APP_KEY=base64:" .env; then
        echo "✅ APP_KEY está configurada"
    else
        echo "❌ APP_KEY NÃO está configurada"
        echo "💡 Execute: php artisan key:generate"
    fi
    
    # Verificar APP_URL
    app_url=$(grep "APP_URL=" .env | cut -d'=' -f2)
    echo "- APP_URL: $app_url"
    
    # Verificar configuração de storage
    filesystem_disk=$(grep "FILESYSTEM_DISK=" .env | cut -d'=' -f2 || echo "local")
    echo "- FILESYSTEM_DISK: $filesystem_disk"
    
else
    echo "❌ Arquivo .env NÃO existe"
    echo "💡 Copie .env.example para .env e configure"
fi

echo ""

# 6. Testar criação de arquivo
echo "📝 Testando criação de arquivo..."
test_dir="storage/app/public/relatorios"
test_file="$test_dir/test_$(date +%s).txt"

if [ -d "$test_dir" ]; then
    if echo "Teste de escrita" > "$test_file" 2>/dev/null; then
        echo "✅ Criação de arquivo: OK"
        rm -f "$test_file"
        echo "✅ Remoção de arquivo: OK"
    else
        echo "❌ Falha na criação de arquivo em: $test_dir"
    fi
else
    echo "❌ Diretório de teste não existe: $test_dir"
fi

echo ""

# 7. Verificar espaço em disco
echo "💾 Verificando espaço em disco..."
df_output=$(df -h . 2>/dev/null | tail -1)
if [ ! -z "$df_output" ]; then
    echo "$df_output"
    
    # Extrair percentual de uso
    usage_percent=$(echo "$df_output" | awk '{print $5}' | sed 's/%//')
    if [ "$usage_percent" -gt 90 ]; then
        echo "⚠️ ATENÇÃO: Disco com pouco espaço ($usage_percent% usado)"
    else
        echo "✅ Espaço em disco OK ($usage_percent% usado)"
    fi
else
    echo "⚠️ Não foi possível verificar espaço em disco"
fi

echo ""

# 8. Verificar últimas imagens (se houver)
echo "🖼️ Verificando últimas imagens salvas..."
if which mysql >/dev/null 2>&1 && [ -f ".env" ]; then
    # Tentar conectar no banco e verificar últimas imagens
    echo "💡 Para verificar imagens no banco, execute:"
    echo "   php artisan tinker"
    echo "   App\\Models\\RelatorioImagem::latest()->take(5)->get(['nome_original', 'caminho_arquivo', 'data_upload'])"
else
    echo "⚠️ MySQL não disponível ou .env não configurado"
fi

# Verificar arquivos físicos no storage
image_count=$(find storage/app/public/relatorios -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" -o -name "*.webp" 2>/dev/null | wc -l)
echo "- Arquivos de imagem encontrados no storage: $image_count"

echo ""

# 9. Verificar logs recentes
echo "📋 Verificando logs recentes..."
log_file="storage/logs/laravel.log"
if [ -f "$log_file" ]; then
    echo "✅ Arquivo de log existe"
    
    # Verificar se há erros recentes relacionados a imagens
    recent_errors=$(tail -100 "$log_file" | grep -i "erro\|error\|exception" | grep -i "imagem\|image\|upload" | wc -l)
    if [ "$recent_errors" -gt 0 ]; then
        echo "⚠️ Encontrados $recent_errors erros recentes relacionados a imagens"
        echo "💡 Verifique: tail -50 $log_file"
    else
        echo "✅ Nenhum erro recente relacionado a imagens"
    fi
else
    echo "⚠️ Arquivo de log não existe: $log_file"
fi

echo ""
echo "======================================================"
echo "🎯 RESUMO DA VERIFICAÇÃO"
echo "======================================================"

# Verificação final
issues=0

# Verificar problemas críticos
if [ ! -d "storage/app/public" ]; then
    echo "❌ CRÍTICO: Diretório storage/app/public não existe"
    issues=$((issues + 1))
fi

if [ ! -L "public/storage" ]; then
    echo "❌ CRÍTICO: Link simbólico public/storage não existe"
    issues=$((issues + 1))
fi

if [ ! -f ".env" ]; then
    echo "❌ CRÍTICO: Arquivo .env não existe"
    issues=$((issues + 1))
fi

if ! php -m | grep -q "^gd$"; then
    echo "❌ CRÍTICO: Extensão PHP GD não instalada"
    issues=$((issues + 1))
fi

if [ "$issues" -eq 0 ]; then
    echo "🎉 SISTEMA OK! Pronto para upload de imagens"
    echo ""
    echo "📋 Para testar:"
    echo "1. Acesse o sistema no navegador"
    echo "2. Crie um novo relatório"
    echo "3. Adicione algumas imagens"
    echo "4. Submeta o formulário"
    echo "5. Verifique se as imagens aparecem no relatório"
else
    echo "⚠️ ENCONTRADOS $issues PROBLEMAS CRÍTICOS"
    echo "💡 Corrija os problemas acima antes de testar"
fi

echo "" 