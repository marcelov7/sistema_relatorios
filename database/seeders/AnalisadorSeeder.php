<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Analisador;
use App\Models\User;

class AnalisadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar usuários existentes
        $usuarios = User::where('ativo', true)->get();
        
        if ($usuarios->isEmpty()) {
            $this->command->warn('Nenhum usuário ativo encontrado. Execute primeiro o UserSeeder.');
            return;
        }

        $tiposAnalisadores = ['TORRE', 'CHAMINE', 'CAIXA DE FUMAÇA'];
        
        $analisadores = [
            [
                'analyzer' => 'TORRE',
                'check_date' => now()->subDays(5),
                'acid_filter' => true,
                'gas_dryer' => true,
                'paper_filter' => true,
                'peristaltic_pump' => true,
                'rotameter' => true,
                'disposable_filter' => true,
                'blocking_filter' => true,
                'room_temperature' => 25.5,
                'air_pressure' => 1.01,
                'observation' => 'Verificação completa realizada. Todos os componentes funcionando perfeitamente.',
                'ativo' => true,
            ],
            [
                'analyzer' => 'CHAMINE',
                'check_date' => now()->subDays(3),
                'acid_filter' => true,
                'gas_dryer' => false,
                'paper_filter' => true,
                'peristaltic_pump' => true,
                'rotameter' => true,
                'disposable_filter' => true,
                'blocking_filter' => true,
                'room_temperature' => 28.2,
                'air_pressure' => 0.98,
                'observation' => 'Secador de gás apresentando problemas. Necessária manutenção urgente.',
                'ativo' => true,
            ],
            [
                'analyzer' => 'CAIXA DE FUMAÇA',
                'check_date' => now()->subDays(1),
                'acid_filter' => true,
                'gas_dryer' => true,
                'paper_filter' => false,
                'peristaltic_pump' => false,
                'rotameter' => true,
                'disposable_filter' => true,
                'blocking_filter' => true,
                'room_temperature' => 22.8,
                'air_pressure' => 1.05,
                'observation' => 'Filtro de papel e bomba peristáltica precisam ser substituídos.',
                'ativo' => true,
            ],
            [
                'analyzer' => 'TORRE',
                'check_date' => now()->subDays(7),
                'acid_filter' => true,
                'gas_dryer' => true,
                'paper_filter' => true,
                'peristaltic_pump' => true,
                'rotameter' => true,
                'disposable_filter' => true,
                'blocking_filter' => true,
                'room_temperature' => 24.1,
                'air_pressure' => 1.02,
                'observation' => 'Manutenção preventiva realizada com sucesso.',
                'ativo' => true,
            ],
            [
                'analyzer' => 'CHAMINE',
                'check_date' => now()->subDays(10),
                'acid_filter' => false,
                'gas_dryer' => true,
                'paper_filter' => true,
                'peristaltic_pump' => true,
                'rotameter' => false,
                'disposable_filter' => true,
                'blocking_filter' => false,
                'room_temperature' => 30.5,
                'air_pressure' => 0.95,
                'observation' => 'Múltiplos componentes com falha. Analisador temporariamente desativado.',
                'ativo' => false,
            ],
            [
                'analyzer' => 'TORRE',
                'check_date' => now(),
                'acid_filter' => true,
                'gas_dryer' => true,
                'paper_filter' => true,
                'peristaltic_pump' => true,
                'rotameter' => true,
                'disposable_filter' => true,
                'blocking_filter' => true,
                'room_temperature' => 26.0,
                'air_pressure' => 1.00,
                'observation' => 'Verificação diária - todos os sistemas operacionais.',
                'ativo' => true,
            ],
        ];

        foreach ($analisadores as $analisadorData) {
            // Atribuir usuário aleatório
            $analisadorData['user_id'] = $usuarios->random()->id;
            $analisadorData['tenant_id'] = 1; // Temporário
            
            Analisador::create($analisadorData);
        }

        $this->command->info('Analisadores criados com sucesso!');
        $this->command->info('Total de analisadores: ' . Analisador::count());
        $this->command->info('Analisadores ativos: ' . Analisador::where('ativo', true)->count());
        $this->command->info('Analisadores com problemas: ' . Analisador::where(function($query) {
            $query->where('acid_filter', false)
                  ->orWhere('gas_dryer', false)
                  ->orWhere('paper_filter', false)
                  ->orWhere('peristaltic_pump', false)
                  ->orWhere('rotameter', false)
                  ->orWhere('disposable_filter', false)
                  ->orWhere('blocking_filter', false);
        })->count());
    }
}
