<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RelatorioImagem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CorrigirCaminhosImagens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imagens:corrigir-caminhos {--dry-run : Mostrar o que seria feito sem executar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige os caminhos incorretos das imagens no banco de dados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 MODO DRY-RUN - Apenas simulando as mudanças...');
        }

        // Buscar todas as imagens com caminhos incorretos (que começam com C:\)
        $imagensProblematicas = RelatorioImagem::where('caminho_arquivo', 'like', 'C:\\%')->get();
        
        $this->info("📋 Encontradas {$imagensProblematicas->count()} imagens com caminhos incorretos");
        
        $corrigidas = 0;
        $problemas = 0;

        foreach ($imagensProblematicas as $imagem) {
            $caminhoAtual = $imagem->caminho_arquivo;
            $nomeArquivo = $imagem->nome_arquivo;
            
            // Determinar o novo caminho baseado no relatorio_id
            if ($imagem->relatorio_id) {
                if ($imagem->historico_id) {
                    // Imagem de histórico
                    $novoCaminho = "relatorios/{$imagem->relatorio_id}/historico/{$imagem->historico_id}/{$nomeArquivo}";
                } else {
                    // Imagem principal do relatório
                    $novoCaminho = "relatorios/{$imagem->relatorio_id}/{$nomeArquivo}";
                }
            } else {
                // Imagem sem relatório - usar pasta genérica
                $novoCaminho = "relatorios/temporarias/{$nomeArquivo}";
            }
            
            $this->line("ID {$imagem->id}: {$caminhoAtual} → {$novoCaminho}");
            
            if (!$dryRun) {
                try {
                    // Verificar se existe no novo caminho (caso já tenha sido corrigida manualmente)
                    if (Storage::disk('public')->exists($novoCaminho)) {
                        $imagem->update(['caminho_arquivo' => $novoCaminho]);
                        $this->info("✅ Corrigido (arquivo encontrado)");
                        $corrigidas++;
                    } else {
                        // Criar diretório se necessário
                        $diretorio = dirname($novoCaminho);
                        Storage::disk('public')->makeDirectory($diretorio);
                        
                        // Atualizar caminho no banco (mesmo que arquivo não exista fisicamente)
                        $imagem->update(['caminho_arquivo' => $novoCaminho]);
                        $this->warn("⚠️  Caminho corrigido no banco, mas arquivo físico não encontrado");
                        $problemas++;
                    }
                } catch (\Exception $e) {
                    $this->error("❌ Erro ao corrigir ID {$imagem->id}: {$e->getMessage()}");
                    $problemas++;
                }
            }
        }
        
        if (!$dryRun) {
            $this->info("\n📊 RESUMO:");
            $this->info("✅ Caminhos corrigidos: {$corrigidas}");
            if ($problemas > 0) {
                $this->warn("⚠️  Problemas encontrados: {$problemas}");
            }
            $this->info("🎉 Correção concluída!");
        } else {
            $this->info("\n💡 Execute sem --dry-run para aplicar as correções");
        }

        return 0;
    }
} 