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
        // Verificar se a tabela relatorio_itens existe, se não existir, criar
        $this->ensureRelatorioItensTableExists();
        
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
                    RelatorioItem::create([
                        'relatorio_id' => $relatorio->id,
                        'equipamento_id' => $item['equipamento_id'],
                        'descricao_equipamento' => $item['descricao_equipamento'],
                        'observacoes' => $item['observacoes'] ?? null,
                        'status_item' => $item['status_item'] ?? 'pendente',
                        'ordem' => $index + 1
                    ]);
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
                    'redirect' => route('relatorios.show', $relatorio)
                ]);
            }

            return redirect()->route('relatorios.show', $relatorio)
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
            $nomeArquivo = time() . '_' . uniqid() . '.' . $imagem->getClientOriginalExtension();
            $caminho = $imagem->storeAs('relatorios', $nomeArquivo, 'public');

            RelatorioImagem::create([
                'relatorio_id' => $relatorio->id,
                'nome_original' => $imagem->getClientOriginalName(),
                'nome_arquivo' => $nomeArquivo,
                'caminho' => $caminho,
                'tamanho' => $imagem->getSize(),
                'tipo' => $imagem->getMimeType()
            ]);
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
     * Garantir que a tabela relatorio_itens existe
     */
    private function ensureRelatorioItensTableExists()
    {
        try {
            if (!Schema::hasTable('relatorio_itens')) {
                Log::info('Tabela relatorio_itens não encontrada, criando dinamicamente...');
                
                // Verificar se as tabelas dependentes existem
                if (!Schema::hasTable('relatorios')) {
                    throw new \Exception('Tabela relatorios não encontrada. Execute as migrations primeiro.');
                }
                
                if (!Schema::hasTable('equipamentos')) {
                    throw new \Exception('Tabela equipamentos não encontrada. Execute as migrations primeiro.');
                }
                
                Schema::create('relatorio_itens', function ($table) {
                    $table->id();
                    $table->integer('relatorio_id')->unsigned();
                    $table->integer('equipamento_id')->unsigned();
                    $table->text('descricao_equipamento');
                    $table->text('observacoes')->nullable();
                    $table->enum('status_item', ['pendente', 'em_andamento', 'concluido'])->default('pendente');
                    $table->integer('ordem')->default(1);
                    $table->timestamps();
                    
                    // Índices para performance
                    $table->index(['relatorio_id', 'ordem']);
                    $table->index('equipamento_id');
                });
                
                // Adicionar foreign keys em separado para evitar problemas
                try {
                    Schema::table('relatorio_itens', function ($table) {
                        $table->foreign('relatorio_id')->references('id')->on('relatorios')->onDelete('cascade');
                        $table->foreign('equipamento_id')->references('id')->on('equipamentos');
                    });
                } catch (\Exception $e) {
                    Log::warning('Não foi possível criar foreign keys na tabela relatorio_itens: ' . $e->getMessage());
                }
                
                Log::info('Tabela relatorio_itens criada com sucesso');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao verificar/criar tabela relatorio_itens: ' . $e->getMessage());
            // Não interromper a aplicação se houver erro na criação da tabela
        }
    }
}
