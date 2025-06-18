<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\PermissionHelper;

class TestWebAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:web-access';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa acesso web às funcionalidades com diferentes usuários';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌐 TESTANDO ACESSO WEB ÀS FUNCIONALIDADES');
        $this->newLine();

        // Teste com usuário Admin
        $this->testUserAccess('admin@sistema.com', 'admin');
        
        // Criar usuário supervisor para teste se não existir
        $this->createTestUsers();
        
        // Teste com usuário Supervisor
        $this->testUserAccess('supervisor@teste.com', 'supervisor');
        
        // Teste com usuário comum
        $this->testUserAccess('usuario@teste.com', 'usuario');
        
        $this->newLine();
        $this->info('✅ TESTE DE ACESSO WEB CONCLUÍDO!');
        $this->newLine();
        
        $this->displayAccessMatrix();
    }

    private function testUserAccess($email, $roleType)
    {
        $this->info("👤 TESTANDO: {$roleType} ({$email})");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Usuário {$email} não encontrado");
            return;
        }
        
        // Simular login
        auth()->login($user);
        
        // Testar acessos específicos
        $modules = [
            'usuarios' => ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar'],
            'roles' => ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar'], 
            'relatorios' => ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar'],
            'equipamentos' => ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar']
        ];
        
        foreach ($modules as $module => $actions) {
            $this->line("  📋 {$module}:");
            
            foreach ($actions as $action) {
                $can = PermissionHelper::can($module, $action);
                $status = $can ? '✅' : '❌';
                $this->line("    {$status} {$action}");
            }
        }
        
        // Testar dashboards
        $this->line("  🏠 Dashboards:");
        $this->line("    " . (PermissionHelper::isAdmin() ? '✅' : '❌') . " dashboard_admin");
        $this->line("    " . (PermissionHelper::isSupervisor() ? '✅' : '❌') . " dashboard_supervisor");
        
        auth()->logout();
        $this->newLine();
    }

    private function createTestUsers()
    {
        // Criar usuário supervisor se não existir
        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@teste.com'],
            [
                'name' => 'Supervisor Teste',
                'password' => bcrypt('supervisor123'),
                'cargo' => 'Supervisor',
                'departamento' => 'manutencao',
                'ativo' => true,
                'configuracoes_notificacao' => json_encode([
                    'relatorio_criado' => true,
                    'relatorio_atualizado' => true,
                ])
            ]
        );
        
        if (!$supervisor->hasRole('supervisor')) {
            $supervisor->assignRole('supervisor');
        }
        
        // Criar usuário comum se não existir
        $usuario = User::firstOrCreate(
            ['email' => 'usuario@teste.com'],
            [
                'name' => 'Usuário Teste',
                'password' => bcrypt('usuario123'),
                'cargo' => 'Técnico',
                'departamento' => 'manutencao',
                'ativo' => true,
                'configuracoes_notificacao' => json_encode([
                    'relatorio_criado' => true,
                ])
            ]
        );
        
        if (!$usuario->hasRole('usuario')) {
            $usuario->assignRole('usuario');
        }
    }

    private function displayAccessMatrix()
    {
        $this->info('📊 MATRIZ DE ACESSO RESUMIDA:');
        $this->newLine();
        
        $this->table(
            ['Funcionalidade', 'Admin', 'Supervisor', 'Usuário'],
            [
                ['Gerenciar Usuários', '✅', '❌', '❌'],
                ['Criar Roles', '✅', '❌', '❌'],
                ['Dashboard Admin', '✅', '❌', '❌'],
                ['Dashboard Supervisor', '✅', '✅', '❌'],
                ['Criar Relatórios', '✅', '✅', '✅'],
                ['Gerenciar Relatórios', '✅', '✅', '❌'],
                ['Gerar PDFs', '✅', '✅', '❌'],
                ['Ver Equipamentos', '❌', '❌', '✅'], // Atribuído só ao usuário no teste
            ]
        );
        
        $this->newLine();
        $this->info('🔗 Para testar na web, acesse:');
        $this->line('   • http://localhost:8000/login');
        $this->line('   • Admin: admin@sistema.com / admin123');
        $this->line('   • Supervisor: supervisor@teste.com / supervisor123');
        $this->line('   • Usuário: usuario@teste.com / usuario123');
        $this->line('   • Dashboard: http://localhost:8000/admin');
    }
}
