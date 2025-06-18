<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RelatorioImagem;
use Illuminate\Support\Facades\Storage;

class LimparImagensOrfas extends Command
{
    protected $signature = 'relatorios:limpar-imagens-orfas';
    protected $description = 'Remove imagens do banco que não possuem arquivos físicos';

    public function handle()
    {
        $this->info('🧹 Limpando imagens órfãs...');
        $this->newLine();

        $imagens = RelatorioImagem::all();
        $removidas = 0;

        foreach ($imagens as $imagem) {
            if (!Storage::disk('public')->exists($imagem->caminho_arquivo)) {
                $this->line("❌ Removendo: {$imagem->nome_original}");
                $imagem->delete();
                $removidas++;
            } else {
                $this->line("✅ OK: {$imagem->nome_original}");
            }
        }

        $this->newLine();
        $this->info("✨ Limpeza concluída! {$removidas} imagens órfãs removidas.");
    }
} 