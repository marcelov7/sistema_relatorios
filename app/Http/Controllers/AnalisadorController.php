<?php

namespace App\Http\Controllers;

use App\Models\Analisador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnalisadorController extends Controller
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
        $query = Analisador::with('usuario')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('analyzer', 'like', "%{$search}%")
                  ->orWhere('observation', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('analyzer')) {
            $query->where('analyzer', $request->analyzer);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('check_date', [
                $request->data_inicio,
                $request->data_fim
            ]);
        }

        // Filtros rápidos
        if ($request->filled('created_at') && $request->created_at === 'today') {
            $query->novos();
        }

        if ($request->filled('componentes') && $request->componentes === 'problema') {
            $query->where(function($subQuery) {
                $subQuery->where('acid_filter', false)
                         ->orWhere('gas_dryer', false)
                         ->orWhere('paper_filter', false)
                         ->orWhere('peristaltic_pump', false)
                         ->orWhere('rotameter', false)
                         ->orWhere('disposable_filter', false)
                         ->orWhere('blocking_filter', false);
            });
        }

        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 20, 50]) ? $perPage : 10;
        
        $analisadores = $query->paginate($perPage);

        // Dados para filtros
        $tiposAnalisadores = Analisador::getTiposAnalisadores();
        $usuarios = User::where('ativo', true)->orderBy('name')->get();

        // Analisadores novos (últimas 24h) para destaque
        $analisadoresNovos = Analisador::with('usuario')
            ->novos()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Estatísticas
        $stats = [
            'total' => Analisador::count(),
            'ativos' => Analisador::where('ativo', true)->count(),
            'inativos' => Analisador::where('ativo', false)->count(),
            'com_problemas' => Analisador::where(function($query) {
                $query->where('acid_filter', false)
                      ->orWhere('gas_dryer', false)
                      ->orWhere('paper_filter', false)
                      ->orWhere('peristaltic_pump', false)
                      ->orWhere('rotameter', false)
                      ->orWhere('disposable_filter', false)
                      ->orWhere('blocking_filter', false);
            })->count(),
            'novos' => $analisadoresNovos->count(),
        ];

        return view('analisadores.index', compact(
            'analisadores', 
            'tiposAnalisadores', 
            'usuarios',
            'stats',
            'analisadoresNovos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposAnalisadores = Analisador::getTiposAnalisadores();
        $componentes = Analisador::getComponentes();
        
        return view('analisadores.create', compact('tiposAnalisadores', 'componentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'analyzer' => 'required|string|max:100',
            'check_date' => 'required|date',
            'acid_filter' => 'boolean',
            'gas_dryer' => 'boolean',
            'paper_filter' => 'boolean',
            'peristaltic_pump' => 'boolean',
            'rotameter' => 'boolean',
            'disposable_filter' => 'boolean',
            'blocking_filter' => 'boolean',
            'room_temperature' => 'nullable|numeric|min:-50|max:100',
            'air_pressure' => 'nullable|numeric|min:0|max:10',
            'observation' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168',
            'ativo' => 'boolean'
        ]);

        // Com os campos hidden, os valores boolean são enviados corretamente
        // Converter strings "0"/"1" para boolean
        $componentes = ['acid_filter', 'gas_dryer', 'paper_filter', 'peristaltic_pump', 
                       'rotameter', 'disposable_filter', 'blocking_filter'];
        
        foreach ($componentes as $componente) {
            $validated[$componente] = (bool) $validated[$componente];
        }
        
        $validated['ativo'] = (bool) $validated['ativo'];

        // Processar imagem se enviada
        if ($request->hasFile('image')) {
            $imagem = $request->file('image');
            $nomeArquivo = Str::uuid() . '.' . $imagem->getClientOriginalExtension();
            $caminho = $imagem->storeAs('analisadores', $nomeArquivo, 'public');
            $validated['image'] = $caminho;
        }

        $analisador = Analisador::create($validated);

        return redirect()
            ->route('analisadores.show', $analisador)
            ->with('success', 'Analisador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Analisador $analisador)
    {
        $analisador->load('usuario');
        $componentes = Analisador::getComponentes();
        
        return view('analisadores.show', compact('analisador', 'componentes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Analisador $analisador)
    {
        $tiposAnalisadores = Analisador::getTiposAnalisadores();
        $componentes = Analisador::getComponentes();
        
        return view('analisadores.edit', compact('analisador', 'tiposAnalisadores', 'componentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Analisador $analisador)
    {
        // Debug temporário - remover após teste
        \Log::info('Dados recebidos no update:', $request->all());
        
        $validated = $request->validate([
            'analyzer' => 'required|string|max:100',
            'check_date' => 'required|date',
            'acid_filter' => 'boolean',
            'gas_dryer' => 'boolean',
            'paper_filter' => 'boolean',
            'peristaltic_pump' => 'boolean',
            'rotameter' => 'boolean',
            'disposable_filter' => 'boolean',
            'blocking_filter' => 'boolean',
            'room_temperature' => 'nullable|numeric|min:-50|max:100',
            'air_pressure' => 'nullable|numeric|min:0|max:10',
            'observation' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168',
            'ativo' => 'boolean'
        ]);

        // Com os campos hidden, os valores boolean são enviados corretamente
        // Converter strings "0"/"1" para boolean
        $componentes = ['acid_filter', 'gas_dryer', 'paper_filter', 'peristaltic_pump', 
                       'rotameter', 'disposable_filter', 'blocking_filter'];
        
        foreach ($componentes as $componente) {
            $validated[$componente] = (bool) $validated[$componente];
        }
        
        $validated['ativo'] = (bool) $validated['ativo'];

        // Debug temporário - remover após teste
        \Log::info('Dados validados:', $validated);

        // Processar nova imagem se enviada
        if ($request->hasFile('image')) {
            // Deletar imagem anterior se existir
            if ($analisador->image && Storage::disk('public')->exists($analisador->image)) {
                Storage::disk('public')->delete($analisador->image);
            }

            $imagem = $request->file('image');
            $nomeArquivo = Str::uuid() . '.' . $imagem->getClientOriginalExtension();
            $caminho = $imagem->storeAs('analisadores', $nomeArquivo, 'public');
            $validated['image'] = $caminho;
        }

        $analisador->update($validated);

        return redirect()
            ->route('analisadores.show', $analisador)
            ->with('success', 'Analisador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Analisador $analisador)
    {
        // Deletar imagem se existir
        if ($analisador->image && Storage::disk('public')->exists($analisador->image)) {
            Storage::disk('public')->delete($analisador->image);
        }

        $analisador->delete();

        return redirect()
            ->route('analisadores.index')
            ->with('success', 'Analisador excluído com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(Analisador $analisador)
    {
        $analisador->update(['ativo' => !$analisador->ativo]);

        $status = $analisador->ativo ? 'ativado' : 'desativado';
        
        return redirect()->back()->with('success', "Analisador {$status} com sucesso!");
    }

    /**
     * Duplicar analisador
     */
    public function duplicate(Analisador $analisador)
    {
        $novoAnalisador = $analisador->replicate();
        $novoAnalisador->analyzer = 'Cópia de ' . $analisador->analyzer;
        $novoAnalisador->check_date = now()->format('Y-m-d');
        $novoAnalisador->image = null; // Não duplicar imagem
        $novoAnalisador->save();

        return redirect()
            ->route('analisadores.edit', $novoAnalisador)
            ->with('success', 'Analisador duplicado com sucesso!');
    }
}
