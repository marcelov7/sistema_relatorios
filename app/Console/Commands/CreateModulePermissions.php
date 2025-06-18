<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateModulePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:create-module 
                            {module : Nome do módulo (ex: equipamentos)} 
                            {--actions=* : Ações específicas (padrão: visualizar,criar,editar,excluir,gerenciar)}
                            {--roles=* : Roles que devem receber as permissões}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria permissões padronizadas para um novo módulo do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->argument('module');
        
        // Ações padrão do sistema
        $defaultActions = ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar'];
        $actions = $this->option('actions') ?: $defaultActions;
        
        $this->info("🚀 Criando permissões para o módulo: {$module}");
        $this->newLine();

        $createdPermissions = [];
        
        foreach ($actions as $action) {
            $permissionName = "{$module}_{$action}";
            
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            
            if ($permission->wasRecentlyCreated) {
                $createdPermissions[] = $permissionName;
                $this->line("✅ Criada: {$permissionName}");
            } else {
                $this->line("⚠️  Já existe: {$permissionName}");
            }
        }
        
        if (empty($createdPermissions)) {
            $this->warn("❌ Nenhuma permissão nova foi criada.");
            return;
        }
        
        $this->newLine();
        $this->info("📋 Permissões criadas: " . count($createdPermissions));
        
        // Perguntar se quer atribuir a roles
        if ($this->confirm('Deseja atribuir essas permissões a alguma role?')) {
            $this->assignToRoles($createdPermissions);
        }
        
        // Mostrar código para adicionar no middleware/controllers
        $this->showImplementationCode($module, $actions);
        
        $this->newLine();
        $this->info("🎉 Processo concluído!");
    }
    
    private function assignToRoles(array $permissions)
    {
        $roles = Role::all();
        
        if ($roles->isEmpty()) {
            $this->warn("Nenhuma role encontrada.");
            return;
        }
        
        $this->info("Roles disponíveis:");
        foreach ($roles as $role) {
            $this->line("  - {$role->name}");
        }
        
        $selectedRoles = $this->ask('Digite as roles separadas por vírgula (ex: admin,supervisor)', 'admin');
        $roleNames = array_map('trim', explode(',', $selectedRoles));
        
        foreach ($roleNames as $roleName) {
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $role->givePermissionTo($permissions);
                $this->line("✅ Permissões atribuídas à role: {$roleName}");
            } else {
                $this->error("❌ Role não encontrada: {$roleName}");
            }
        }
    }
    
    private function showImplementationCode(string $module, array $actions)
    {
        $this->newLine();
        $this->info("📝 Código para implementação:");
        $this->newLine();
        
        // Controller middleware
        $this->line("🔧 <comment>No Controller:</comment>");
        $this->line("public function __construct()");
        $this->line("{");
        $this->line("    \$this->middleware(['auth', 'permission:{$module}_visualizar'])->only(['index', 'show']);");
        $this->line("    \$this->middleware(['auth', 'permission:{$module}_criar'])->only(['create', 'store']);");
        $this->line("    \$this->middleware(['auth', 'permission:{$module}_editar'])->only(['edit', 'update']);");
        $this->line("    \$this->middleware(['auth', 'permission:{$module}_excluir'])->only(['destroy']);");
        $this->line("}");
        
        $this->newLine();
        
        // Routes
        $this->line("🛣️  <comment>Nas Rotas:</comment>");
        $this->line("Route::middleware(['permission:{$module}_gerenciar'])->group(function() {");
        $this->line("    Route::resource('{$module}', " . ucfirst($module) . "Controller::class);");
        $this->line("});");
        
        $this->newLine();
        
        // Blade directives
        $this->line("🎨 <comment>Nas Views (Blade):</comment>");
        $this->line("@can('{$module}_criar')");
        $this->line("    <a href=\"{{ route('{$module}.create') }}\" class=\"btn btn-primary\">Novo</a>");
        $this->line("@endcan");
        
        $this->newLine();
        
        // Navigation
        $this->line("🧭 <comment>No Menu de Navegação:</comment>");
        $this->line("@can('{$module}_visualizar')");
        $this->line("    <a href=\"{{ route('{$module}.index') }}\" class=\"nav-link\">");
        $this->line("        <i class=\"bi bi-icon\"></i> " . ucfirst($module));
        $this->line("    </a>");
        $this->line("@endcan");
    }
}
