<?php

namespace App\Console\Commands;

use App\Models\Local;
use Illuminate\Console\Command;

class PopularLocaisTeste extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locais:popular-teste';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Popula a tabela de locais com dados de teste';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populando locais de teste...');

        $locais = [
            [
                'nome' => 'Fábrica Principal',
                'descricao' => 'Prédio principal da fábrica onde ocorre a produção de equipamentos industriais.',
                'endereco' => 'Rua Industrial, 123 - Setor B - Térreo',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Oficina de Manutenção',
                'descricao' => 'Local para manutenção e reparo de equipamentos diversos.',
                'endereco' => 'Setor B - Subsolo',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Depósito',
                'descricao' => 'Depósito de materiais e equipamentos diversos.',
                'endereco' => 'Setor C - Subsolo',
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Mina de Calcário',
                'descricao' => 'Área de extração de matéria prima para produção.',
                'endereco' => null,
                'ativo' => true,
                'tenant_id' => 1
            ],
            [
                'nome' => 'Sede TechCorp',
                'descricao' => 'Escritório principal da TechCorp onde ficam os serviços administrativos.',
                'endereco' => null,
                'ativo' => true,
                'tenant_id' => 2
            ],
            [
                'nome' => 'Sede Intercement',
                'descricao' => 'Local principal da Intercement para operações gerais.',
                'endereco' => 'Avenida Principal, 456 - Centro',
                'ativo' => true,
                'tenant_id' => 3
            ]
        ];

        $created = 0;
        
        foreach ($locais as $localData) {
            // Verificar se já existe um local com o mesmo nome
            $existeLocal = Local::where('nome', $localData['nome'])->first();
            
            if (!$existeLocal) {
                Local::create($localData);
                $created++;
                $this->line("✓ Local '{$localData['nome']}' criado");
            } else {
                $this->line("- Local '{$localData['nome']}' já existe");
            }
        }

        $this->info("Concluído! {$created} locais foram criados.");
        
        // Mostrar estatísticas
        $totalLocais = Local::count();
        $locaisAtivos = Local::where('ativo', true)->count();
        $locaisInativos = Local::where('ativo', false)->count();
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total de Locais', $totalLocais],
                ['Locais Ativos', $locaisAtivos],
                ['Locais Inativos', $locaisInativos],
            ]
        );

        return Command::SUCCESS;
    }
}
