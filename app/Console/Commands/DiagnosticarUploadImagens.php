<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\RelatorioImagem;
use App\Models\Relatorio;

class DiagnosticarUploadImagens extends Command
{
    protected $signature = 'relatorios:diagnosticar-upload';
    protected $description = 'Diagnostica problemas com upload de imagens em relatórios';

    public function handle()
    {
        $this->info('🔍 Iniciando diagnóstico de upload de imagens...');
        $this->newLine();

        // 1. Verificar configurações do PHP
        $this->info('📋 Verificando configurações do PHP:');
        $this->line('- upload_max_filesize: ' . ini_get('upload_max_filesize'));
        $this->line('- post_max_size: ' . ini_get('post_max_size'));
        $this->line('- max_file_uploads: ' . ini_get('max_file_uploads'));
        $this->line('- memory_limit: ' . ini_get('memory_limit'));
        $this->line('- max_execution_time: ' . ini_get('max_execution_time'));
        $this->newLine();

        // 2. Verificar permissões de diretórios
        $this->info('📁 Verificando permissões de diretórios:');
        $storagePath = storage_path('app/public');
        $relatoriosPath = storage_path('app/public/relatorios');
        
        $this->line('- Storage path: ' . $storagePath);
        $this->line('  Existe: ' . (is_dir($storagePath) ? '✅' : '❌'));
        $this->line('  Gravável: ' . (is_writable($storagePath) ? '✅' : '❌'));
        
        if (!is_dir($relatoriosPath)) {
            $this->warn('Criando diretório relatorios...');
            Storage::disk('public')->makeDirectory('relatorios');
        }
        
        $this->line('- Relatórios path: ' . $relatoriosPath);
        $this->line('  Existe: ' . (is_dir($relatoriosPath) ? '✅' : '❌'));
        $this->line('  Gravável: ' . (is_writable($relatoriosPath) ? '✅' : '❌'));
        $this->newLine();

        // 3. Verificar link simbólico
        $this->info('🔗 Verificando link simbólico:');
        $publicStoragePath = public_path('storage');
        $this->line('- Public storage path: ' . $publicStoragePath);
        $this->line('  Existe: ' . (is_link($publicStoragePath) || is_dir($publicStoragePath) ? '✅' : '❌'));
        
        if (!is_link($publicStoragePath) && !is_dir($publicStoragePath)) {
            $this->warn('Link simbólico não existe. Execute: php artisan storage:link');
        }
        $this->newLine();

        // 4. Testar criação de arquivo
        $this->info('📝 Testando criação de arquivo:');
        try {
            $testFile = 'test_' . time() . '.txt';
            $testPath = 'relatorios/' . $testFile;
            
            Storage::disk('public')->put($testPath, 'Teste de escrita');
            
            if (Storage::disk('public')->exists($testPath)) {
                $this->line('✅ Criação de arquivo: OK');
                Storage::disk('public')->delete($testPath);
                $this->line('✅ Remoção de arquivo: OK');
            } else {
                $this->error('❌ Falha na criação de arquivo');
            }
        } catch (\Exception $e) {
            $this->error('❌ Erro ao testar arquivo: ' . $e->getMessage());
        }
        $this->newLine();

        // 5. Verificar espaço em disco
        $this->info('💾 Verificando espaço em disco:');
        $freeBytes = disk_free_space(storage_path());
        $totalBytes = disk_total_space(storage_path());
        
        if ($freeBytes && $totalBytes) {
            $freeGB = round($freeBytes / 1024 / 1024 / 1024, 2);
            $totalGB = round($totalBytes / 1024 / 1024 / 1024, 2);
            $usedPercent = round((($totalBytes - $freeBytes) / $totalBytes) * 100, 2);
            
            $this->line("- Espaço livre: {$freeGB} GB");
            $this->line("- Espaço total: {$totalGB} GB");
            $this->line("- Uso: {$usedPercent}%");
            
            if ($freeGB < 1) {
                $this->warn('⚠️ Pouco espaço em disco disponível!');
            }
        } else {
            $this->warn('Não foi possível verificar o espaço em disco');
        }
        $this->newLine();

        // 6. Verificar últimas imagens
        $this->info('🖼️ Verificando últimas imagens:');
        $ultimasImagens = RelatorioImagem::orderBy('data_upload', 'desc')->take(5)->get();
        
        if ($ultimasImagens->count() > 0) {
            foreach ($ultimasImagens as $imagem) {
                $existe = Storage::disk('public')->exists($imagem->caminho_arquivo);
                $status = $existe ? '✅' : '❌';
                $tamanho = $existe ? Storage::disk('public')->size($imagem->caminho_arquivo) : 0;
                $tamanhoMB = round($tamanho / 1024 / 1024, 2);
                
                $this->line("{$status} {$imagem->nome_original} ({$tamanhoMB}MB) - {$imagem->data_upload}");
            }
        } else {
            $this->line('Nenhuma imagem encontrada');
        }
        $this->newLine();

        // 7. Verificar configurações do Laravel
        $this->info('⚙️ Verificações do Laravel:');
        $this->line('- APP_ENV: ' . config('app.env'));
        $this->line('- APP_DEBUG: ' . (config('app.debug') ? 'true' : 'false'));
        $this->line('- FILESYSTEM_DISK: ' . config('filesystems.default'));
        $this->line('- APP_URL: ' . config('app.url'));
        $this->newLine();

        // 8. Sugerir soluções
        $this->info('💡 Possíveis soluções para problemas comuns:');
        $this->line('1. Se upload_max_filesize for menor que 7MB:');
        $this->line('   - Edite php.ini: upload_max_filesize = 10M');
        $this->line('   - Edite php.ini: post_max_size = 50M');
        $this->newLine();
        
        $this->line('2. Se há problemas de permissão:');
        $this->line('   - sudo chown -R www-data:www-data storage/');
        $this->line('   - sudo chmod -R 755 storage/');
        $this->newLine();
        
        $this->line('3. Se o link simbólico não existe:');
        $this->line('   - php artisan storage:link');
        $this->newLine();
        
        $this->line('4. Para problemas de timeout:');
        $this->line('   - Edite php.ini: max_execution_time = 300');
        $this->line('   - Edite php.ini: memory_limit = 256M');
        
        $this->info('✅ Diagnóstico concluído!');
        
        return 0;
    }
} 