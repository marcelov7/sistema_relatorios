<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $usuarios = $query->paginate(15)->withQueryString();
        $roles = Role::all();
        $departamentos = User::DEPARTAMENTOS;

        return view('admin.users.index', compact('usuarios', 'roles', 'departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $departamentos = User::DEPARTAMENTOS;
        
        return view('admin.users.create', compact('roles', 'departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users|regex:/^[a-zA-Z0-9._-]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefone' => 'nullable|string|max:20',
            'cargo' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|in:' . implode(',', array_keys(User::DEPARTAMENTOS)),
            'role' => 'required|exists:roles,name',
            'ativo' => 'boolean',
        ], [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.regex' => 'O nome de usuário deve conter apenas letras, números, pontos, hífens e sublinhados.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefone' => $request->telefone,
            'cargo' => $request->cargo,
            'departamento' => $request->departamento,
            'ativo' => $request->boolean('ativo', true),
            'configuracoes_notificacao' => [
                'relatorio_criado' => true,
                'relatorio_atualizado' => true,
                'relatorio_concluido' => true,
                'relatorio_atribuido' => true,
                'sistema' => false,
            ]
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'permissions');
        $departamentos = User::DEPARTAMENTOS;
        
        return view('admin.users.show', [
            'usuario' => $user,
            'departamentos' => $departamentos
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $departamentos = User::DEPARTAMENTOS;
        $userRole = $user->roles->first()?->name;
        
        return view('admin.users.edit', [
            'usuario' => $user,
            'roles' => $roles,
            'departamentos' => $departamentos,
            'userRole' => $userRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id . '|regex:/^[a-zA-Z0-9._-]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefone' => 'nullable|string|max:20',
            'cargo' => 'nullable|string|max:255',
            'departamento' => 'nullable|string|in:' . implode(',', array_keys(User::DEPARTAMENTOS)),
            'role' => 'required|exists:roles,name',
            'ativo' => 'boolean',
        ], [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.regex' => 'O nome de usuário deve conter apenas letras, números, pontos, hífens e sublinhados.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'cargo' => $request->cargo,
            'departamento' => $request->departamento,
            'ativo' => $request->boolean('ativo', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Atualizar role
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Impedir exclusão do próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        // Impedir desativação do próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Você não pode alterar o status da sua própria conta!');
        }

        $user->update(['ativo' => !$user->ativo]);

        $status = $user->ativo ? 'ativado' : 'desativado';
        
        return redirect()->back()
            ->with('success', "Usuário {$status} com sucesso!");
    }
}
