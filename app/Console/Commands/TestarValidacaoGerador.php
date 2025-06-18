<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InspecaoGerador;
use App\Services\GeradorParametrosService;

class TestarValidacaoGerador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gerador:testar-validacao {--criar-exemplo : Criar inspeção de exemplo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de validação automática de parâmetros do gerador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testando Sistema de Validação de Parâmetros do Gerador');
        $this->newLine();

        if ($this->option('criar-exemplo')) {
            $this->criarInspecaoExemplo();
            return;
        }

        // Mostrar parâmetros normais
        $this->mostrarParametrosNormais();
        
        // Testar validação individual
        $this->testarValidacaoIndividual();
        
        // Testar com inspeções existentes
        $this->testarInspecoesExistentes();
    }

    private function mostrarParametrosNormais()
    {
        $this->info('📋 Parâmetros Normais Configurados:');
        $this->newLine();

        $parametros = GeradorParametrosService::getParametrosNormais();
        
        $headers = ['Parâmetro', 'Mín', 'Máx', 'Ideal', 'Unidade'];
        $rows = [];

        foreach ($parametros as $campo => $config) {
            $rows[] = [
                $config['nome'],
                $config['min'],
                $config['max'],
                $config['ideal'],
                $config['unidade']
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
    }

    private function testarValidacaoIndividual()
    {
        $this->info('🧪 Testando Validação Individual:');
        $this->newLine();

        $testes = [
            // Tensões normais
            ['tensao_sync_gerador', 12.0, 'Normal'],
            ['tensao_a', 227.0, 'Normal'],
            ['tensao_bateria', 24.0, 'Normal'],
            
            // Valores em atenção
            ['tensao_sync_gerador', 10.5, 'Atenção'],
            ['temp_agua', 92.0, 'Atenção'],
            
            // Valores anormais
            ['tensao_b', 1.0, 'Anormal/Crítico'],
            ['pressao_oleo', 1.5, 'Crítico'],
            ['temp_agua', 98.0, 'Crítico'],
            
            // Valores fora da faixa
            ['frequencia', 55.0, 'Anormal'],
            ['rpm', 2000, 'Anormal'],
        ];

        $headers = ['Parâmetro', 'Valor', 'Status', 'Mensagem'];
        $rows = [];

        foreach ($testes as $teste) {
            [$parametro, $valor, $esperado] = $teste;
            $validacao = GeradorParametrosService::validarParametro($parametro, $valor);
            
            $rows[] = [
                $parametro,
                $valor,
                strtoupper($validacao['status']),
                $validacao['mensagem']
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();
    }

    private function testarInspecoesExistentes()
    {
        $this->info('📊 Testando Inspeções Existentes:');
        $this->newLine();

        $inspecoes = InspecaoGerador::latest()->take(5)->get();

        if ($inspecoes->isEmpty()) {
            $this->warn('Nenhuma inspeção encontrada. Use --criar-exemplo para criar uma inspeção de teste.');
            return;
        }

        $headers = ['ID', 'Data', 'Normal', 'Atenção', 'Anormal', 'Crítico', 'Status Geral'];
        $rows = [];

        foreach ($inspecoes as $inspecao) {
            $validacao = $inspecao->validacao_parametros;
            $statusGeral = $validacao['status_geral'];
            
            $rows[] = [
                $inspecao->id,
                $inspecao->data_formatada,
                $validacao['resumo']['normal'],
                $validacao['resumo']['atencao'],
                $validacao['resumo']['anormal'],
                $validacao['resumo']['critico'],
                strtoupper($statusGeral['status'])
            ];
        }

        $this->table($headers, $rows);
        
        // Mostrar recomendações da primeira inspeção
        $primeira = $inspecoes->first();
        $recomendacoes = $primeira->recomendacoes;
        
        if (!empty($recomendacoes)) {
            $this->newLine();
            $this->info("🔧 Recomendações para Inspeção #{$primeira->id}:");
            
            foreach ($recomendacoes as $rec) {
                $cor = $rec['prioridade'] === 'alta' ? 'red' : ($rec['prioridade'] === 'media' ? 'yellow' : 'blue');
                $this->line("  <fg={$cor}>[{$rec['prioridade']}]</> {$rec['parametro']}: {$rec['acao']}");
            }
        }
        
        $this->newLine();
    }

    private function criarInspecaoExemplo()
    {
        $this->info('🏗️ Criando Inspeção de Exemplo com Parâmetros Variados...');
        
        $inspecao = InspecaoGerador::create([
            'data' => now()->format('Y-m-d'),
            'colaborador' => 'Sistema de Teste',
            'nivel_oleo' => 'Normal',
            'nivel_agua' => 'Normal',
            
            // Tensões - algumas normais, outras não
            'tensao_sync_gerador' => 12.0,  // Normal
            'tensao_sync_rede' => 12.0,     // Normal
            'tensao_a' => 227.0,            // Normal
            'tensao_b' => 1.0,              // Crítico (muito baixo)
            'tensao_c' => 225.0,            // Normal
            'tensao_bateria' => 18.0,       // Atenção (baixo)
            'tensao_alternador' => 111.0,   // Normal
            
            // Medições - algumas problemáticas
            'temp_agua' => 96.0,            // Crítico (muito alto)
            'pressao_oleo' => 2.5,          // Atenção (baixo)
            'frequencia' => 59.8,           // Normal
            'rpm' => 1800,                  // Normal
            
            'combustivel_50' => 'Sim',
            'iluminacao_sala' => 'Normal',
            'observacao' => 'Inspeção de exemplo criada pelo sistema de teste para demonstrar a validação automática de parâmetros.',
            'ativo' => true
        ]);

        $this->info("✅ Inspeção de exemplo criada com ID: {$inspecao->id}");
        $this->newLine();

        // Mostrar análise da inspeção criada
        $validacao = $inspecao->validacao_parametros;
        $statusGeral = $validacao['status_geral'];
        
        $this->info('📊 Análise da Inspeção Criada:');
        $this->line("Status Geral: <fg=red>{$statusGeral['mensagem']}</>");
        $this->line("Parâmetros Normais: {$validacao['resumo']['normal']}");
        $this->line("Requer Atenção: {$validacao['resumo']['atencao']}");
        $this->line("Anormais: {$validacao['resumo']['anormal']}");
        $this->line("Críticos: {$validacao['resumo']['critico']}");
        
        $recomendacoes = $inspecao->recomendacoes;
        if (!empty($recomendacoes)) {
            $this->newLine();
            $this->info('🔧 Recomendações Geradas:');
            foreach ($recomendacoes as $rec) {
                $cor = $rec['prioridade'] === 'alta' ? 'red' : ($rec['prioridade'] === 'media' ? 'yellow' : 'blue');
                $this->line("  <fg={$cor}>[{$rec['prioridade']}]</> {$rec['parametro']}: {$rec['acao']}");
            }
        }
        
        $this->newLine();
        $this->info("🌐 Acesse: " . route('inspecoes-gerador.show', $inspecao));
    }
}
