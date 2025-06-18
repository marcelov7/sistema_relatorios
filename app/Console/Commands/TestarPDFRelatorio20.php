<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Relatorio;
use Barryvdh\DomPDF\Facade\Pdf;

class TestarPDFRelatorio20 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pdf-relatorio-20';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa especificamente o PDF do relatório 20';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TESTE PDF RELATÓRIO #20 ===');
        
        $relatorio = Relatorio::with(['usuario', 'local', 'equipamento', 'imagens', 'historicos.usuario'])
            ->find(20);
            
        if (!$relatorio) {
            $this->error('Relatório #20 não encontrado.');
            return;
        }
        
        $this->info("Relatório encontrado: {$relatorio->titulo}");
        $this->info("Imagens: {$relatorio->imagens->count()}");
        
        // Testar cada imagem
        foreach ($relatorio->imagens as $index => $imagem) {
            $caminhoCompleto = storage_path('app/public/' . $imagem->caminho_arquivo);
            $existe = file_exists($caminhoCompleto);
            $tamanho = $existe ? filesize($caminhoCompleto) : 0;
            
            $this->line("Imagem " . ($index + 1) . ":");
            $this->line("  Arquivo: {$imagem->nome_arquivo}");
            $this->line("  Existe: " . ($existe ? 'SIM' : 'NÃO'));
            $this->line("  Tamanho: " . number_format($tamanho / 1024, 2) . " KB");
            $this->line("  Tipo: {$imagem->tipo_mime}");
            $this->line("  É imagem: " . ($imagem->isImagem() ? 'SIM' : 'NÃO'));
            
            if ($existe && $imagem->isImagem()) {
                if ($tamanho > 2 * 1024 * 1024) {
                    $this->warn("  AVISO: Imagem muito grande para PDF (será exibida mensagem)");
                } else {
                    $this->info("  OK: Imagem será incluída no PDF");
                }
            }
        }
        
        // Tentar gerar o PDF
        try {
            $this->info("\nTentando gerar PDF...");
            
            $pdf = Pdf::loadView('pdf.relatorio', compact('relatorio'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => false,
                ]);
            
            $filename = 'teste_relatorio_20_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            $caminhoPDF = storage_path('app/public/' . $filename);
            
            $pdf->save($caminhoPDF);
            
            $this->info("PDF gerado com sucesso!");
            $this->info("Arquivo salvo em: {$caminhoPDF}");
            $this->info("Tamanho do PDF: " . number_format(filesize($caminhoPDF) / 1024, 2) . " KB");
            
        } catch (\Exception $e) {
            $this->error("Erro ao gerar PDF: " . $e->getMessage());
            $this->error("Linha: " . $e->getLine());
            $this->error("Arquivo: " . $e->getFile());
        }
        
        return 0;
    }
}
