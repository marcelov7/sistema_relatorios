<?php

/**
 * Script de diagnóstico para upload de imagens
 * Execute este script diretamente na VPS para testar problemas de upload
 */

echo "<h1>Diagnóstico de Upload de Imagens - VPS</h1>";
echo "<hr>";

// 1. Verificar configurações do PHP
echo "<h2>📋 Configurações PHP</h2>";
echo "<ul>";
echo "<li><strong>upload_max_filesize:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "<li><strong>post_max_size:</strong> " . ini_get('post_max_size') . "</li>";
echo "<li><strong>max_file_uploads:</strong> " . ini_get('max_file_uploads') . "</li>";
echo "<li><strong>memory_limit:</strong> " . ini_get('memory_limit') . "</li>";
echo "<li><strong>max_execution_time:</strong> " . ini_get('max_execution_time') . "</li>";
echo "<li><strong>file_uploads:</strong> " . (ini_get('file_uploads') ? 'Habilitado' : 'Desabilitado') . "</li>";
echo "<li><strong>upload_tmp_dir:</strong> " . (ini_get('upload_tmp_dir') ?: 'Default') . "</li>";
echo "</ul>";

// 2. Verificar diretórios
echo "<h2>📁 Verificação de Diretórios</h2>";
$storagePublic = __DIR__ . '/storage/app/public';
$storageRelatorios = $storagePublic . '/relatorios';
$publicStorage = __DIR__ . '/public/storage';

echo "<ul>";
echo "<li><strong>Storage Public:</strong> {$storagePublic}";
echo "<ul>";
echo "<li>Existe: " . (is_dir($storagePublic) ? '✅' : '❌') . "</li>";
echo "<li>Gravável: " . (is_writable($storagePublic) ? '✅' : '❌') . "</li>";
echo "<li>Permissões: " . (file_exists($storagePublic) ? substr(sprintf('%o', fileperms($storagePublic)), -4) : 'N/A') . "</li>";
echo "</ul></li>";

echo "<li><strong>Storage Relatórios:</strong> {$storageRelatorios}";
echo "<ul>";
echo "<li>Existe: " . (is_dir($storageRelatorios) ? '✅' : '❌') . "</li>";
echo "<li>Gravável: " . (is_writable($storageRelatorios) ? '✅' : '❌') . "</li>";
if (!is_dir($storageRelatorios)) {
    $created = mkdir($storageRelatorios, 0755, true);
    echo "<li>Tentativa de criação: " . ($created ? '✅' : '❌') . "</li>";
}
echo "</ul></li>";

echo "<li><strong>Public Storage (Link):</strong> {$publicStorage}";
echo "<ul>";
echo "<li>Existe: " . (file_exists($publicStorage) ? '✅' : '❌') . "</li>";
echo "<li>É Link: " . (is_link($publicStorage) ? '✅' : '❌') . "</li>";
if (is_link($publicStorage)) {
    echo "<li>Aponta para: " . readlink($publicStorage) . "</li>";
}
echo "</ul></li>";
echo "</ul>";

// 3. Verificar espaço em disco
echo "<h2>💾 Espaço em Disco</h2>";
$freeBytes = disk_free_space(__DIR__);
$totalBytes = disk_total_space(__DIR__);

if ($freeBytes && $totalBytes) {
    $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
    $totalGB = round($totalBytes / 1024 / 1024 / 1024, 2);
    $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);
    
    echo "<ul>";
    echo "<li><strong>Espaço livre:</strong> {$freeGB} GB</li>";
    echo "<li><strong>Espaço total:</strong> {$totalGB} GB</li>";
    echo "<li><strong>Uso:</strong> {$usedPercent}%</li>";
    echo "</ul>";
    
    if ($freeGB < 1) {
        echo "<p style='color: orange;'>⚠️ <strong>Aviso:</strong> Pouco espaço em disco disponível!</p>";
    }
} else {
    echo "<p>Não foi possível verificar o espaço em disco</p>";
}

// 4. Teste de escrita de arquivo
echo "<h2>📝 Teste de Escrita</h2>";
$testFile = $storageRelatorios . '/test_' . time() . '.txt';
$testContent = 'Teste de escrita: ' . date('Y-m-d H:i:s');

