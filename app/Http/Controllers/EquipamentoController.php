<?php

namespace App\Http\Controllers;

use App\Models\Equipamento;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipamentoController extends Controller
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
        $user = auth()->user();
        return $user && ($user->papel === 'admin' || !$user->papel);
    }

    /**
     * Verificar se é admin ou supervisor
     */
    private function isAdminOrSupervisor()
    {
        $user = auth()->user();
        return $user && (in_array($user->papel, ['admin', 'supervisor']) || !$user->papel);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtros
        $busca = $request->get('busca');
        $status_operacional = $request->get('status_operacional');
        $ativo = $request->get('ativo');
        $local_id = $request->get('local_id');

        $query = Equipamento::with(['local']);

        // Filtro por busca
        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('codigo', 'like', "%{$busca}%")
                  ->orWhere('fabricante', 'like', "%{$busca}%")
                  ->orWhere('modelo', 'like', "%{$busca}%")
                  ->orWhere('numero_serie', 'like', "%{$busca}%");
            });
        }

        // Filtro por status operacional
        if ($status_operacional) {
            $query->where('status_operacional', $status_operacional);
        }

        // Filtro por ativo/inativo
        if ($ativo !== null && $ativo !== '') {
            $query->where('ativo', $ativo == '1');
        }

        // Filtro por local
        if ($local_id) {
            $query->where('local_id', $local_id);
        }

        $equipamentos = $query->withCount('relatorios')
                             ->orderBy('data_criacao', 'desc')
                             ->paginate(12);

        // Estatísticas
        $stats = [
            'total' => Equipamento::count(),
            'ativos' => Equipamento::where('ativo', true)->count(),
            'inativos' => Equipamento::where('ativo', false)->count(),
            'operando' => Equipamento::operando()->count(),
            'manutencao' => Equipamento::emManutencao()->count(),
            'com_relatorios' => Equipamento::has('relatorios')->count(),
        ];

        // Locais para filtro
        $locais = Local::ativos()->orderBy('nome')->get();

        // Status options
        $statusOptions = Equipamento::getStatusOptions();

        return view('equipamentos.index', compact(
            'equipamentos', 
            'stats', 
            'locais',
            'statusOptions',
            'busca', 
            'status_operacional',
            'ativo',
            'local_id'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Apenas admin pode criar equipamentos
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('equipamentos.index')
                           ->with('error', 'Você não tem permissão para criar equipamentos.');
        }

        $locais = Local::ativos()->orderBy('nome')->get();
        $statusOptions = Equipamento::getStatusOptions();

        return view('equipamentos.create', compact('locais', 'statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Apenas admin pode criar equipamentos
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('equipamentos.index')
                           ->with('error', 'Você não tem permissão para criar equipamentos.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:50|unique:equipamentos,codigo',
            'descricao' => 'required|string',
            'local_id' => 'required|exists:locais,id',
            'tipo' => 'nullable|string|max:50',
            'fabricante' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:100',
            'data_instalacao' => 'nullable|date',
            'status_operacional' => 'required|in:operando,manutencao,inativo',
            'ativo' => 'required|boolean'
        ]);

        $equipamento = Equipamento::create($validated);

        return redirect()
            ->route('equipamentos.show', $equipamento)
            ->with('success', 'Equipamento criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipamento $equipamento)
    {
        $equipamento->load(['local', 'relatorios.usuario']);
        
        // Relatórios recentes deste equipamento
        $relatoriosRecentes = $equipamento->relatorios()
                                         ->with('usuario')
                                         ->orderBy('data_criacao', 'desc')
                                         ->limit(5)
                                         ->get();

        // Estatísticas do equipamento
        $stats = [
            'total_relatorios' => $equipamento->relatorios()->count(),
            'relatorios_pendentes' => $equipamento->relatorios()->where('status', 'pendente')->count(),
            'relatorios_em_andamento' => $equipamento->relatorios()->where('status', 'em_andamento')->count(),
            'relatorios_resolvidos' => $equipamento->relatorios()->where('status', 'resolvido')->count(),
        ];

        return view('equipamentos.show', compact('equipamento', 'relatoriosRecentes', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipamento $equipamento)
    {
        // Apenas admin pode editar equipamentos
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('equipamentos.show', $equipamento)
                           ->with('error', 'Você não tem permissão para editar este equipamento.');
        }

        $locais = Local::ativos()->orderBy('nome')->get();
        $statusOptions = Equipamento::getStatusOptions();

        return view('equipamentos.edit', compact('equipamento', 'locais', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipamento $equipamento)
    {
        // Apenas admin pode editar equipamentos
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->route('equipamentos.show', $equipamento)
                           ->with('error', 'Você não tem permissão para editar este equipamento.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'codigo' => 'nullable|string|max:50|unique:equipamentos,codigo,' . $equipamento->id,
            'descricao' => 'required|string',
            'local_id' => 'required|exists:locais,id',
            'tipo' => 'nullable|string|max:50',
            'fabricante' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'numero_serie' => 'nullable|string|max:100',
            'data_instalacao' => 'nullable|date',
            'status_operacional' => 'required|in:operando,manutencao,inativo',
            'ativo' => 'required|boolean'
        ]);

        $equipamento->update($validated);

        return redirect()
            ->route('equipamentos.show', $equipamento)
            ->with('success', 'Equipamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipamento $equipamento)
    {
        // Apenas admin pode excluir equipamentos
        if (!$this->isAdmin()) {
            return redirect()->route('equipamentos.index')
                           ->with('error', 'Você não tem permissão para excluir equipamentos.');
        }

        // Verificar se pode ser excluído
        if (!$equipamento->podeSerExcluido()) {
            return redirect()->route('equipamentos.show', $equipamento)
                           ->with('error', 'Este equipamento não pode ser excluído pois possui relatórios vinculados.');
        }

        $equipamento->delete();

        return redirect()
            ->route('equipamentos.index')
            ->with('success', 'Equipamento excluído com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(Equipamento $equipamento)
    {
        // Apenas admin pode alterar status
        if (!$this->isAdminOrSupervisor()) {
            return redirect()->back()
                           ->with('error', 'Você não tem permissão para alterar o status.');
        }

        $equipamento->update(['ativo' => !$equipamento->ativo]);

        $status = $equipamento->ativo ? 'ativado' : 'desativado';
        
        return redirect()->back()
                        ->with('success', "Equipamento {$status} com sucesso!");
    }

    /**
     * Listar equipamentos ativos para select (API)
     */
    public function apiEquipamentosAtivos(Request $request)
    {
        $query = Equipamento::ativos()->select('id', 'nome', 'codigo', 'local_id');

        // Filtrar por local se fornecido
        if ($request->has('local_id') && $request->local_id) {
            $query->where('local_id', $request->local_id);
        }

        $equipamentos = $query->with('local:id,nome')
                             ->orderBy('nome')
                             ->get();

        return response()->json($equipamentos);
    }
} 