<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CorrigirImagensV2 extends Command
{
    protected $signature = 'relatorio:corrigir-imagens-v2';
    protected $description = 'Corrige problemas com upload de imagens no sistema V2';

    public function handle()
    {
        $this->info('🔧 Verificando e corrigindo problemas com imagens V2...');
        
        // Verificar se a tabela relatorio_imagens existe
        if (!Schema::hasTable('relatorio_imagens')) {
            $this->error('❌ Tabela relatorio_imagens não existe!');
            return 1;
        }
        
        // Verificar estrutura da tabela
        $this->info('📋 Verificando estrutura da tabela relatorio_imagens...');
        
        $columns = Schema::getColumnListing('relatorio_imagens');
        $this->line('Colunas encontradas: ' . implode(', ', $columns));
        
        // Verificar campos obrigatórios
        $requiredFields = ['id', 'relatorio_id', 'nome_arquivo', 'nome_original', 'caminho_arquivo', 'tamanho_arquivo', 'tipo_mime'];
        $missingFields = array_diff($requiredFields, $columns);
        
        if (!empty($missingFields)) {
            $this->error('❌ Campos obrigatórios não encontrados: ' . implode(', ', $missingFields));
            
            $this->info('🔨 Tentando adicionar campos faltantes...');
            
            try {
                Schema::table('relatorio_imagens', function ($table) use ($missingFields) {
                    foreach ($missingFields as $field) {
                        switch ($field) {
                            case 'caminho_arquivo':
                                if (!Schema::hasColumn('relatorio_imagens', 'caminho_arquivo')) {
                                    $table->string('caminho_arquivo')->nullable();
                                }
                                break;
                            case 'tamanho_arquivo':
                                if (!Schema::hasColumn('relatorio_imagens', 'tamanho_arquivo')) {
                                    $table->integer('tamanho_arquivo')->nullable();
                                }
                                break;
                            case 'tipo_mime':
                                if (!Schema::hasColumn('relatorio_imagens', 'tipo_mime')) {
                                    $table->string('tipo_mime')->nullable();
                                }
                                break;
                            case 'tenant_id':
                                if (!Schema::hasColumn('relatorio_imagens', 'tenant_id')) {
                                    $table->integer('tenant_id')->default(1);
                                }
                                break;
                        }
                    }
                });
                
                $this->info('✅ Campos adicionados com sucesso!');
                
            } catch (\Exception $e) {
                $this->error('❌ Erro ao adicionar campos: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('✅ Todos os campos obrigatórios estão presentes!');
        }
        
        // Verificar registros com problemas
        $this->info('🔍 Verificando registros com problemas...');
        
        $problemRecords = DB::table('relatorio_imagens')
            ->whereNull('caminho_arquivo')
            ->orWhereNull('tamanho_arquivo')
            ->orWhereNull('tipo_mime')
            ->count();
        
        if ($problemRecords > 0) {
            $this->warn("⚠️  Encontrados {$problemRecords} registros com campos nulos");
            
            if ($this->confirm('Deseja tentar corrigir os registros com problemas?')) {
                $this->info('🔧 Corrigindo registros...');
                
                // Tentar corrigir registros onde caminho_arquivo está nulo mas nome_arquivo existe
                DB::table('relatorio_imagens')
                    ->whereNull('caminho_arquivo')
                    ->whereNotNull('nome_arquivo')
                    ->update([
                        'caminho_arquivo' => DB::raw("CONCAT('relatorios/', nome_arquivo)"),
                        'tamanho_arquivo' => DB::raw('COALESCE(tamanho_arquivo, 0)'),
                        'tipo_mime' => DB::raw("COALESCE(tipo_mime, 'image/jpeg')")
                    ]);
                
                $this->info('✅ Registros corrigidos!');
            }
        } else {
            $this->info('✅ Nenhum registro com problemas encontrado!');
        }
        
        // Verificar se há relatórios V2 (com itens)
        if (Schema::hasTable('relatorio_itens')) {
            $relatoriosV2 = DB::table('relatorio_itens')->distinct('relatorio_id')->count();
            $this->info("📊 Encontrados {$relatoriosV2} relatórios V2 no sistema");
        }
        
        $this->info('🎉 Verificação concluída!');
        
        return 0;
    }
} 