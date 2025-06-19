<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RelatorioImagem;
use Illuminate\Support\Facades\Storage;

class VerificarImagens extends Command
{
    protected $signature = 'imagens:verificar';
    protected $description = 'Verifica todas as imagens no banco de dados';

    public function handle()
    {
        $this->info('🔍 Verificando todas as imagens...');
        
        $imagens = RelatorioImagem::all();
        
        $this->info("📋 Total de imagens no banco: {$imagens->count()}");
        
        $problematicas = 0;
        $corretas = 0;
        $naoEncontradas = 0;
        
        foreach ($imagens as $imagem) {
            $this->line("ID {$imagem->id}: {$imagem->nome_arquivo}");
            $this->line("  Relatório: {$imagem->relatorio_id}");
            $this->line("  Caminho: {$imagem->caminho_arquivo}");
            
            // Verificar se é caminho problemático
            if (str_contains($imagem->caminho_arquivo, 'xampp') || 
                str_contains($imagem->caminho_arquivo, 'DevNodjs') ||
                str_contains($imagem->caminho_arquivo, 'C:\\')) {
                $this->error("  ❌ CAMINHO PROBLEMÁTICO!");
                $problematicas++;
            } else {
                // Verificar se arquivo existe
                if (Storage::disk('public')->exists($imagem->caminho_arquivo)) {
                    $this->info("  ✅ Arquivo encontrado");
                    $corretas++;
                } else {
                    $this->warn("  ⚠️  Arquivo não encontrado no storage");
                    $naoEncontradas++;
                }
            }
            
            $this->line("");
        }
        
        $this->info("📊 RESUMO:");
        $this->info("✅ Imagens corretas e encontradas: {$corretas}");
        $this->warn("⚠️  Imagens corretas mas não encontradas: {$naoEncontradas}");
        $this->error("❌ Imagens com caminhos problemáticos: {$problematicas}");
        
        return 0;
    }
} 