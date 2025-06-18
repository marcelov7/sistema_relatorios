<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Relatorio;

class BuscarRelatoriosComImagens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:relatorios-imagens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca relatórios que têm imagens anexadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== RELATÓRIOS COM IMAGENS ===');
        
        $relatorios = Relatorio::has('imagens')->with('imagens')->get();
        
        if ($relatorios->isEmpty()) {
            $this->error('Nenhum relatório com imagens encontrado.');
            return;
        }
        
        $this->info("Encontrados {$relatorios->count()} relatórios com imagens:");
        
        foreach ($relatorios as $relatorio) {
            $this->line("- Relatório #{$relatorio->id}: {$relatorio->titulo}");
            $this->line("  Imagens: {$relatorio->imagens->count()}");
            
            foreach ($relatorio->imagens as $index => $imagem) {
                $caminhoCompleto = storage_path('app/public/' . $imagem->caminho_arquivo);
                $existe = file_exists($caminhoCompleto);
                
                $this->line("    Imagem " . ($index + 1) . ": {$imagem->nome_arquivo}");
                $this->line("      Caminho: {$imagem->caminho_arquivo}");
                $this->line("      Existe: " . ($existe ? 'SIM' : 'NÃO'));
                if ($existe) {
                    $tamanho = filesize($caminhoCompleto);
                    $this->line("      Tamanho: " . number_format($tamanho / 1024, 2) . " KB");
                }
            }
            $this->line('');
        }
        
        return 0;
    }
}
