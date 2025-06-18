<?php

namespace App\Console\Commands;

use App\Models\Equipamento;
use App\Models\Local;
use Illuminate\Console\Command;

class PopularEquipamentosTeste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'equipamentos:popular-teste';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popula a tabela de equipamentos com dados de teste';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populando equipamentos de teste...');

        // Buscar locais existentes
        $locais = Local::all();
        
        if ($locais->count() === 0) {
            $this->error('Nenhum local encontrado! Execute primeiro o comando de locais.');
            return Command::FAILURE;
        }

        $equipamentos = [
            [
                'nome' => 'Moinho Principal A1',
                'codigo' => 'MOI-001',
                'descricao' => 'Moinho principal para processamento de matéria prima',
                'local_id' => $locais->where('nome', 'Fábrica Principal')->first()->id ?? $locais->first()->id,
                'tipo' => 'Moinho',
                'fabricante' => 'ACME Industrial',
                'modelo' => 'MX-5000',
                'numero_serie' => 'AC001234567',
                'data_instalacao' => '2020-03-15',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Esteira Transportadora B2',
                'codigo' => 'EST-002',
                'descricao' => 'Esteira para transporte de materiais processados',
                'local_id' => $locais->where('nome', 'Fábrica Principal')->first()->id ?? $locais->first()->id,
                'tipo' => 'Esteira',
                'fabricante' => 'TechBelt',
                'modelo' => 'TB-2500',
                'numero_serie' => 'TB987654321',
                'data_instalacao' => '2021-08-10',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Britador Primário C1',
                'codigo' => 'BRI-003',
                'descricao' => 'Britador para fragmentação inicial de rochas',
                'local_id' => $locais->where('nome', 'Mina de Calcário')->first()->id ?? $locais->skip(1)->first()->id ?? $locais->first()->id,
                'tipo' => 'Britador',
                'fabricante' => 'CrushMaster',
                'modelo' => 'CM-800',
                'numero_serie' => 'CM555666777',
                'data_instalacao' => '2019-05-20',
                'status_operacional' => 'manutencao',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Compressor de Ar D4',
                'codigo' => 'COM-004',
                'descricao' => 'Compressor para sistema pneumático da fábrica',
                'local_id' => $locais->where('nome', 'Oficina de Manutenção')->first()->id ?? $locais->first()->id,
                'tipo' => 'Compressor',
                'fabricante' => 'AirMax',
                'modelo' => 'AM-300',
                'numero_serie' => 'AM123789456',
                'data_instalacao' => '2022-01-12',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Forno Industrial E5',
                'codigo' => 'FOR-005',
                'descricao' => 'Forno para processamento térmico de materiais',
                'local_id' => $locais->where('nome', 'Fábrica Principal')->first()->id ?? $locais->first()->id,
                'tipo' => 'Forno',
                'fabricante' => 'HeatTech',
                'modelo' => 'HT-1200',
                'numero_serie' => 'HT999888777',
                'data_instalacao' => '2018-11-30',
                'status_operacional' => 'inativo',
                'ativo' => false,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Bomba Centrífuga F6',
                'codigo' => 'BOM-006',
                'descricao' => 'Bomba para recirculação de água do sistema',
                'local_id' => $locais->first()->id,
                'tipo' => 'Bomba',
                'fabricante' => 'FlowMax',
                'modelo' => 'FM-150',
                'numero_serie' => 'FM456123789',
                'data_instalacao' => '2023-02-28',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Guindaste Móvel G7',
                'codigo' => 'GUI-007',
                'descricao' => 'Guindaste para movimentação de cargas pesadas',
                'local_id' => $locais->where('nome', 'Depósito')->first()->id ?? $locais->first()->id,
                'tipo' => 'Guindaste',
                'fabricante' => 'LiftCorp',
                'modelo' => 'LC-50T',
                'numero_serie' => 'LC147258369',
                'data_instalacao' => '2021-06-15',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Gerador Diesel H8',
                'codigo' => 'GER-008',
                'descricao' => 'Gerador de emergência para falhas de energia',
                'local_id' => $locais->first()->id,
                'tipo' => 'Gerador',
                'fabricante' => 'PowerGen',
                'modelo' => 'PG-500KW',
                'numero_serie' => 'PG789456123',
                'data_instalacao' => '2020-09-10',
                'status_operacional' => 'operando',
                'ativo' => true,
                'tenant_id' => 1
            ]
        ];

        $created = 0;
        
        foreach ($equipamentos as $equipamentoData) {
            // Verificar se já existe um equipamento com o mesmo código
            $existeEquipamento = Equipamento::where('codigo', $equipamentoData['codigo'])->first();
            
            if (!$existeEquipamento) {
                Equipamento::create($equipamentoData);
                $created++;
                $this->line("✓ Equipamento '{$equipamentoData['nome']}' criado");
            } else {
                $this->line("- Equipamento '{$equipamentoData['nome']}' já existe");
            }
        }

        $this->info("Concluído! {$created} equipamentos foram criados.");
        
        // Mostrar estatísticas
        $totalEquipamentos = Equipamento::count();
        $equipamentosAtivos = Equipamento::where('ativo', true)->count();
        $equipamentosInativos = Equipamento::where('ativo', false)->count();
        $equipamentosOperando = Equipamento::where('status_operacional', 'operando')->count();
        $equipamentosManutencao = Equipamento::where('status_operacional', 'manutencao')->count();
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de Equipamentos', $totalEquipamentos],
                ['Equipamentos Ativos', $equipamentosAtivos],
                ['Equipamentos Inativos', $equipamentosInativos],
                ['Operando', $equipamentosOperando],
                ['Em Manutenção', $equipamentosManutencao],
            ]
        );

        return Command::SUCCESS;
    }
}
