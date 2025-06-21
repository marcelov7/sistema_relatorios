<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;
use App\Models\RelatorioImagem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Verifica se o usuário é admin
     */
    private function isAdmin()
    {
        return auth()->user()->role === 'admin' || 
               (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'));
    }

    /**
     * Verifica se o usuário é admin ou supervisor
     */
    private function isAdminOrSupervisor()
    {
        return in_array(auth()->user()->role, ['admin', 'supervisor']) ||
               (method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole(['admin', 'supervisor']));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Relatorio::with('usuario')
            ->orderBy('data_criacao', 'desc');

        // Filtros rápidos
        if ($request->filled('novo') && $request->novo == '1') {
            $query->novos(24);
        }

        if ($request->filled('atualizado') && $request->atualizado == '1') {
            $query->atualizadosRecentemente(24);
        }

        // Filtros tradicionais
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $query->whereBetween('data_ocorrencia', [
                $request->data_inicio . ' 00:00:00',
                $request->data_fim . ' 23:59:59'
            ]);
        }

        $perPage = $request->get('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 20, 50]) ? $perPage : 10;
        
        $relatorios = $query->paginate($perPage);

        // Dados para filtros
        $statusOptions = Relatorio::getStatusOptions();
        $prioridadeOptions = Relatorio::getPrioridadeOptions();
        $usuarios = User::where('ativo', true)
                       ->orderBy('name')
                       ->get();

        // Estatísticas
        $stats = [
            'total' => Relatorio::count(),
            'pendentes' => Relatorio::pendentes()->count(),
            'em_andamento' => Relatorio::emAndamento()->count(),
            'resolvidos' => Relatorio::resolvidos()->count(),
        ];

        // Relatórios com destaque (novos e atualizados)
        $novosRelatorios = Relatorio::with('usuario')
            ->where('data_criacao', '>=', now()->subHours(24))
            ->orderBy('data_criacao', 'desc')
            ->take(5)
            ->get();

        $atualizadosRecentemente = Relatorio::with('usuario')
            ->where('data_atualizacao', '>=', now()->subHours(24))
            ->where('data_atualizacao', '!=', 'data_criacao')
            ->orderBy('data_atualizacao', 'desc')
            ->take(5)
            ->get();

        return view('relatorios.index', compact(
            'relatorios', 
            'statusOptions', 
            'prioridadeOptions', 
            'usuarios',
            'stats',
            'novosRelatorios',
            'atualizadosRecentemente'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusOptions = Relatorio::getStatusOptions();
        $prioridadeOptions = Relatorio::getPrioridadeOptions();
        $locais = \App\Models\Local::ativos()->orderBy('nome')->get();
        $equipamentos = \App\Models\Equipamento::ativos()->with('local')->orderBy('nome')->get();
        
        return view('relatorios.create', compact(
            'statusOptions', 
            'prioridadeOptions',
            'locais',
            'equipamentos'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'descricao' => 'required|string',
            'data_ocorrencia' => 'required|date',
            'status' => 'required|in:pendente,em_andamento,resolvido',
            'prioridade' => 'required|in:baixa,media,alta,critica',
            'progresso' => 'required|integer|min:0|max:100',
            'local_id' => 'required|exists:locais,id',
            'equipamento_id' => 'required|exists:equipamentos,id',
            'imagens.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168' // 7MB em KB
        ]);

        $validated['editavel'] = true;

        $relatorio = Relatorio::create($validated);

        // Processar imagens
        if ($request->hasFile('imagens')) {
            try {
                $this->processarImagens($request->file('imagens'), $relatorio);
            } catch (\Exception $e) {
                // Se houver erro com imagens, deletar o relatório e retornar erro
                $relatorio->delete();
                
                \Log::error("Erro ao criar relatório com imagens", [
                    'erro' => $e->getMessage(),
                    'usuario_id' => auth()->id()
                ]);
                
                // Verificar se é uma requisição AJAX
                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erro ao processar imagens: ' . $e->getMessage()
                    ], 422);
                }
                
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erro ao processar imagens: ' . $e->getMessage());
            }
        }

        // Verificar se é uma requisição AJAX
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Relatório criado com sucesso!',
                'relatorio_id' => $relatorio->id,
                'redirect_url' => route('relatorios.show', $relatorio)
            ]);
        }

        return redirect()
            ->route('relatorios.show', $relatorio)
            ->with('success', 'Relatório criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Relatorio $relatorio)
    {
        // Se for um relatório V2, redirecionar para a view V2
        if ($relatorio->isV2()) {
            return redirect()->route('relatorios-v2.show', $relatorio);
        }

        $relatorio->load('usuario', 'imagens', 'local', 'equipamento', 'historicos.usuario', 'historicos.imagens');
        
        return view('relatorios.show', compact('relatorio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Relatorio $relatorio)
    {
        // Se for um relatório V2, redirecionar para a edição V2
        if ($relatorio->isV2()) {
            return redirect()->route('relatorios-v2.edit', $relatorio);
        }

        // Verifica se pode editar
        if (!$relatorio->podeSerEditado()) {
            return redirect()
                ->route('relatorios.show', $relatorio)
                ->with('error', 'Este relatório não pode ser editado.');
        }

        // Verifica se o usuário pode editar (próprio relatório ou admin)
        if ($relatorio->usuario_id !== auth()->id() && !$this->isAdminOrSupervisor()) {
            return redirect()
                ->route('relatorios.show', $relatorio)
                ->with('error', 'Você não tem permissão para editar este relatório.');
        }

        $relatorio->load('imagens');
        $statusOptions = Relatorio::getStatusOptions();
        $prioridadeOptions = Relatorio::getPrioridadeOptions();
        $locais = \App\Models\Local::ativos()->orderBy('nome')->get();
        $equipamentos = \App\Models\Equipamento::ativos()->with('local')->orderBy('nome')->get();
        
        return view('relatorios.edit', compact(
            'relatorio',
            'statusOptions', 
            'prioridadeOptions',
            'locais',
            'equipamentos'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Relatorio $relatorio)
    {
        // Verifica permissões
        if (!$relatorio->podeSerEditado()) {
            return redirect()
                ->route('relatorios.show', $relatorio)
                ->with('error', 'Este relatório não pode ser editado.');
        }

        if ($relatorio->usuario_id !== auth()->id() && !$this->isAdminOrSupervisor()) {
            return redirect()
                ->route('relatorios.show', $relatorio)
                ->with('error', 'Você não tem permissão para editar este relatório.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:200',
            'descricao' => 'required|string',
            'data_ocorrencia' => 'required|date',
            'status' => 'required|in:pendente,em_andamento,resolvido',
            'prioridade' => 'required|in:baixa,media,alta,critica',
            'progresso' => 'required|integer|min:0|max:100',
            'local_id' => 'required|exists:locais,id',
            'equipamento_id' => 'required|exists:equipamentos,id',
            'imagens.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168', // 7MB em KB
            'remover_imagens' => 'nullable|array',
            'remover_imagens.*' => 'exists:relatorio_imagens,id'
        ]);

        $relatorio->update($validated);

        // Remover imagens selecionadas
        if ($request->has('remover_imagens')) {
            RelatorioImagem::whereIn('id', $request->remover_imagens)
                          ->where('relatorio_id', $relatorio->id)
                          ->get()
                          ->each(function ($imagem) {
                              $imagem->deletarArquivo();
                          });
        }

        // Processar novas imagens
        if ($request->hasFile('imagens')) {
            try {
                $this->processarImagens($request->file('imagens'), $relatorio);
            } catch (\Exception $e) {
                \Log::error("Erro ao atualizar relatório com imagens", [
                    'relatorio_id' => $relatorio->id,
                    'erro' => $e->getMessage(),
                    'usuario_id' => auth()->id()
                ]);
                
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Erro ao processar imagens: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('relatorios.show', $relatorio)
            ->with('success', 'Relatório atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Relatorio $relatorio)
    {
        // Verifica se pode excluir (apenas o criador ou admin)
        if ($relatorio->usuario_id !== auth()->id() && !$this->isAdmin()) {
            return redirect()
                ->route('relatorios.index')
                ->with('error', 'Você não tem permissão para excluir este relatório.');
        }

        $relatorio->delete();

        return redirect()
            ->route('relatorios.index')
            ->with('success', 'Relatório excluído com sucesso!');
    }

    /**
     * Atualiza o progresso do relatório com histórico detalhado
     */
    public function updateProgresso(Request $request, Relatorio $relatorio)
    {
        $validated = $request->validate([
            'progresso' => 'required|integer|min:0|max:100',
            'descricao' => 'required|string|min:10|max:1000',
            'imagens.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168' // 7MB em KB
        ]);

        $statusAnterior = $relatorio->status;
        
        // Atualizar progresso (que pode alterar o status automaticamente)
        $relatorio->atualizarProgresso($validated['progresso']);

        // Criar histórico da atualização
        $historico = \App\Models\RelatorioHistorico::create([
            'relatorio_id' => $relatorio->id,
            'status_anterior' => $statusAnterior,
            'status_novo' => $relatorio->status,
            'descricao' => $validated['descricao'],
            'progresso' => $validated['progresso']
        ]);

        // Processar imagens do histórico
        if ($request->hasFile('imagens')) {
            try {
                $this->processarImagensHistorico($request->file('imagens'), $relatorio, $historico);
            } catch (\Exception $e) {
                \Log::error("Erro ao processar imagens do histórico", [
                    'relatorio_id' => $relatorio->id,
                    'historico_id' => $historico->id,
                    'erro' => $e->getMessage(),
                    'usuario_id' => auth()->id()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar imagens: ' . $e->getMessage()
                ], 422);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Progresso atualizado com sucesso!',
            'status' => $relatorio->status_label,
            'progresso' => $relatorio->progresso,
            'redirect' => route('relatorios.show', $relatorio)
        ]);
    }

    /**
     * Toggle editavel status
     */
    public function toggleEditavel(Relatorio $relatorio)
    {
        // Apenas admin pode alterar editabilidade
        if (!$this->isAdmin()) {
            return redirect()->back()->with('error', 'Sem permissão.');
        }

        $relatorio->update(['editavel' => !$relatorio->editavel]);

        $status = $relatorio->editavel ? 'editável' : 'não editável';
        
        return redirect()->back()->with('success', "Relatório marcado como {$status}.");
    }

    /**
     * Duplicar relatório
     */
    public function duplicate(Relatorio $relatorio)
    {
        $novoRelatorio = $relatorio->replicate();
        $novoRelatorio->titulo = 'Cópia de ' . $relatorio->titulo;
        $novoRelatorio->status = Relatorio::STATUS_PENDENTE;
        $novoRelatorio->progresso = 0;
        $novoRelatorio->data_ocorrencia = now();
        $novoRelatorio->save();

        return redirect()
            ->route('relatorios.edit', $novoRelatorio)
            ->with('success', 'Relatório duplicado com sucesso!');
    }

    /**
     * Dashboard simples e funcional
     */
    public function dashboard()
    {
        // Estatísticas básicas - dados reais
        $statsRelatorios = [
            'total' => Relatorio::count(),
            'pendentes' => Relatorio::where('status', 'pendente')->count(),
            'em_andamento' => Relatorio::where('status', 'em_andamento')->count(),
            'resolvidos' => Relatorio::where('status', 'resolvido')->count(),
        ];

        $statsMotores = [
            'total' => \App\Models\Motor::count(),
            'com_estoque' => \App\Models\Motor::where('stock_reserve', 'Sim')->count(),
            'sem_estoque' => \App\Models\Motor::where('stock_reserve', 'Não')->count(),
            'ativos' => \App\Models\Motor::whereNotNull('equipment')->count(),
        ];

        $statsAnalisadores = [
            'total' => \App\Models\Analisador::count(),
            'ativos' => \App\Models\Analisador::where('ativo', true)->count(),
            'inativos' => \App\Models\Analisador::where('ativo', false)->count(),
            'com_problemas' => 2, // Simplificado por enquanto
        ];

        $statsInspecoes = [
            'total' => \App\Models\InspecaoGerador::count(),
            'este_mes' => \App\Models\InspecaoGerador::whereMonth('data', now()->month)->count(),
            'ultimos_7_dias' => \App\Models\InspecaoGerador::where('data', '>=', now()->subDays(7))->count(),
            'hoje' => \App\Models\InspecaoGerador::whereDate('data', now())->count(),
        ];

        return view('relatorios.dashboard', compact(
            'statsRelatorios',
            'statsMotores', 
            'statsAnalisadores',
            'statsInspecoes'
        ));
    }

    /**
     * Retorna a cor do badge baseada no status
     */
    private function getStatusColor($status)
    {
        switch($status) {
            case 'pendente': return 'warning';
            case 'em_andamento': return 'info';
            case 'resolvido': return 'success';
            default: return 'secondary';
        }
    }

    /**
     * Processa upload de múltiplas imagens
     */
    private function processarImagens($imagens, Relatorio $relatorio)
    {
        foreach ($imagens as $index => $imagem) {
            try {
                // Validações adicionais
                if (!$imagem->isValid()) {
                    \Log::error("Imagem inválida no índice {$index}", [
                        'error' => $imagem->getError(),
                        'original_name' => $imagem->getClientOriginalName()
                    ]);
                    throw new \Exception("Arquivo de imagem inválido: {$imagem->getClientOriginalName()}");
                }

                // Verificar se o arquivo foi realmente enviado
                if (!$imagem->isFile()) {
                    throw new \Exception("Arquivo não foi enviado corretamente");
                }

                // Verificar tamanho do arquivo
                $tamanhoMB = $imagem->getSize() / 1024 / 1024;
                if ($tamanhoMB > 7) {
                    throw new \Exception("Imagem {$imagem->getClientOriginalName()} excede o limite de 7MB (atual: {$tamanhoMB}MB)");
                }

                $nomeOriginal = $imagem->getClientOriginalName();
                $extensao = $imagem->getClientOriginalExtension();
                $nomeArquivo = Str::uuid() . '.' . $extensao;
                $diretorioRelatorio = 'relatorios/' . $relatorio->id;

                // Criar diretório se não existir
                if (!Storage::disk('public')->exists($diretorioRelatorio)) {
                    $created = Storage::disk('public')->makeDirectory($diretorioRelatorio);
                    if (!$created) {
                        throw new \Exception("Não foi possível criar o diretório: {$diretorioRelatorio}");
                    }
                }

                // Verificar se o diretório é gravável
                $fullPath = storage_path('app/public/' . $diretorioRelatorio);
                if (!is_writable($fullPath)) {
                    throw new \Exception("Diretório não é gravável: {$fullPath}");
                }

                // Salvar arquivo
                $caminho = $imagem->storeAs($diretorioRelatorio, $nomeArquivo, 'public');
                
                if (!$caminho) {
                    throw new \Exception("Falha ao salvar o arquivo no storage");
                }

                // Verificar se o arquivo foi realmente salvo
                if (!Storage::disk('public')->exists($caminho)) {
                    throw new \Exception("Arquivo não foi encontrado após o salvamento: {$caminho}");
                }

                // Salvar no banco
                $imagemModel = RelatorioImagem::create([
                    'relatorio_id' => $relatorio->id,
                    'nome_arquivo' => $nomeArquivo,
                    'nome_original' => $nomeOriginal,
                    'caminho_arquivo' => $caminho,
                    'tamanho_arquivo' => $imagem->getSize(),
                    'tipo_mime' => $imagem->getMimeType(),
                    'tenant_id' => 1 // Temporário
                ]);

                if (!$imagemModel) {
                    // Se não conseguiu salvar no banco, remover o arquivo
                    Storage::disk('public')->delete($caminho);
                    throw new \Exception("Falha ao salvar informações da imagem no banco de dados");
                }

                \Log::info("Imagem processada com sucesso", [
                    'nome_original' => $nomeOriginal,
                    'caminho' => $caminho,
                    'tamanho_mb' => round($tamanhoMB, 2),
                    'relatorio_id' => $relatorio->id
                ]);

            } catch (\Exception $e) {
                \Log::error("Erro ao processar imagem", [
                    'index' => $index,
                    'nome_original' => $imagem->getClientOriginalName() ?? 'desconhecido',
                    'erro' => $e->getMessage(),
                    'relatorio_id' => $relatorio->id
                ]);
                
                // Re-throw a exceção para que seja capturada pelo controller
                throw new \Exception("Erro ao processar a imagem '{$imagem->getClientOriginalName()}': " . $e->getMessage());
            }
        }
    }

    /**
     * Processar e salvar imagens do histórico
     */
    private function processarImagensHistorico($imagens, Relatorio $relatorio, \App\Models\RelatorioHistorico $historico)
    {
        foreach ($imagens as $index => $imagem) {
            try {
                // Validações adicionais
                if (!$imagem->isValid()) {
                    \Log::error("Imagem de histórico inválida no índice {$index}", [
                        'error' => $imagem->getError(),
                        'original_name' => $imagem->getClientOriginalName(),
                        'relatorio_id' => $relatorio->id,
                        'historico_id' => $historico->id
                    ]);
                    throw new \Exception("Arquivo de imagem inválido: {$imagem->getClientOriginalName()}");
                }

                // Verificar se o arquivo foi realmente enviado
                if (!$imagem->isFile()) {
                    throw new \Exception("Arquivo não foi enviado corretamente");
                }

                // Verificar tamanho do arquivo
                $tamanhoMB = $imagem->getSize() / 1024 / 1024;
                if ($tamanhoMB > 7) {
                    throw new \Exception("Imagem {$imagem->getClientOriginalName()} excede o limite de 7MB (atual: {$tamanhoMB}MB)");
                }

                $nomeOriginal = $imagem->getClientOriginalName();
                $extensao = $imagem->getClientOriginalExtension();
                $nomeArquivo = Str::uuid() . '.' . $extensao;
                $diretorioHistorico = "relatorios/{$relatorio->id}/historico/{$historico->id}";
                $caminhoArquivo = "{$diretorioHistorico}/{$nomeArquivo}";

                // Criar diretório se não existir
                if (!Storage::disk('public')->exists($diretorioHistorico)) {
                    $created = Storage::disk('public')->makeDirectory($diretorioHistorico);
                    if (!$created) {
                        throw new \Exception("Não foi possível criar o diretório: {$diretorioHistorico}");
                    }
                }

                // Verificar se o diretório é gravável
                $fullPath = storage_path('app/public/' . $diretorioHistorico);
                if (!is_writable($fullPath)) {
                    throw new \Exception("Diretório não é gravável: {$fullPath}");
                }

                // Salvar arquivo
                $caminho = $imagem->storeAs($diretorioHistorico, $nomeArquivo, 'public');
                
                if (!$caminho) {
                    throw new \Exception("Falha ao salvar o arquivo no storage");
                }

                // Verificar se o arquivo foi realmente salvo
                if (!Storage::disk('public')->exists($caminho)) {
                    throw new \Exception("Arquivo não foi encontrado após o salvamento: {$caminho}");
                }

                // Salvar no banco (vinculado ao histórico)
                $imagemModel = RelatorioImagem::create([
                    'relatorio_id' => $relatorio->id,
                    'historico_id' => $historico->id,
                    'nome_arquivo' => $nomeArquivo,
                    'nome_original' => $nomeOriginal,
                    'caminho_arquivo' => $caminho,
                    'tamanho_arquivo' => $imagem->getSize(),
                    'tipo_mime' => $imagem->getMimeType(),
                    'tenant_id' => 1 // Temporário
                ]);

                if (!$imagemModel) {
                    // Se não conseguiu salvar no banco, remover o arquivo
                    Storage::disk('public')->delete($caminho);
                    throw new \Exception("Falha ao salvar informações da imagem no banco de dados");
                }

                \Log::info("Imagem de histórico processada com sucesso", [
                    'nome_original' => $nomeOriginal,
                    'caminho' => $caminho,
                    'tamanho_mb' => round($tamanhoMB, 2),
                    'relatorio_id' => $relatorio->id,
                    'historico_id' => $historico->id
                ]);

            } catch (\Exception $e) {
                \Log::error("Erro ao processar imagem de histórico", [
                    'index' => $index,
                    'nome_original' => $imagem->getClientOriginalName() ?? 'desconhecido',
                    'erro' => $e->getMessage(),
                    'relatorio_id' => $relatorio->id,
                    'historico_id' => $historico->id
                ]);
                
                // Re-throw a exceção para que seja capturada pelo controller
                throw new \Exception("Erro ao processar a imagem '{$imagem->getClientOriginalName()}': " . $e->getMessage());
            }
        }
    }

    /**
     * Remove uma imagem específica via AJAX
     */
    public function removerImagem(RelatorioImagem $imagem)
    {
        // Verifica se a imagem pertence a um relatório que o usuário pode editar
        if ($imagem->relatorio->usuario_id !== auth()->id() && !$this->isAdminOrSupervisor()) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $imagem->deletarArquivo();

        return response()->json(['success' => true]);
    }

    /**
     * Lista os usuários atribuídos a um relatório
     */
    public function atribuicoes(Relatorio $relatorio)
    {
        $relatorio->load('usuariosAtribuidos');
        return response()->json($relatorio->usuariosAtribuidos);
    }

    /**
     * Atribui um usuário ao relatório
     */
    public function atribuirUsuario(Request $request, Relatorio $relatorio)
    {
        $user = auth()->user();
        // Só criador ou admin pode atribuir, e status deve ser pendente/em_andamento
        if (!($relatorio->usuario_id === $user->id || $user->isAdmin()) || !in_array($relatorio->status, ['pendente', 'em_andamento'])) {
            return redirect()->route('relatorios.show', $relatorio)->with('error', 'Ação não permitida.');
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $usuarioId = $request->user_id;
        // Evita duplicidade
        if ($relatorio->usuariosAtribuidos()->where('user_id', $usuarioId)->exists()) {
            return redirect()->route('relatorios.show', $relatorio)->with('error', 'Usuário já atribuído.');
        }
        $relatorio->usuariosAtribuidos()->attach($usuarioId, [
            'permissao' => 'edicao',
            'atribuido_por' => $user->id,
            'atribuido_em' => now(),
        ]);
        return redirect()->route('relatorios.show', $relatorio)->with('success', 'Usuário atribuído com sucesso.');
    }

    /**
     * Remove um usuário atribuído do relatório
     */
    public function removerAtribuicao(Request $request, Relatorio $relatorio, $userId)
    {
        $user = auth()->user();
        if (!($relatorio->usuario_id === $user->id || $user->isAdmin()) || !in_array($relatorio->status, ['pendente', 'em_andamento'])) {
            return redirect()->route('relatorios.show', $relatorio)->with('error', 'Ação não permitida.');
        }
        $relatorio->usuariosAtribuidos()->detach($userId);
        return redirect()->route('relatorios.show', $relatorio)->with('success', 'Atribuição removida com sucesso.');
    }
} 