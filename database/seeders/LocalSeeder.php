<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Local;
use Carbon\Carbon;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locais = [
            [
                'nome' => 'Escritório Principal',
                'descricao' => 'Sede da empresa, onde ficam os departamentos administrativos',
                'endereco' => 'Rua das Flores, 123 - Centro',
                'ativo' => true,
                'tenant_id' => 1,
                'data_criacao' => Carbon::now(),
                'data_atualizacao' => Carbon::now(),
            ],
            [
                'nome' => 'Depósito Central',
                'descricao' => 'Depósito principal para armazenamento de equipamentos',
                'endereco' => 'Av. Industrial, 456 - Distrito Industrial',
                'ativo' => true,
                'tenant_id' => 1,
                'data_criacao' => Carbon::now()->subDays(5),
                'data_atualizacao' => Carbon::now()->subDays(5),
            ],
            [
                'nome' => 'Filial Norte',
                'descricao' => 'Filial responsável pela região norte da cidade',
                'endereco' => 'Rua do Comércio, 789 - Zona Norte',
                'ativo' => true,
                'tenant_id' => 1,
                'data_criacao' => Carbon::now()->subDays(10),
                'data_atualizacao' => Carbon::now()->subDays(10),
            ],
            [
                'nome' => 'Laboratório',
                'descricao' => 'Laboratório de testes e desenvolvimento',
                'endereco' => 'Rua da Tecnologia, 321 - Campus Universitário',
                'ativo' => false,
                'tenant_id' => 1,
                'data_criacao' => Carbon::now()->subDays(15),
                'data_atualizacao' => Carbon::now()->subDays(1),
            ],
        ];

        foreach ($locais as $localData) {
            Local::create($localData);
        }
    }
}
