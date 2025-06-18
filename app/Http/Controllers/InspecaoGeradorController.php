<?php

namespace App\Http\Controllers;

use App\Models\InspecaoGerador;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Gerador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InspecaoGeradorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InspecaoGerador::with('usuario')
            ->orderBy('data', 'desc')
            ->orderBy('criado_em', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('colaborador', 'like', "%{$search}%")
                  ->orWhere('observacao', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('colaborador')) {
            $query->where('colaborador', 'like', "%{$request->colaborador}%");
        }

        if ($request->filled('data_inicio')) {
            $query->where('data', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data', '<=', $request->data_fim);
        }

        $perPage = $request->get('per_page', 10);
        $inspecoes = $query->paginate($perPage);

        // Aplicar filtro de status após carregar os dados (necessário pois é um accessor)
        if ($request->filled('status')) {
            $status = $request->status;
            $inspecoesOriginais = $inspecoes->getCollection();
            
            $inspecoesFiltradas = $inspecoesOriginais->filter(function ($inspecao) use ($status) {
                $statusGeral = $inspecao->status_geral;
                return $statusGeral['status'] === $status;
            });
            
            // Recriar a paginação com os dados filtrados
            $currentPage = $request->get('page', 1);
            $path = $request->url();
            $query = $request->query();
            
            $inspecoes = new \Illuminate\Pagination\LengthAwarePaginator(
                $inspecoesFiltradas->forPage($currentPage, $perPage)->values(),
                $inspecoesFiltradas->count(),
                $perPage,
                $currentPage,
                ['path' => $path, 'query' => $query]
            );
            
            // Preservar links de paginação
            $inspecoes->withQueryString();
        }

        // Estatísticas
        $stats = [
            'total' => InspecaoGerador::count(),
            'este_mes' => InspecaoGerador::whereMonth('data', now()->month)
                                       ->whereYear('data', now()->year)
                                       ->count(),
            'com_problemas' => InspecaoGerador::where(function($q) {
                $q->where('nivel_oleo', 'Baixo')
                  ->orWhere('nivel_agua', 'Baixo')
                  ->orWhere('combustivel_50', 'Não')
                  ->orWhere('iluminacao_sala', 'Anormal');
            })->count(),
            'ultima_inspecao' => InspecaoGerador::orderBy('data', 'desc')->first()?->data_formatada ?? 'Nunca'
        ];

        return view('inspecoes-gerador.index', compact('inspecoes', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nivelOptions = InspecaoGerador::getNivelOptions();
        $simNaoOptions = InspecaoGerador::getSimNaoOptions();
        $iluminacaoOptions = InspecaoGerador::getIluminacaoOptions();
        
        return view('inspecoes-gerador.create', compact('nivelOptions', 'simNaoOptions', 'iluminacaoOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|date',
            'colaborador' => 'required|string|max:255',
            'nivel_oleo' => 'required|in:Máximo,Normal,Baixo',
            'nivel_agua' => 'required|in:Máximo,Normal,Baixo',
            'tensao_sync_gerador' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_sync_rede' => 'nullable|numeric|min:0|max:9999.99',
            'temp_agua' => 'nullable|numeric|min:0|max:999.99',
            'pressao_oleo' => 'nullable|numeric|min:0|max:999.99',
            'frequencia' => 'nullable|numeric|min:0|max:999.99',
            'tensao_a' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_b' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_c' => 'nullable|numeric|min:0|max:9999.99',
            'rpm' => 'nullable|integer|min:0|max:999999',
            'tensao_bateria' => 'nullable|numeric|min:0|max:999.99',
            'tensao_alternador' => 'nullable|numeric|min:0|max:999.99',
            'combustivel_50' => 'nullable|in:Sim,Não',
            'iluminacao_sala' => 'required|in:Normal,Anormal',
            'observacao' => 'nullable|string',
            'ativo' => 'boolean'
        ]);

        $inspecao = InspecaoGerador::create($validated);

        return redirect()
            ->route('inspecoes-gerador.show', $inspecao)
            ->with('success', 'Inspeção de gerador criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(InspecaoGerador $inspecaoGerador)
    {
        $inspecaoGerador->load('user');
        
        // Passa como 'inspecao' para manter consistência com a view
        return view('inspecoes-gerador.show', ['inspecao' => $inspecaoGerador]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InspecaoGerador $inspecaoGerador)
    {
        $nivelOptions = InspecaoGerador::getNivelOptions();
        $simNaoOptions = InspecaoGerador::getSimNaoOptions();
        $iluminacaoOptions = InspecaoGerador::getIluminacaoOptions();
        
        // Passa como 'inspecao' para manter consistência com a view
        return view('inspecoes-gerador.edit', [
            'inspecao' => $inspecaoGerador,
            'nivelOptions' => $nivelOptions,
            'simNaoOptions' => $simNaoOptions,
            'iluminacaoOptions' => $iluminacaoOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InspecaoGerador $inspecaoGerador)
    {
        $validated = $request->validate([
            'data' => 'required|date',
            'colaborador' => 'required|string|max:255',
            'nivel_oleo' => 'required|in:Máximo,Normal,Baixo',
            'nivel_agua' => 'required|in:Máximo,Normal,Baixo',
            'tensao_sync_gerador' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_sync_rede' => 'nullable|numeric|min:0|max:9999.99',
            'temp_agua' => 'nullable|numeric|min:0|max:999.99',
            'pressao_oleo' => 'nullable|numeric|min:0|max:999.99',
            'frequencia' => 'nullable|numeric|min:0|max:999.99',
            'tensao_a' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_b' => 'nullable|numeric|min:0|max:9999.99',
            'tensao_c' => 'nullable|numeric|min:0|max:9999.99',
            'rpm' => 'nullable|integer|min:0|max:999999',
            'tensao_bateria' => 'nullable|numeric|min:0|max:999.99',
            'tensao_alternador' => 'nullable|numeric|min:0|max:999.99',
            'combustivel_50' => 'nullable|in:Sim,Não',
            'iluminacao_sala' => 'required|in:Normal,Anormal',
            'observacao' => 'nullable|string',
            'ativo' => 'boolean'
        ]);

        $inspecaoGerador->update($validated);

        return redirect()
            ->route('inspecoes-gerador.show', $inspecaoGerador)
            ->with('success', 'Inspeção de gerador atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InspecaoGerador $inspecaoGerador)
    {
        $inspecaoGerador->delete();

        return redirect()
            ->route('inspecoes-gerador.index')
            ->with('success', 'Inspeção de gerador excluída com sucesso!');
    }

    /**
     * Duplicar inspeção
     */
    public function duplicate(InspecaoGerador $inspecaoGerador)
    {
        $novaInspecao = $inspecaoGerador->replicate();
        $novaInspecao->data = now()->format('Y-m-d');
        $novaInspecao->colaborador = Auth::user()->name; // Usa o nome do usuário atual
        $novaInspecao->user_id = Auth::id();
        $novaInspecao->save();

        return redirect()
            ->route('inspecoes-gerador.edit', $novaInspecao)
            ->with('success', 'Inspeção duplicada com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(InspecaoGerador $inspecaoGerador)
    {
        $inspecaoGerador->update(['ativo' => !$inspecaoGerador->ativo]);

        $status = $inspecaoGerador->ativo ? 'ativada' : 'desativada';
        
        return redirect()
            ->back()
            ->with('success', "Inspeção {$status} com sucesso!");
    }
}
