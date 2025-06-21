<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class CriarTabelaRelatorioItens extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'relatorio:criar-tabela-itens';

    /**
     * The console command description.
     */
    protected $description = 'Criar a tabela relatorio_itens se ela não existir';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Schema::hasTable('relatorio_itens')) {
            $this->info('Tabela relatorio_itens já existe.');
            return 0;
        }

        $this->info('Criando tabela relatorio_itens...');

        try {
            // Verificar se as tabelas dependentes existem
            if (!Schema::hasTable('relatorios')) {
                $this->error('Tabela relatorios não encontrada. Execute as migrations primeiro.');
                return 1;
            }
            
            if (!Schema::hasTable('equipamentos')) {
                $this->error('Tabela equipamentos não encontrada. Execute as migrations primeiro.');
                return 1;
            }

            Schema::create('relatorio_itens', function (Blueprint $table) {
                $table->id();
                $table->integer('relatorio_id')->unsigned();
                $table->integer('equipamento_id')->unsigned();
                $table->text('descricao_equipamento');
                $table->text('observacoes')->nullable();
                $table->enum('status_item', ['pendente', 'em_andamento', 'concluido'])->default('pendente');
                $table->integer('ordem')->default(1);
                $table->timestamps();
                
                // Índices para performance
                $table->index(['relatorio_id', 'ordem']);
                $table->index('equipamento_id');
            });

            // Adicionar foreign keys em separado
            try {
                Schema::table('relatorio_itens', function (Blueprint $table) {
                    $table->foreign('relatorio_id')->references('id')->on('relatorios')->onDelete('cascade');
                    $table->foreign('equipamento_id')->references('id')->on('equipamentos');
                });
                $this->info('Foreign keys criadas com sucesso.');
            } catch (\Exception $e) {
                $this->warn('Não foi possível criar foreign keys: ' . $e->getMessage());
            }

            $this->info('Tabela relatorio_itens criada com sucesso!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Erro ao criar tabela: ' . $e->getMessage());
            return 1;
        }
    }
} 