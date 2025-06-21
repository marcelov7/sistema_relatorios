<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\RelatorioImagem;
use App\Models\Relatorio;

class DiagnosticarUploadImagens extends Command
{
    protected $signature = 'relatorio:diagnosticar-upload';
    protected $description = 'Diagnostica problemas de upload de imagens em relatórios';

    public function handle()
    {
        $this->info('Iniciando diagnóstico de upload de imagens...');
        
        // Verificar últimos relatórios criados
        $ultimosRelatorios = Relatorio::with('imagens')
            ->orderBy('data_criacao', 'desc')
            ->take(10)
            ->get();
            
        $this->info("Analisando os últimos 10 relatórios:");
        
        foreach ($ultimosRelatorios as $relatorio) {
            $totalImagens = $relatorio->imagens->count();
            $this->line("Relatório #{$relatorio->id} - {$relatorio->titulo}");
            $this->line("  Criado em: {$relatorio->data_criacao}");
            $this->line("  Total de imagens: {$totalImagens}");
            
            if ($totalImagens > 0) {
                foreach ($relatorio->imagens as $imagem) {
                    $existeArquivo = Storage::disk('public')->exists($imagem->caminho_arquivo);
                    $status = $existeArquivo ? '✓' : '✗';
                    $this->line("    {$status} {$imagem->nome_original} - {$imagem->caminho_arquivo}");
                }
            }
            $this->line('');
        }
        
        // Verificar permissões do diretório de storage
        $this->info('Verificando permissões dos diretórios:');
        
        $directorios = [
            'storage/app/public',
            'storage/app/public/relatorios',
            'public/storage'
        ];
        
        foreach ($directorios as $dir) {
            $fullPath = base_path($dir);
            if (is_dir($fullPath)) {
                $permissions = substr(sprintf('%o', fileperms($fullPath)), -4);
                $writable = is_writable($fullPath) ? '✓' : '✗';
                $this->line("{$writable} {$dir} - Permissões: {$permissions}");
            } else {
                $this->line("✗ {$dir} - Diretório não existe");
            }
        }
        
        // Verificar se o link simbólico existe
        $this->info('Verificando link simbólico:');
        $linkPath = public_path('storage');
        if (is_link($linkPath)) {
            $this->line("✓ Link simbólico existe: " . readlink($linkPath));
        } else {
            $this->line("✗ Link simbólico não existe em: {$linkPath}");
            $this->warn("Execute: php artisan storage:link");
        }
        
        $this->info('Diagnóstico concluído!');
    }
} 