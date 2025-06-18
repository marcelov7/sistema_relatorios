<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relatorio;
use App\Models\InspecaoGerador;
use App\Models\Analisador;
use App\Models\Equipamento;
use App\Models\Local;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use App\Models\Usuario;

class PDFController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Página principal para geração de PDFs
     */
    public function index()
    {
        // Estatísticas para a página
        $stats = [
            'relatorios' => Relatorio::count(),
            'inspecoes' => InspecaoGerador::count(),
            'analisadores' => Analisador::count(),
            'equipamentos' => Equipamento::count(),
            'locais' => Local::count(),
        ];

        return view('pdf.index', compact('stats'));
    }

    /**
     * Gerar PDF de um relatório específico
     */
    public function relatorio(Relatorio $relatorio)
    {
        $relatorio->load(['usuario', 'local', 'equipamento', 'imagens', 'historicos.usuario']);

        $pdf = Pdf::loadView('pdf.relatorio', compact('relatorio'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'relatorio_' . $relatorio->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Gerar PDF de inspeção individual
     */
    public function inspecao(InspecaoGerador $inspecaoGerador)
    {
        $inspecao = $inspecaoGerador; // Para manter compatibilidade com a view
        
        $pdf = Pdf::loadView('pdf.inspecao', compact('inspecao'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'inspecao_' . $inspecao->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Gerar PDF de analisador individual
     */
    public function analisador(Analisador $analisador)
    {
        $pdf = Pdf::loadView('pdf.analisador', compact('analisador'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'analisador_' . $analisador->id . '_' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Gerar PDF de analytics
     */
    public function analytics(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $dataInicio = Carbon::parse($request->data_inicio);
        $dataFim = Carbon::parse($request->data_fim);

        $queryBase = Relatorio::with(['usuario', 'local', 'equipamento'])
            ->whereBetween('data_ocorrencia', [$dataInicio, $dataFim->endOfDay()]);

        $dados = $this->prepararDadosAnalytics($queryBase, $dataInicio, $dataFim);

        $pdf = Pdf::loadView('pdf.analytics', compact('dados', 'dataInicio', 'dataFim'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'analytics_' . $dataInicio->format('Y-m-d') . '_a_' . $dataFim->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Gerar PDF de relatórios em lote
     */
    public function relatoriosLote(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'status' => 'nullable|in:pendente,em_andamento,resolvido',
            'prioridade' => 'nullable|in:baixa,media,alta,critica',
            'local_id' => 'nullable|exists:locais,id',
            'equipamento_id' => 'nullable|exists:equipamentos,id',
        ]);

        $query = Relatorio::with(['usuario', 'local', 'equipamento'])
            ->whereBetween('data_ocorrencia', [
                Carbon::parse($request->data_inicio),
                Carbon::parse($request->data_fim)->endOfDay()
            ]);

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }
        if ($request->filled('local_id')) {
            $query->where('local_id', $request->local_id);
        }
        if ($request->filled('equipamento_id')) {
            $query->where('equipamento_id', $request->equipamento_id);
        }

        $relatorios = $query->orderBy('data_ocorrencia', 'desc')->get();

        if ($relatorios->isEmpty()) {
            return back()->with('error', 'Nenhum relatório encontrado com os filtros selecionados.');
        }

        $pdf = Pdf::loadView('pdf.relatorios-lote', compact('relatorios', 'request'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'relatorios_lote_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Gerar PDF de inspeções em lote
     */
    public function inspecoesLote(Request $request)
    {
        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'colaborador' => 'nullable|string',
        ]);

        $query = InspecaoGerador::whereNotNull('data')
            ->whereBetween('data', [
                Carbon::parse($request->data_inicio),
                Carbon::parse($request->data_fim)
            ]);

        if ($request->filled('colaborador')) {
            $query->where('colaborador', 'like', '%' . $request->colaborador . '%');
        }

        $inspecoes = $query->orderBy('data', 'desc')->get();

        if ($inspecoes->isEmpty()) {
            return back()->with('error', 'Nenhuma inspeção encontrada com os filtros selecionados.');
        }

        // Filtrar inspeções com dados válidos
        $inspecoes = $inspecoes->filter(function($inspecao) {
            return $inspecao->data !== null;
        });

        if ($inspecoes->isEmpty()) {
            return back()->with('error', 'Nenhuma inspeção com data válida encontrada.');
        }

        $pdf = Pdf::loadView('pdf.inspecoes-lote', compact('inspecoes', 'request'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
            ]);

        $filename = 'inspecoes_lote_' . now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Preparar dados para analytics
     */
    private function prepararDadosAnalytics($queryBase, $dataInicio, $dataFim)
    {
        // Equipamentos com mais problemas
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
                ];
            });

        // Locais mais afetados
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
                ];
            });

        // Distribuição por status
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
                return [
                    'status' => $labels[$item->status] ?? $item->status,
                    'total' => $item->total,
                ];
            });

        // Distribuição por prioridade
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
                return [
                    'prioridade' => $labels[$item->prioridade] ?? $item->prioridade,
                    'total' => $item->total,
                ];
            });

        // Estatísticas resumo
        $totalRelatorios = $queryBase->clone()->count();
        $estatisticas = [
            'total_relatorios' => $totalRelatorios,
            'resolvidos' => $queryBase->clone()->where('status', 'resolvido')->count(),
            'pendentes' => $queryBase->clone()->where('status', 'pendente')->count(),
            'em_andamento' => $queryBase->clone()->where('status', 'em_andamento')->count(),
            'equipamentos_afetados' => $queryBase->clone()->distinct('equipamento_id')->count('equipamento_id'),
            'locais_afetados' => $queryBase->clone()->distinct('local_id')->count('local_id'),
            'periodo_dias' => (int) $dataInicio->diffInDays($dataFim),
        ];

        return [
            'equipamentosProblemas' => $equipamentosProblemas,
            'locaisAfetados' => $locaisAfetados,
            'distribuicaoStatus' => $distribuicaoStatus,
            'distribuicaoPrioridade' => $distribuicaoPrioridade,
            'estatisticas' => $estatisticas,
        ];
    }

    /**
     * Gerar PDF de relatório usando Browsershot (Chrome headless)
     */
    public function gerarRelatorioBrowsershot($id)
    {
        try {
            $relatorio = Relatorio::with(['usuario', 'local', 'equipamento', 'imagens', 'historicos.usuario'])->findOrFail($id);
            
            // Renderizar a view como HTML
            $html = view('pdf.relatorio-browsershot', compact('relatorio'))->render();
            
            // Gerar PDF com Browsershot
            $nomeArquivo = 'relatorio_' . $relatorio->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $caminhoArquivo = storage_path('app/public/' . $nomeArquivo);
            
            Browsershot::html($html)
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4 em mm
                ->margins(10, 10, 10, 10) // margens em mm
                ->waitUntilNetworkIdle()
                ->timeout(60)
                ->setOption('printBackground', true)
                ->save($caminhoArquivo);
            
            return response()->download($caminhoArquivo)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF de inspeção usando Browsershot (Chrome headless)
     */
    public function gerarInspecaoBrowsershot($id)
    {
        try {
            $inspecao = InspecaoGerador::with(['usuario'])->findOrFail($id);
            
            // Renderizar a view como HTML
            $html = view('pdf.inspecao-browsershot', compact('inspecao'))->render();
            
            // Gerar PDF com Browsershot
            $nomeArquivo = 'inspecao_' . $inspecao->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $caminhoArquivo = storage_path('app/public/' . $nomeArquivo);
            
            Browsershot::html($html)
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4 em mm
                ->margins(10, 10, 10, 10) // margens em mm
                ->waitUntilNetworkIdle()
                ->timeout(60)
                ->setOption('printBackground', true)
                ->save($caminhoArquivo);
            
            return response()->download($caminhoArquivo)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF de analisador usando Browsershot (Chrome headless)
     */
    public function gerarAnalisadorBrowsershot($id)
    {
        try {
            $analisador = Analisador::with(['usuario'])->findOrFail($id);
            
            // Renderizar a view como HTML
            $html = view('pdf.analisador-browsershot', compact('analisador'))->render();
            
            // Gerar PDF com Browsershot
            $nomeArquivo = 'analisador_' . $analisador->id . '_' . date('Y-m-d_H-i-s') . '.pdf';
            $caminhoArquivo = storage_path('app/public/' . $nomeArquivo);
            
            Browsershot::html($html)
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4 em mm
                ->margins(10, 10, 10, 10) // margens em mm
                ->waitUntilNetworkIdle()
                ->timeout(60)
                ->setOption('printBackground', true)
                ->save($caminhoArquivo);
            
            return response()->download($caminhoArquivo)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }

    /**
     * Gerar PDF de analytics usando Browsershot (Chrome headless)
     */
    public function gerarAnalyticsBrowsershot(Request $request)
    {
        try {
            $request->validate([
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
            ]);

            $dataInicio = Carbon::parse($request->data_inicio);
            $dataFim = Carbon::parse($request->data_fim);

            $queryBase = Relatorio::with(['usuario', 'local', 'equipamento'])
                ->whereBetween('data_ocorrencia', [$dataInicio, $dataFim->endOfDay()]);

            $dados = $this->prepararDadosAnalytics($queryBase, $dataInicio, $dataFim);
            
            // Renderizar a view como HTML
            $html = view('pdf.analytics-browsershot', compact('dados', 'dataInicio', 'dataFim'))->render();
            
            // Gerar PDF com Browsershot
            $nomeArquivo = 'analytics_' . $dataInicio->format('Y-m-d') . '_a_' . $dataFim->format('Y-m-d') . '_' . date('H-i-s') . '.pdf';
            $caminhoArquivo = storage_path('app/public/' . $nomeArquivo);
            
            Browsershot::html($html)
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4 em mm
                ->margins(10, 10, 10, 10) // margens em mm
                ->waitUntilNetworkIdle()
                ->timeout(60)
                ->setOption('printBackground', true)
                ->save($caminhoArquivo);
            
            return response()->download($caminhoArquivo)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao gerar PDF: ' . $e->getMessage());
        }
    }
}
