<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Relatorio;
use App\Models\RelatorioImagem;
use Illuminate\Support\Facades\Storage;

class TestarImagens extends Command
{
    protected $signature = 'relatorios:testar-imagens';
    protected $description = 'Testa a funcionalidade de imagens dos relatórios';

    public function handle()
    {
        $this->info('🖼️  Testando funcionalidade de imagens dos relatórios...');
        $this->newLine();

        // Verificar se o storage está configurado
        $this->info('📂 Verificando configuração do storage...');
        
        if (!Storage::disk('public')->exists('')) {
            $this->error('❌ Diretório storage/app/public não existe');
            return;
        }

        if (!file_exists(public_path('storage'))) {
            $this->error('❌ Link simbólico public/storage não existe');
            $this->comment('Execute: php artisan storage:link');
            return;
        }

        $this->info('✅ Storage configurado corretamente');

        // Verificar relatórios existentes
        $totalRelatorios = Relatorio::count();
        $this->info("📊 Total de relatórios: {$totalRelatorios}");

        // Verificar imagens existentes
        $totalImagens = RelatorioImagem::count();
        $this->info("🖼️  Total de imagens: {$totalImagens}");

        if ($totalImagens > 0) {
            $this->newLine();
            $this->info('📋 Relatórios com imagens:');
            
            $relatoriosComImagens = Relatorio::has('imagens')->with('imagens')->get();
            
            foreach ($relatoriosComImagens as $relatorio) {
                $this->line("   • Relatório #{$relatorio->id}: {$relatorio->titulo}");
                $this->line("     Imagens: {$relatorio->imagens->count()}");
                
                foreach ($relatorio->imagens as $imagem) {
                    $exists = Storage::disk('public')->exists($imagem->caminho_arquivo);
                    $status = $exists ? '✅' : '❌';
                    $this->line("     {$status} {$imagem->nome_original} ({$imagem->tamanho_formatado})");
                }
            }
        }

        // Verificar URLs das imagens
        $this->newLine();
        $this->info('🔗 Testando URLs das imagens...');
        
        $imagens = RelatorioImagem::take(3)->get();
        foreach ($imagens as $imagem) {
            $url = $imagem->url;
            if ($url) {
                $this->info("✅ URL gerada: {$url}");
            } else {
                $this->error("❌ Erro ao gerar URL para: {$imagem->nome_original}");
            }
        }

        $this->newLine();
        $this->info('✨ Teste concluído!');
        $this->comment('Acesse: http://127.0.0.1:8000/relatorios/create para testar upload');
    }
} 