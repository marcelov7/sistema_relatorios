<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Relatorio;
use App\Models\Notificacao;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criando roles
        $adminRole = Role::create(['name' => 'admin']);
        $usuarioRole = Role::create(['name' => 'usuario']);
        $supervisorRole = Role::create(['name' => 'supervisor']);

        // Criando permissions
        Permission::create(['name' => 'gerenciar_usuarios']);
        Permission::create(['name' => 'gerenciar_relatorios']);
        Permission::create(['name' => 'visualizar_relatorios']);
        Permission::create(['name' => 'criar_relatorios']);
        Permission::create(['name' => 'editar_relatorios']);
        Permission::create(['name' => 'deletar_relatorios']);
        Permission::create(['name' => 'gerar_pdfs']);
        Permission::create(['name' => 'gerenciar_notificacoes']);

        // Atribuindo permissions aos roles
        $adminRole->givePermissionTo([
            'gerenciar_usuarios',
            'gerenciar_relatorios',
            'visualizar_relatorios',
            'criar_relatorios',
            'editar_relatorios',
            'deletar_relatorios',
            'gerar_pdfs',
            'gerenciar_notificacoes'
        ]);

        $supervisorRole->givePermissionTo([
            'visualizar_relatorios',
            'criar_relatorios',
            'editar_relatorios',
            'gerar_pdfs'
        ]);

        $usuarioRole->givePermissionTo([
            'visualizar_relatorios',
            'criar_relatorios',
            'gerar_pdfs'
        ]);

        // Criando usuários de teste
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => 'password',
            'cargo' => 'Administrador do Sistema',
            'departamento' => 'TI',
            'ativo' => true,
            'configuracoes_notificacao' => json_encode([
                'relatorio_criado' => true,
                'relatorio_atualizado' => true,
                'relatorio_concluido' => true,
                'relatorio_atribuido' => true,
                'sistema' => true,
            ])
        ]);
        $admin->assignRole('admin');

        $supervisor = User::create([
            'name' => 'João Silva',
            'email' => 'supervisor@sistema.com',
            'password' => 'password',
            'cargo' => 'Supervisor de Manutenção',
            'departamento' => 'Manutenção',
            'telefone' => '(11) 98765-4321',
            'ativo' => true,
            'configuracoes_notificacao' => json_encode([
                'relatorio_criado' => true,
                'relatorio_atualizado' => true,
                'relatorio_concluido' => true,
                'relatorio_atribuido' => true,
                'sistema' => false,
            ])
        ]);
        $supervisor->assignRole('supervisor');

        $usuario1 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@sistema.com',
            'password' => 'password',
            'cargo' => 'Técnica de Manutenção',
            'departamento' => 'Manutenção',
            'telefone' => '(11) 91234-5678',
            'ativo' => true,
            'configuracoes_notificacao' => json_encode([
                'relatorio_criado' => false,
                'relatorio_atualizado' => true,
                'relatorio_concluido' => true,
                'relatorio_atribuido' => true,
                'sistema' => false,
            ])
        ]);
        $usuario1->assignRole('usuario');

        $usuario2 = User::create([
            'name' => 'Carlos Oliveira',
            'email' => 'carlos@sistema.com',
            'password' => 'password',
            'cargo' => 'Mecânico Industrial',
            'departamento' => 'Manutenção',
            'telefone' => '(11) 95555-5555',
            'ativo' => true,
            'configuracoes_notificacao' => json_encode([
                'relatorio_criado' => false,
                'relatorio_atualizado' => true,
                'relatorio_concluido' => false,
                'relatorio_atribuido' => true,
                'sistema' => false,
            ])
        ]);
        $usuario2->assignRole('usuario');

        // Criando relatórios de teste
        $relatorio1 = Relatorio::create([
            'titulo' => 'Manutenção Preventiva - Compressor 01',
            'tipo' => 'preventiva',
            'descricao' => 'Verificação geral do compressor de ar principal, incluindo troca de filtros e verificação de pressão.',
            'status' => 'em_andamento',
            'prioridade' => 'media',
            'equipamento' => 'Compressor Atlas Copco GA22',
            'local' => 'Área de Produção - Setor A',
            'data_inicio' => now()->subDays(2),
            'data_fim' => now()->addDays(1),
            'custo_estimado' => 350.00,
            'observacoes' => 'Equipamento apresentou ruído anormal na última semana.',
            'criado_por' => $supervisor->id,
        ]);

        $relatorio2 = Relatorio::create([
            'titulo' => 'Correção - Motor Esteira Transportadora',
            'tipo' => 'corretiva',
            'descricao' => 'Motor da esteira transportadora principal apresentou falha. Necessário diagnóstico e reparo.',
            'status' => 'pendente',
            'prioridade' => 'alta',
            'equipamento' => 'Motor WEG 15CV',
            'local' => 'Linha de Produção 2',
            'data_inicio' => now(),
            'custo_estimado' => 800.00,
            'observacoes' => 'Produção impactada. Prioridade alta.',
            'criado_por' => $usuario1->id,
        ]);

        $relatorio3 = Relatorio::create([
            'titulo' => 'Inspeção Mensal - Sistema Hidráulico',
            'tipo' => 'inspecao',
            'descricao' => 'Inspeção mensal obrigatória do sistema hidráulico conforme normas de segurança.',
            'status' => 'concluido',
            'prioridade' => 'media',
            'equipamento' => 'Central Hidráulica Parker',
            'local' => 'Sala de Máquinas',
            'data_inicio' => now()->subDays(5),
            'data_fim' => now()->subDays(3),
            'custo_estimado' => 150.00,
            'custo_real' => 120.00,
            'observacoes' => 'Sistema operando dentro dos parâmetros normais.',
            'criado_por' => $usuario2->id,
        ]);

        // Atribuindo usuários aos relatórios
        $relatorio1->usuariosAtribuidos()->attach($usuario1->id, [
            'permissao' => 'edicao',
            'atribuido_por' => $supervisor->id,
            'atribuido_em' => now(),
        ]);

        $relatorio2->usuariosAtribuidos()->attach($usuario2->id, [
            'permissao' => 'edicao',
            'atribuido_por' => $admin->id,
            'atribuido_em' => now(),
        ]);

        // Criando notificações de teste
        Notificacao::create([
            'titulo' => 'Novo relatório atribuído',
            'mensagem' => 'Você foi atribuído ao relatório: Manutenção Preventiva - Compressor 01',
            'tipo' => 'relatorio_atribuido',
            'user_id' => $usuario1->id,
            'relatorio_id' => $relatorio1->id,
            'dados_extras' => json_encode([
                'atribuido_por' => $supervisor->name,
                'permissao' => 'edicao'
            ])
        ]);

        Notificacao::create([
            'titulo' => 'Relatório concluído',
            'mensagem' => 'O relatório "Inspeção Mensal - Sistema Hidráulico" foi marcado como concluído.',
            'tipo' => 'relatorio_concluido',
            'user_id' => $supervisor->id,
            'relatorio_id' => $relatorio3->id,
            'dados_extras' => json_encode([
                'concluido_por' => $usuario2->name,
                'custo_real' => 120.00
            ])
        ]);

        echo "✅ Dados de teste criados com sucesso!\n";
        echo "📧 Admin: admin@sistema.com\n";
        echo "📧 Supervisor: supervisor@sistema.com\n";
        echo "📧 Usuários: maria@sistema.com, carlos@sistema.com\n";
        echo "🔑 Senha para todos: password\n";
    }
}
