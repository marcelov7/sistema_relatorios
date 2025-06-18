<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,supervisor']);
    }

    public function index()
    {
        // Estatísticas gerais
        $totalUsuarios = User::count();
        $usuariosAtivos = User::where('ativo', true)->count();
        $usuariosInativos = User::where('ativo', false)->count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();

        // Usuários por departamento
        $usuariosPorDepartamento = User::selectRaw('departamento, COUNT(*) as total')
            ->whereNotNull('departamento')
            ->groupBy('departamento')
            ->get()
            ->pluck('total', 'departamento');

        // Usuários por role
        $usuariosPorRole = User::with('roles')
            ->get()
            ->groupBy(function($user) {
                return $user->roles->first()?->name ?? 'Sem Role';
            })
            ->map(function($users) {
                return $users->count();
            });

        // Últimos usuários criados
        $ultimosUsuarios = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        // Mapeamento de departamentos
        $departamentos = [
            'manutencao' => 'Manutenção',
            'producao' => 'Produção', 
            'qualidade' => 'Qualidade',
            'engenharia' => 'Engenharia',
            'administracao' => 'Administração',
            'ti' => 'TI'
        ];

        return view('admin.dashboard', compact(
            'totalUsuarios',
            'usuariosAtivos', 
            'usuariosInativos',
            'totalRoles',
            'totalPermissions',
            'usuariosPorDepartamento',
            'usuariosPorRole',
            'ultimosUsuarios',
            'departamentos'
        ));
    }
}
