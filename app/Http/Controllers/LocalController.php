<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verificar se é admin
     */
    private function isAdmin()
    {
        return hasRole('admin');
    }

    /**
     * Verificar se é admin ou supervisor
     */
    private function isAdminOrSupervisor()
    {
        return hasRole(['admin', 'supervisor']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtros
        $busca = $request->get('busca');
        $status = $request->get('status');

        $query = Local::query();

        // Filtro por busca
        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%")
                  ->orWhere('endereco', 'like', "%{$busca}%");
            });
        }

        // Filtro por status
        if ($status !== null && $status !== '') {
            $query->where('ativo', $status == '1');
        }

        $locais = $query->withCount('relatorios')
                       ->orderBy('data_criacao', 'desc')
                       ->paginate(12);

        // Estatísticas
        $stats = [
            'total' => Local::count(),
            'ativos' => Local::ativos()->count(),
            'inativos' => Local::inativos()->count(),
            'com_relatorios' => Local::has('relatorios')->count(),
        ];

        return view('locais.index', compact('locais', 'stats', 'busca', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Apenas admin pode criar locais
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('locais.index')
                           ->with('error', 'Você não tem permissão para criar locais.');
        }

        return view('locais.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Apenas admin pode criar locais
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('locais.index')
                           ->with('error', 'Você não tem permissão para criar locais.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'endereco' => 'nullable|string|max:500',
            'ativo' => 'required|boolean'
        ]);

        $local = Local::create($validated);

        return redirect()
            ->route('locais.show', $local)
            ->with('success', 'Local criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Local $local)
    {
        $local->load('relatorios.usuario');
        
        // Relatórios recentes deste local
        $relatoriosRecentes = $local->relatorios()
                                   ->with('usuario')
                                   ->orderBy('data_criacao', 'desc')
                                   ->limit(5)
                                   ->get();

        // Estatísticas do local
        $stats = [
            'total_relatorios' => $local->relatorios()->count(),
            'relatorios_pendentes' => $local->relatorios()->where('status', 'pendente')->count(),
            'relatorios_em_andamento' => $local->relatorios()->where('status', 'em_andamento')->count(),
            'relatorios_resolvidos' => $local->relatorios()->where('status', 'resolvido')->count(),
        ];

        return view('locais.show', compact('local', 'relatoriosRecentes', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Local $local)
    {
        // Apenas admin pode editar locais
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('locais.show', $local)
                           ->with('error', 'Você não tem permissão para editar este local.');
        }

        return view('locais.edit', compact('local'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Local $local)
    {
        // Apenas admin pode editar locais
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('locais.show', $local)
                           ->with('error', 'Você não tem permissão para editar este local.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'endereco' => 'nullable|string|max:500',
            'ativo' => 'required|boolean'
        ]);

        $local->update($validated);

        return redirect()
            ->route('locais.show', $local)
            ->with('success', 'Local atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Local $local)
    {
        // Apenas admin pode excluir locais
        if (!$this->isAdmin()) {
            return redirect()->route('locais.index')
                           ->with('error', 'Você não tem permissão para excluir locais.');
        }

        // Verificar se pode ser excluído
        if (!$local->podeSerExcluido()) {
            return redirect()->route('locais.show', $local)
                           ->with('error', 'Este local não pode ser excluído pois possui relatórios ou equipamentos vinculados.');
        }

        $local->delete();

        return redirect()
            ->route('locais.index')
            ->with('success', 'Local excluído com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(Local $local)
    {
        // Apenas admin pode alterar status
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->back()
                           ->with('error', 'Você não tem permissão para alterar o status.');
        }

        $local->update(['ativo' => !$local->ativo]);

        $status = $local->ativo ? 'ativado' : 'desativado';
        
        return redirect()->back()
                        ->with('success', "Local {$status} com sucesso!");
    }

    /**
     * Listar locais ativos para select (API)
     */
    public function apiLocaisAtivos()
    {
        $locais = Local::ativos()
                      ->select('id', 'nome', 'endereco')
                      ->orderBy('nome')
                      ->get();

        return response()->json($locais);
    }
} 