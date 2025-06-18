<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with(['users', 'permissions'])->get();
        $permissions = Permission::with('roles')->get();
        
        // Estatísticas
        $totalUsuariosComRoles = User::whereHas('roles')->count();
        $usuariosSemRoles = User::whereDoesntHave('roles')->count();
        
        return view('admin.roles.index', compact(
            'roles',
            'permissions', 
            'totalUsuariosComRoles',
            'usuariosSemRoles'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        // Salvar campos adicionais se existirem (usando campos customizados se necessário)
        if ($request->display_name || $request->description) {
            // Aqui você pode adicionar lógica para salvar campos extras
            // Por exemplo, usando um modelo customizado ou campos JSON
        }

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role->load(['permissions', 'users']);
        $permissions = Permission::all();
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        // Atualizar permissões
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Impedir exclusão de roles do sistema
        if (in_array($role->name, ['admin', 'supervisor', 'usuario'])) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir roles do sistema!');
        }

        // Verificar se há usuários com esta role
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir uma role que possui usuários atribuídos!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role excluída com sucesso!');
    }
}
