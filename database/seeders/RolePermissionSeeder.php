<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // PADRÃO PARA TODAS AS FUNCIONALIDADES:
        // [modulo]_[acao] onde acao = visualizar|criar|editar|excluir|gerenciar|atribuir
        
        $permissions = [
            // 1. USUÁRIOS
            'usuarios_visualizar',
            'usuarios_criar', 
            'usuarios_editar',
            'usuarios_excluir',
            'usuarios_gerenciar',
            
            // 2. ROLES E PERMISSÕES
            'roles_visualizar',
            'roles_criar',
            'roles_editar', 
            'roles_excluir',
            'roles_gerenciar',
            
            // 3. RELATÓRIOS (para fase 3)
            'relatorios_visualizar',
            'relatorios_criar',
            'relatorios_editar',
            'relatorios_excluir',
            'relatorios_gerenciar',
            'relatorios_atribuir',
            
            // 4. PDFs E TEMPLATES (para fase 4)
            'pdfs_visualizar',
            'pdfs_gerar',
            'templates_visualizar',
            'templates_criar',
            'templates_editar',
            'templates_excluir',
            
            // 5. NOTIFICAÇÕES (para fase 5)
            'notificacoes_visualizar',
            'notificacoes_criar',
            'notificacoes_gerenciar',
            
            // 6. DASHBOARD E SISTEMA
            'dashboard_admin',
            'dashboard_supervisor',
            'sistema_configurar',
            'logs_visualizar',
            
            // PADRÃO PARA FUTURAS FUNCIONALIDADES:
            // Sempre seguir: [modulo]_[acao]
            // Ações padrão: visualizar, criar, editar, excluir, gerenciar, atribuir
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $usuarioRole = Role::firstOrCreate(['name' => 'usuario']);

        // Atribuir permissions aos roles
        $adminRole->givePermissionTo(Permission::all());

        $supervisorRole->givePermissionTo([
            'usuarios_visualizar',
            'dashboard_supervisor',
            'relatorios_visualizar',
            'relatorios_criar',
            'relatorios_editar',
            'relatorios_gerenciar',
            'notificacoes_visualizar',
            'pdfs_visualizar',
            'pdfs_gerar',
        ]);

        $usuarioRole->givePermissionTo([
            'relatorios_visualizar', // apenas seus próprios relatórios
            'relatorios_criar',
            'notificacoes_visualizar',
            'pdfs_visualizar',
        ]);

        // Criar usuário admin padrão se não existir
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@sistema.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('admin123'),
                'cargo' => 'Administrador do Sistema',
                'departamento' => 'ti',
                'ativo' => true,
                'configuracoes_notificacao' => json_encode([
                    'relatorio_criado' => true,
                    'relatorio_atualizado' => true,
                    'relatorio_concluido' => true,
                    'relatorio_atribuido' => true,
                    'sistema' => true,
                ])
            ]
        );

        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        echo "✅ Roles e Permissions criados com sucesso!\n";
        echo "📧 Admin: admin@sistema.com\n";
        echo "🔑 Senha: admin123\n";
    }
}