try {
    if (!is_dir($storageRelatorios)) {
        mkdir($storageRelatorios, 0755, true);
    }
    
    $result = file_put_contents($testFile, $testContent);
    
    if ($result !== false) {
        echo "<p style='color: green;'>✅ <strong>Sucesso:</strong> Arquivo de teste criado ({$result} bytes)</p>";
        
        // Verificar se o arquivo pode ser lido
        $readContent = file_get_contents($testFile);
        if ($readContent === $testContent) {
            echo "<p style='color: green;'>✅ <strong>Sucesso:</strong> Arquivo de teste lido corretamente</p>";
        } else {
            echo "<p style='color: red;'>❌ <strong>Erro:</strong> Conteúdo do arquivo não confere</p>";
        }
        
        // Remover arquivo de teste
        if (unlink($testFile)) {
            echo "<p style='color: green;'>✅ <strong>Sucesso:</strong> Arquivo de teste removido</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ <strong>Aviso:</strong> Não foi possível remover o arquivo de teste</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ <strong>Erro:</strong> Não foi possível criar arquivo de teste</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Erro:</strong> " . $e->getMessage() . "</p>";
}

// 5. Se há POST, testar upload real
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "<h2>🖼️ Resultado do Upload</h2>";
    
    $file = $_FILES['test_image'];
    
    echo "<h3>Informações do arquivo enviado:</h3>";
    echo "<ul>";
    echo "<li><strong>Nome:</strong> " . $file['name'] . "</li>";
    echo "<li><strong>Tipo:</strong> " . $file['type'] . "</li>";
    echo "<li><strong>Tamanho:</strong> " . round($file['size'] / 1024, 2) . " KB</li>";
    echo "<li><strong>Erro:</strong> " . $file['error'] . " (" . getUploadErrorMessage($file['error']) . ")</li>";
    echo "<li><strong>Arquivo temporário:</strong> " . $file['tmp_name'] . "</li>";
    echo "</ul>";
    
    if ($file['error'] === UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {
        $destination = $storageRelatorios . '/upload_test_' . time() . '_' . $file['name'];
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            echo "<p style='color: green;'>✅ <strong>Sucesso:</strong> Arquivo movido para: {$destination}</p>";
            
            // Verificar se o arquivo existe
            if (file_exists($destination)) {
                echo "<p style='color: green;'>✅ <strong>Sucesso:</strong> Arquivo confirmado no destino</p>";
                echo "<p><strong>Tamanho final:</strong> " . round(filesize($destination) / 1024, 2) . " KB</p>";
            } else {
                echo "<p style='color: red;'>❌ <strong>Erro:</strong> Arquivo não encontrado no destino</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ <strong>Erro:</strong> Falha ao mover arquivo</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ <strong>Erro no upload:</strong> " . getUploadErrorMessage($file['error']) . "</p>";
    }
}

function getUploadErrorMessage($error) {
    switch ($error) {
        case UPLOAD_ERR_OK:
            return 'Sem erro';
        case UPLOAD_ERR_INI_SIZE:
            return 'Arquivo excede upload_max_filesize';
        case UPLOAD_ERR_FORM_SIZE:
            return 'Arquivo excede MAX_FILE_SIZE do formulário';
        case UPLOAD_ERR_PARTIAL:
            return 'Upload parcial';
        case UPLOAD_ERR_NO_FILE:
            return 'Nenhum arquivo enviado';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Diretório temporário ausente';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Falha ao escrever no disco';
        case UPLOAD_ERR_EXTENSION:
            return 'Upload interrompido por extensão';
        default:
            return 'Erro desconhecido';
    }
}
?>

<hr>
<h2>🧪 Teste de Upload</h2>
<p>Use o formulário abaixo para testar o upload de uma imagem:</p>

<form method="POST" enctype="multipart/form-data" style="border: 1px solid #ddd; padding: 20px; margin: 20px 0;">
    <label for="test_image">Selecione uma imagem (máx. 7MB):</label><br><br>
    <input type="file" name="test_image" id="test_image" accept="image/*" required><br><br>
    <input type="submit" value="Testar Upload" style="background: #007cba; color: white; padding: 10px 20px; border: none; cursor: pointer;">
</form>

<hr>
<h2>💡 Possíveis Soluções</h2>
<div style="background: #f0f0f0; padding: 15px; margin: 20px 0;">
    <h3>1. Problemas de Configuração PHP:</h3>
    <ul>
        <li>Edite <code>php.ini</code>: <code>upload_max_filesize = 10M</code></li>
        <li>Edite <code>php.ini</code>: <code>post_max_size = 50M</code></li>
        <li>Edite <code>php.ini</code>: <code>max_file_uploads = 20</code></li>
        <li>Reinicie o servidor web após alterar o php.ini</li>
    </ul>
    
    <h3>2. Problemas de Permissão:</h3>
    <ul>
        <li><code>sudo chown -R www-data:www-data storage/</code></li>
        <li><code>sudo chmod -R 755 storage/</code></li>
        <li><code>sudo chmod -R 755 public/</code></li>
    </ul>
    
    <h3>3. Link Simbólico:</h3>
    <ul>
        <li><code>php artisan storage:link</code></li>
    </ul>
    
    <h3>4. Verificar logs do servidor:</h3>
    <ul>
        <li>Apache: <code>/var/log/apache2/error.log</code></li>
        <li>Nginx: <code>/var/log/nginx/error.log</code></li>
        <li>PHP: <code>/var/log/php_errors.log</code></li>
    </ul>
</div>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2, h3 { color: #333; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
</style> 