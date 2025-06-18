<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\Equipamento;
use App\Models\Local;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal de análises
     */
    public function index(Request $request)
    {
        // Filtros de data
        $dataInicio = $request->get('data_inicio', now()->subMonths(3)->format('Y-m-d'));
        $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
        
        // Validar datas
        try {
            $dataInicio = Carbon::parse($dataInicio);
            $dataFim = Carbon::parse($dataFim)->endOfDay();
        } catch (\Exception $e) {
            $dataInicio = now()->subMonths(3);
            $dataFim = now()->endOfDay();
        }

        // Query base com filtros de data
        $queryBase = Relatorio::with(['equipamento', 'local'])
            ->whereBetween('data_ocorrencia', [$dataInicio, $dataFim]);

        // 1. EQUIPAMENTOS COM MAIS PROBLEMAS
        $equipamentosProblemas = $queryBase->clone()
            ->select('equipamento_id', DB::raw('COUNT(*) as total_problemas'))
            ->whereNotNull('equipamento_id')
            ->groupBy('equipamento_id')
            ->orderByDesc('total_problemas')
            ->limit(10)
            ->with('equipamento')
            ->get()
            ->map(function($item) {
                return [
                    'equipamento' => $item->equipamento ? $item->equipamento->nome : 'Equipamento #' . $item->equipamento_id,
                    'codigo' => $item->equipamento ? $item->equipamento->codigo : 'N/A',
                    'total' => $item->total_problemas,
                    'id' => $item->equipamento_id
                ];
            });

        // 2. ÁREAS/LOCAIS MAIS AFETADOS
        $locaisAfetados = $queryBase->clone()
            ->select('local_id', DB::raw('COUNT(*) as total_problemas'))
            ->whereNotNull('local_id')
            ->groupBy('local_id')
            ->orderByDesc('total_problemas')
            ->limit(10)
            ->with('local')
            ->get()
            ->map(function($item) {
                return [
                    'local' => $item->local ? $item->local->nome : 'Local #' . $item->local_id,
                    'endereco' => $item->local ? $item->local->endereco : 'N/A',
                    'total' => $item->total_problemas,
                    'id' => $item->local_id
                ];
            });

        // 3. EVOLUÇÃO DOS PROBLEMAS POR MÊS
        $evolucaoMensal = $queryBase->clone()
            ->select(
                DB::raw('YEAR(data_ocorrencia) as ano'),
                DB::raw('MONTH(data_ocorrencia) as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "resolvido" THEN 1 ELSE 0 END) as resolvidos'),
                DB::raw('SUM(CASE WHEN status = "pendente" THEN 1 ELSE 0 END) as pendentes'),
                DB::raw('SUM(CASE WHEN status = "em_andamento" THEN 1 ELSE 0 END) as em_andamento')
            )
            ->groupBy('ano', 'mes')
            ->orderBy('ano')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                return [
                    'periodo' => $item->ano . '-' . str_pad($item->mes, 2, '0', STR_PAD_LEFT),
                    'mes_ano' => Carbon::create($item->ano, $item->mes)->format('M/Y'),
                    'total' => $item->total,
                    'resolvidos' => $item->resolvidos,
                    'pendentes' => $item->pendentes,
                    'em_andamento' => $item->em_andamento
                ];
            });

        // 4. DISTRIBUIÇÃO POR STATUS
        $distribuicaoStatus = $queryBase->clone()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                $labels = [
                    'pendente' => 'Pendente',
                    'em_andamento' => 'Em Andamento',
                    'resolvido' => 'Resolvido'
                ];
                $colors = [
                    'pendente' => '#ffc107',
                    'em_andamento' => '#17a2b8',
                    'resolvido' => '#28a745'
                ];
                return [
                    'status' => $labels[$item->status] ?? $item->status,
                    'total' => $item->total,
                    'color' => $colors[$item->status] ?? '#6c757d'
                ];
            });

        // 5. DISTRIBUIÇÃO POR PRIORIDADE
        $distribuicaoPrioridade = $queryBase->clone()
            ->select('prioridade', DB::raw('COUNT(*) as total'))
            ->groupBy('prioridade')
            ->get()
            ->map(function($item) {
                $labels = [
                    'baixa' => 'Baixa',
                    'media' => 'Média',
                    'alta' => 'Alta',
                    'critica' => 'Crítica'
                ];
                $colors = [
                    'baixa' => '#28a745',
                    'media' => '#ffc107',
                    'alta' => '#fd7e14',
                    'critica' => '#dc3545'
                ];
                return [
                    'prioridade' => $labels[$item->prioridade] ?? $item->prioridade,
                    'total' => $item->total,
                    'color' => $colors[$item->prioridade] ?? '#6c757d'
                ];
            });

        // 6. ESTATÍSTICAS RESUMO
        $totalRelatorios = $queryBase->clone()->count();
        $estatisticas = [
            'total_relatorios' => $totalRelatorios,
            'resolvidos' => $queryBase->clone()->where('status', 'resolvido')->count(),
            'pendentes' => $queryBase->clone()->where('status', 'pendente')->count(),
            'em_andamento' => $queryBase->clone()->where('status', 'em_andamento')->count(),
            'equipamentos_afetados' => $queryBase->clone()->distinct('equipamento_id')->count('equipamento_id'),
            'locais_afetados' => $queryBase->clone()->distinct('local_id')->count('local_id'),
            'periodo_dias' => (int) $dataInicio->diffInDays($dataFim),
            'media_diaria' => $totalRelatorios > 0 ? round($totalRelatorios / max(1, (int) $dataInicio->diffInDays($dataFim)), 1) : 0
        ];

        // 7. TOP 5 EQUIPAMENTOS CRÍTICOS (prioridade alta/crítica)
        $equipamentosCriticos = $queryBase->clone()
            ->whereIn('prioridade', ['alta', 'critica'])
            ->select('equipamento_id', DB::raw('COUNT(*) as problemas_criticos'))
            ->whereNotNull('equipamento_id')
            ->groupBy('equipamento_id')
            ->orderByDesc('problemas_criticos')
            ->limit(5)
            ->with('equipamento')
            ->get()
            ->map(function($item) {
                return [
                    'equipamento' => $item->equipamento ? $item->equipamento->nome : 'Equipamento #' . $item->equipamento_id,
                    'total' => $item->problemas_criticos
                ];
            });

        return view('analytics.dashboard', compact(
            'equipamentosProblemas',
            'locaisAfetados', 
            'evolucaoMensal',
            'distribuicaoStatus',
            'distribuicaoPrioridade',
            'estatisticas',
            'equipamentosCriticos',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * API para obter dados de gráficos em JSON
     */
    public function getDadosGraficos(Request $request)
    {
        $tipo = $request->get('tipo');
        $dataInicio = Carbon::parse($request->get('data_inicio', now()->subMonths(3)));
        $dataFim = Carbon::parse($request->get('data_fim', now()))->endOfDay();

        $queryBase = Relatorio::with(['equipamento', 'local'])
            ->whereBetween('data_ocorrencia', [$dataInicio, $dataFim]);

        switch ($tipo) {
            case 'equipamentos_problemas':
                return response()->json(
                    $queryBase->select('equipamento_id', DB::raw('COUNT(*) as total'))
                        ->whereNotNull('equipamento_id')
                        ->groupBy('equipamento_id')
                        ->orderByDesc('total')
                        ->limit(15)
                        ->with('equipamento')
                        ->get()
                        ->map(function($item) {
                            return [
                                'label' => $item->equipamento ? $item->equipamento->nome : 'Equipamento #' . $item->equipamento_id,
                                'value' => $item->total
                            ];
                        })
                );

            case 'locais_afetados':
                return response()->json(
                    $queryBase->select('local_id', DB::raw('COUNT(*) as total'))
                        ->whereNotNull('local_id')
                        ->groupBy('local_id')
                        ->orderByDesc('total')
                        ->limit(15)
                        ->with('local')
                        ->get()
                        ->map(function($item) {
                            return [
                                'label' => $item->local ? $item->local->nome : 'Local #' . $item->local_id,
                                'value' => $item->total
                            ];
                        })
                );

            case 'evolucao_temporal':
                return response()->json(
                    $queryBase->select(
                        DB::raw('DATE(data_ocorrencia) as data'),
                        DB::raw('COUNT(*) as total')
                    )
                    ->groupBy('data')
                    ->orderBy('data')
                    ->get()
                    ->map(function($item) {
                        return [
                            'data' => $item->data,
                            'total' => $item->total
                        ];
                    })
                );

            default:
                return response()->json(['error' => 'Tipo de gráfico não encontrado'], 400);
        }
    }
}
