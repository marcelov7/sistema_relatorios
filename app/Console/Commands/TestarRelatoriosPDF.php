<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Relatorio;
use App\Http\Controllers\PDFController;
use Illuminate\Http\Request;

class TestarRelatoriosPDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:relatorios-pdf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a geração de PDFs de relatórios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TESTE DE PDFs DE RELATÓRIOS ===');
        
        // Buscar alguns relatórios para teste
        $relatorios = Relatorio::with(['usuario', 'local', 'equipamento', 'imagens', 'historicos.usuario'])
            ->limit(3)
            ->get();
            
        if ($relatorios->isEmpty()) {
            $this->error('Nenhum relatório encontrado para teste.');
            return;
        }
        
        $this->info("Encontrados {$relatorios->count()} relatórios para teste:");
        
        foreach ($relatorios as $relatorio) {
            $this->line("- Relatório #{$relatorio->id}: {$relatorio->titulo}");
            $this->line("  Status: {$relatorio->status}");
            $this->line("  Criado em: " . ($relatorio->data_criacao ? $relatorio->data_criacao->format('d/m/Y H:i') : 'N/A'));
            $this->line("  Atualizado em: " . ($relatorio->data_atualizacao ? $relatorio->data_atualizacao->format('d/m/Y H:i') : 'N/A'));
            $this->line("  Históricos: {$relatorio->historicos->count()}");
            $this->line("  Imagens: {$relatorio->imagens->count()}");
            
            // Testar imagens
            if ($relatorio->imagens->count() > 0) {
                $this->line("  Detalhes das imagens:");
                foreach ($relatorio->imagens as $index => $imagem) {
                    $caminhoCompleto = storage_path('app/public/' . $imagem->caminho_arquivo);
                    $existe = file_exists($caminhoCompleto);
                    $tamanho = $existe ? filesize($caminhoCompleto) : 0;
                    
                    $this->line("    Imagem " . ($index + 1) . ":");
                    $this->line("      Arquivo: {$imagem->nome_arquivo}");
                    $this->line("      Caminho: {$imagem->caminho_arquivo}");
                    $this->line("      Existe: " . ($existe ? 'SIM' : 'NÃO'));
                    $this->line("      Tamanho: " . number_format($tamanho / 1024, 2) . " KB");
                    $this->line("      Tipo MIME: {$imagem->tipo_mime}");
                    $this->line("      É imagem: " . ($imagem->isImagem() ? 'SIM' : 'NÃO'));
                }
            }
            
            // Testar cálculo de tempo
            if ($relatorio->status === 'resolvido' && $relatorio->data_criacao && $relatorio->data_atualizacao) {
                $tempoTotal = $relatorio->data_criacao->diffInDays($relatorio->data_atualizacao);
                $tempoHoras = $relatorio->data_criacao->diffInHours($relatorio->data_atualizacao) % 24;
                $this->line("  Tempo total: {$tempoTotal} dia(s) e {$tempoHoras} hora(s)");
            }
            
            $this->line('');
        }
        
        $this->info('Teste de dados concluído com sucesso!');
        $this->info('Para testar a geração do PDF, acesse: /pdf/relatorio/{id}');
        
        return 0;
    }
}
