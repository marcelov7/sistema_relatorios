<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InspecaoGerador;
use App\Models\Relatorio;
use App\Models\Analisador;
use Barryvdh\DomPDF\Facade\Pdf;

class TestarPDF extends Command
{
    protected $signature = 'test:pdf';
    protected $description = 'Testar geração de PDFs';

    public function handle()
    {
        $this->info('Testando geração de PDFs...');

        // Contar registros
        $inspecoes = InspecaoGerador::count();
        $relatorios = Relatorio::count();
        $analisadores = Analisador::count();

        $this->info("Inspeções: {$inspecoes}");
        $this->info("Relatórios: {$relatorios}");
        $this->info("Analisadores: {$analisadores}");

        // Testar PDF simples
        try {
            $html = '<html><body><h1>Teste PDF</h1><p>Este é um teste de geração de PDF.</p></body></html>';
            
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'Arial',
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                ]);

            $pdfContent = $pdf->output();
            
            if (strlen($pdfContent) > 0) {
                $this->info('✅ PDF simples gerado com sucesso!');
            } else {
                $this->error('❌ Falha na geração do PDF simples');
            }
        } catch (\Exception $e) {
            $this->error('❌ Erro na geração do PDF: ' . $e->getMessage());
        }

        // Testar com dados reais se existirem
        if ($inspecoes > 0) {
            try {
                $inspecao = InspecaoGerador::first();
                if ($inspecao) {
                    $pdf = Pdf::loadView('pdf.teste-simples', compact('inspecao'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions([
                            'defaultFont' => 'sans-serif',
                            'isRemoteEnabled' => false,
                            'isHtml5ParserEnabled' => false,
                        ]);

                    $pdfContent = $pdf->output();
                    
                    if (strlen($pdfContent) > 0) {
                        $this->info('✅ PDF de inspeção simples gerado com sucesso!');
                    } else {
                        $this->error('❌ Falha na geração do PDF de inspeção simples');
                    }
                }
            } catch (\Exception $e) {
                $this->error('❌ Erro na geração do PDF de inspeção simples: ' . $e->getMessage());
            }

            // Testar template real de inspeção
            try {
                $inspecao = InspecaoGerador::first();
                if ($inspecao) {
                    $pdf = Pdf::loadView('pdf.inspecao', compact('inspecao'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions([
                            'defaultFont' => 'sans-serif',
                            'isRemoteEnabled' => false,
                            'isHtml5ParserEnabled' => false,
                        ]);

                    $pdfContent = $pdf->output();
                    
                    if (strlen($pdfContent) > 0) {
                        $this->info('✅ PDF de inspeção real gerado com sucesso!');
                    } else {
                        $this->error('❌ Falha na geração do PDF de inspeção real');
                    }
                }
            } catch (\Exception $e) {
                $this->error('❌ Erro na geração do PDF de inspeção real: ' . $e->getMessage());
            }
        }

        // Testar relatório se existir
        if ($relatorios > 0) {
            try {
                $relatorio = Relatorio::first();
                if ($relatorio) {
                    $pdf = Pdf::loadView('pdf.relatorio', compact('relatorio'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions([
                            'defaultFont' => 'sans-serif',
                            'isRemoteEnabled' => false,
                            'isHtml5ParserEnabled' => false,
                        ]);

                    $pdfContent = $pdf->output();
                    
                    if (strlen($pdfContent) > 0) {
                        $this->info('✅ PDF de relatório gerado com sucesso!');
                    } else {
                        $this->error('❌ Falha na geração do PDF de relatório');
                    }
                }
            } catch (\Exception $e) {
                $this->error('❌ Erro na geração do PDF de relatório: ' . $e->getMessage());
            }
        }

        // Testar analisador se existir
        if ($analisadores > 0) {
            try {
                $analisador = Analisador::first();
                if ($analisador) {
                    $pdf = Pdf::loadView('pdf.analisador', compact('analisador'))
                        ->setPaper('a4', 'portrait')
                        ->setOptions([
                            'defaultFont' => 'sans-serif',
                            'isRemoteEnabled' => false,
                            'isHtml5ParserEnabled' => false,
                        ]);

                    $pdfContent = $pdf->output();
                    
                    if (strlen($pdfContent) > 0) {
                        $this->info('✅ PDF de analisador gerado com sucesso!');
                    } else {
                        $this->error('❌ Falha na geração do PDF de analisador');
                    }
                }
            } catch (\Exception $e) {
                $this->error('❌ Erro na geração do PDF de analisador: ' . $e->getMessage());
            }
        }

        $this->info('Teste concluído!');
    }
} 