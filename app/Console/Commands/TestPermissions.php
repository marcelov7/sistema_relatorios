<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\PermissionHelper;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de permissões implementado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TESTANDO SISTEMA DE PERMISSÕES');
        $this->newLine();

        // Teste 1: Verificar usuário admin
        $this->testAdminUser();
        
        // Teste 2: Verificar permissões criadas
        $this->testCreatedPermissions();
        
        // Teste 3: Verificar Helper
        $this->testPermissionHelper();
        
        // Teste 4: Verificar Controllers
        $this->testControllerMiddleware();
        
        $this->newLine();
        $this->info('✅ TODOS OS TESTES CONCLUÍDOS!');
    }

    private function testAdminUser()
    {
        $this->info('1️⃣ TESTE: Usuário Admin');
        
        $admin = User::where('email', 'admin@sistema.com')->first();
        
        if (!$admin) {
            $this->error('❌ Usuário admin não encontrado!');
            return;
        }
        
        $this->line("✅ Admin encontrado: {$admin->name}");
        
        // Testar permissões específicas
        $permissions = [
            'usuarios_gerenciar',
            'roles_criar', 
            'dashboard_admin',
            'relatorios_gerenciar',
            'pdfs_gerar'
        ];
        
        foreach ($permissions as $permission) {
            $has = $admin->can($permission);
            $status = $has ? '✅' : '❌';
            $this->line("  {$status} {$permission}: " . ($has ? 'SIM' : 'NÃO'));
        }
        
        $this->newLine();
    }

    private function testCreatedPermissions()
    {
        $this->info('2️⃣ TESTE: Permissões Criadas');
        
        $modules = ['usuarios', 'roles', 'relatorios', 'equipamentos', 'manutencoes'];
        
        foreach ($modules as $module) {
            $this->line("📋 Módulo: {$module}");
            
            $actions = ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar'];
            
            foreach ($actions as $action) {
                $permissionName = "{$module}_{$action}";
                $exists = Permission::where('name', $permissionName)->exists();
                $status = $exists ? '✅' : '❌';
                $this->line("    {$status} {$permissionName}");
            }
        }
        
        $this->newLine();
    }

    private function testPermissionHelper()
    {
        $this->info('3️⃣ TESTE: Permission Helper');
        
        $admin = User::where('email', 'admin@sistema.com')->first();
        
        if (!$admin) {
            $this->error('❌ Admin não encontrado para teste do helper');
            return;
        }
        
        // Simular login do admin
        auth()->login($admin);
        
        // Testar métodos do helper
        $tests = [
            'canView("usuarios")' => PermissionHelper::canView('usuarios'),
            'canCreate("roles")' => PermissionHelper::canCreate('roles'),
            'canManage("relatorios")' => PermissionHelper::canManage('relatorios'),
            'isAdmin()' => PermissionHelper::isAdmin(),
            'isSupervisor()' => PermissionHelper::isSupervisor(),
        ];
        
        foreach ($tests as $test => $result) {
            $status = $result ? '✅' : '❌';
            $this->line("  {$status} {$test}: " . ($result ? 'TRUE' : 'FALSE'));
        }
        
        auth()->logout();
        $this->newLine();
    }

    private function testControllerMiddleware()
    {
        $this->info('4️⃣ TESTE: Controllers e Middleware');
        
        // Verificar se os controllers existem
        $controllers = [
            'App\Http\Controllers\Admin\UserController',
            'App\Http\Controllers\Admin\RoleController', 
            'App\Http\Controllers\Admin\DashboardController'
        ];
        
        foreach ($controllers as $controller) {
            $exists = class_exists($controller);
            $status = $exists ? '✅' : '❌';
            $this->line("  {$status} {$controller}");
        }
        
        // Verificar middleware CheckRole
        $middlewareExists = class_exists('App\Http\Middleware\CheckRole');
        $status = $middlewareExists ? '✅' : '❌';
        $this->line("  {$status} Middleware CheckRole");
        
        $this->newLine();
    }
}
