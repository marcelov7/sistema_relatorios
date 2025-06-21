<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;
use App\Models\RelatorioItem;
use App\Models\Local;
use App\Models\Equipamento;
use App\Models\RelatorioImagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RelatorioV2Controller extends Controller
{
    /**
     * Exibir formulário de criação V2
     */
    public function create()
    {
        $locais = Local::orderBy('nome')->get();
        $equipamentos = Equipamento::with('local')->orderBy('nome')->get();
        
        return view('relatorios.create-v2', compact('locais', 'equipamentos'));
    }

    /**
     * Armazenar novo relatório V2 com múltiplos equipamentos
     */
    public function store(Request $request)
    {
        try {
            // Validação
            $validated = $this->validateRelatorioV2($request);
            
            DB::beginTransaction();

            // Criar relatório principal (sem equipamento_id específico)
            $relatorioData = $validated;
            unset($relatorioData['itens'], $relatorioData['imagens']);
            
            // Remove equipamento_id do relatório principal (será nos itens)
            unset($relatorioData['equipamento_id']);
            $relatorioData['editavel'] = true;

            $relatorio = Relatorio::create($relatorioData);

            // Criar itens do relatório (equipamentos + descrições)
            if (isset($validated['itens']) && is_array($validated['itens'])) {
                foreach ($validated['itens'] as $index => $item) {
                    try {
                        // Tentar usar a model primeiro
                        RelatorioItem::create([
                            'relatorio_id' => $relatorio->id,
                            'equipamento_id' => $item['equipamento_id'],
                            'descricao_equipamento' => $item['descricao_equipamento'],
                            'observacoes' => $item['observacoes'] ?? null,
                            'status_item' => $item['status_item'] ?? 'pendente',
                            'ordem' => $index + 1
                        ]);
                    } catch (\Exception $e) {
                        // Se falhar, usar DB::table diretamente
                        DB::table('relatorio_itens')->insert([
                            'relatorio_id' => $relatorio->id,
                            'equipamento_id' => $item['equipamento_id'],
                            'descricao_equipamento' => $item['descricao_equipamento'],
                            'observacoes' => $item['observacoes'] ?? null,
                            'status_item' => $item['status_item'] ?? 'pendente',
                            'ordem' => $index + 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // Processar imagens se houver
            if ($request->hasFile('imagens')) {
                $this->processarImagens($request->file('imagens'), $relatorio);
            }

            DB::commit();

            // Retornar resposta baseada no tipo de requisição
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Relatório criado com sucesso!',
                    'redirect' => route('relatorios-v2.show', $relatorio)
                ]);
            }

            return redirect()->route('relatorios-v2.show', $relatorio)
                           ->with('success', 'Relatório criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erro ao criar relatório V2: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar relatório: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                         ->withErrors(['error' => 'Erro ao criar relatório: ' . $e->getMessage()]);
        }
    }

    /**
     * Validar dados do relatório V2
     */
    private function validateRelatorioV2(Request $request)
    {
        return $request->validate([
            'titulo' => 'required|string|max:200',
            'descricao' => 'required|string',
            'data_ocorrencia' => 'required|date',
            'status' => 'required|in:pendente,em_andamento,resolvido',
            'prioridade' => 'required|in:baixa,media,alta,critica',
            'progresso' => 'required|integer|min:0|max:100',
            'local_id' => 'required|exists:locais,id',
            
            // Validação dos itens (equipamentos)
            'itens' => 'required|array|min:1',
            'itens.*.equipamento_id' => 'required|exists:equipamentos,id',
            'itens.*.descricao_equipamento' => 'required|string',
            'itens.*.observacoes' => 'nullable|string',
            'itens.*.status_item' => 'nullable|in:pendente,em_andamento,concluido',
            
            // Validação das imagens
            'imagens.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168' // 7MB
        ], [
            'itens.required' => 'É necessário adicionar pelo menos um equipamento.',
            'itens.min' => 'É necessário adicionar pelo menos um equipamento.',
            'itens.*.equipamento_id.required' => 'Selecione um equipamento para cada item.',
            'itens.*.descricao_equipamento.required' => 'Adicione uma descrição para cada equipamento.',
        ]);
    }

    /**
     * Processar upload de imagens
     */
    private function processarImagens($imagens, $relatorio)
    {
        foreach ($imagens as $imagem) {
            try {
                // Validações básicas
                if (!$imagem->isValid()) {
                    throw new \Exception("Arquivo de imagem inválido: {$imagem->getClientOriginalName()}");
                }

                $nomeOriginal = $imagem->getClientOriginalName();
                $extensao = $imagem->getClientOriginalExtension();
                $nomeArquivo = time() . '_' . uniqid() . '.' . $extensao;
                $diretorioRelatorio = 'relatorios/' . $relatorio->id;

                // Salvar arquivo
                $caminho = $imagem->storeAs($diretorioRelatorio, $nomeArquivo, 'public');
                
                if (!$caminho) {
                    throw new \Exception("Falha ao salvar o arquivo no storage");
                }

                // Salvar no banco usando os campos corretos da tabela relatorio_imagens
                RelatorioImagem::create([
                    'relatorio_id' => $relatorio->id,
                    'nome_original' => $nomeOriginal,
                    'nome_arquivo' => $nomeArquivo,
                    'caminho_arquivo' => $caminho,  // Campo correto
                    'tamanho_arquivo' => $imagem->getSize(),  // Campo correto
                    'tipo_mime' => $imagem->getMimeType(),  // Campo correto
                    'tenant_id' => 1
                ]);

                \Log::info("Imagem V2 processada com sucesso", [
                    'nome_original' => $nomeOriginal,
                    'caminho' => $caminho,
                    'relatorio_id' => $relatorio->id
                ]);

            } catch (\Exception $e) {
                \Log::error("Erro ao processar imagem V2: " . $e->getMessage(), [
                    'nome_arquivo' => $imagem->getClientOriginalName() ?? 'unknown',
                    'relatorio_id' => $relatorio->id
                ]);
                throw $e;
            }
        }
    }

    /**
     * API para buscar equipamentos por local
     */
    public function equipamentosPorLocal(Request $request)
    {
        $localId = $request->input('local_id');
        
        if (!$localId) {
            return response()->json([]);
        }

        $equipamentos = Equipamento::where('local_id', $localId)
                                  ->orderBy('nome')
                                  ->get(['id', 'nome', 'codigo']);

        return response()->json($equipamentos);
    }

    /**
     * Exibir relatório V2 específico
     */
    public function show(Relatorio $relatorio)
    {
        // Carregar os itens do relatório
        $itens = collect();
        if (Schema::hasTable('relatorio_itens')) {
            $itens = DB::table('relatorio_itens')
                       ->join('equipamentos', 'relatorio_itens.equipamento_id', '=', 'equipamentos.id')
                       ->where('relatorio_itens.relatorio_id', $relatorio->id)
                       ->select(
                           'relatorio_itens.*',
                           'equipamentos.nome as equipamento_nome',
                           'equipamentos.codigo as equipamento_codigo'
                       )
                       ->orderBy('relatorio_itens.ordem')
                       ->get();
        }

        $relatorio->load(['usuario', 'local', 'imagens']);

        return view('relatorios.show-v2', compact('relatorio', 'itens'));
    }

    /**
     * Exibir formulário de edição V2
     */
    public function edit(Relatorio $relatorio)
    {
        // Carregar os itens do relatório
        $itens = collect();
        if (Schema::hasTable('relatorio_itens')) {
            $itens = DB::table('relatorio_itens')
                       ->where('relatorio_id', $relatorio->id)
                       ->orderBy('ordem')
                       ->get();
        }

        $locais = Local::orderBy('nome')->get();
        $equipamentos = Equipamento::with('local')->orderBy('nome')->get();

        return view('relatorios.edit-v2', compact('relatorio', 'itens', 'locais', 'equipamentos'));
    }

    /**
     * Atualizar relatório V2
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        try {
            $validated = $this->validateRelatorioV2($request);
            
            DB::beginTransaction();

            // Atualizar dados principais do relatório
            $relatorioData = $validated;
            unset($relatorioData['itens'], $relatorioData['imagens']);
            
            $relatorio->update($relatorioData);

            // Remover itens existentes
            if (Schema::hasTable('relatorio_itens')) {
                DB::table('relatorio_itens')->where('relatorio_id', $relatorio->id)->delete();
            }

            // Recriar itens
            if (isset($validated['itens']) && is_array($validated['itens'])) {
                foreach ($validated['itens'] as $index => $item) {
                    try {
                        RelatorioItem::create([
                            'relatorio_id' => $relatorio->id,
                            'equipamento_id' => $item['equipamento_id'],
                            'descricao_equipamento' => $item['descricao_equipamento'],
                            'observacoes' => $item['observacoes'] ?? null,
                            'status_item' => $item['status_item'] ?? 'pendente',
                            'ordem' => $index + 1
                        ]);
                    } catch (\Exception $e) {
                        DB::table('relatorio_itens')->insert([
                            'relatorio_id' => $relatorio->id,
                            'equipamento_id' => $item['equipamento_id'],
                            'descricao_equipamento' => $item['descricao_equipamento'],
                            'observacoes' => $item['observacoes'] ?? null,
                            'status_item' => $item['status_item'] ?? 'pendente',
                            'ordem' => $index + 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // Processar novas imagens se houver
            if ($request->hasFile('imagens')) {
                $this->processarImagens($request->file('imagens'), $relatorio);
            }

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Relatório atualizado com sucesso!',
                    'redirect' => route('relatorios-v2.show', $relatorio)
                ]);
            }

            return redirect()->route('relatorios-v2.show', $relatorio)
                           ->with('success', 'Relatório atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erro ao atualizar relatório V2: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar relatório: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                         ->withErrors(['error' => 'Erro ao atualizar relatório: ' . $e->getMessage()]);
        }
    }

    /**
     * Gerar PDF para relatório V2
     */
    public function pdf(Relatorio $relatorio)
    {
        // Carregar os itens do relatório
        $itens = collect();
        if (Schema::hasTable('relatorio_itens')) {
            $itens = DB::table('relatorio_itens')
                       ->join('equipamentos', 'relatorio_itens.equipamento_id', '=', 'equipamentos.id')
                       ->where('relatorio_itens.relatorio_id', $relatorio->id)
                       ->select(
                           'relatorio_itens.*',
                           'equipamentos.nome as equipamento_nome',
                           'equipamentos.codigo as equipamento_codigo'
                       )
                       ->orderBy('relatorio_itens.ordem')
                       ->get();
        }

        $relatorio->load(['usuario', 'local', 'imagens']);

        $pdf = \PDF::loadView('pdf.relatorio-v2', compact('relatorio', 'itens'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'relatorio-v2-' . $relatorio->id . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

}
