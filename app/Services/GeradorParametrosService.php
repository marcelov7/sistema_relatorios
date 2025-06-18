<?php

namespace App\Services;

class GeradorParametrosService
{
    /**
     * Parâmetros normais baseados na imagem fornecida
     */
    public static function getParametrosNormais(): array
    {
        return [
            // Tensões Elétricas (V)
            'tensao_sync_gerador' => [
                'min' => 11.0,
                'max' => 13.0,
                'ideal' => 12.0,
                'unidade' => 'V',
                'nome' => 'Tensão Sync Gerador'
            ],
            'tensao_sync_rede' => [
                'min' => 11.0,
                'max' => 13.0,
                'ideal' => 12.0,
                'unidade' => 'V',
                'nome' => 'Tensão Sync Rede'
            ],
            'tensao_a' => [
                'min' => 210.0,
                'max' => 240.0,
                'ideal' => 227.0,
                'unidade' => 'V',
                'nome' => 'Tensão Fase A'
            ],
            'tensao_b' => [
                'min' => 210.0,
                'max' => 240.0,
                'ideal' => 227.0,
                'unidade' => 'V',
                'nome' => 'Tensão Fase B'
            ],
            'tensao_c' => [
                'min' => 210.0,
                'max' => 240.0,
                'ideal' => 227.0,
                'unidade' => 'V',
                'nome' => 'Tensão Fase C'
            ],
            'tensao_bateria' => [
                'min' => 20.0,
                'max' => 26.0,
                'ideal' => 24.0,
                'unidade' => 'V',
                'nome' => 'Tensão da Bateria'
            ],
            'tensao_alternador' => [
                'min' => 100.0,
                'max' => 120.0,
                'ideal' => 111.0,
                'unidade' => 'V',
                'nome' => 'Tensão do Alternador'
            ],
            
            // Medições Operacionais
            'temp_agua' => [
                'min' => 20.0,
                'max' => 90.0,
                'ideal' => 80.0,
                'critico_max' => 95.0,
                'unidade' => '°C',
                'nome' => 'Temperatura da Água'
            ],
            'pressao_oleo' => [
                'min' => 3.0,
                'max' => 6.0,
                'ideal' => 4.5,
                'critico_min' => 2.0,
                'unidade' => 'bar',
                'nome' => 'Pressão do Óleo'
            ],
            'frequencia' => [
                'min' => 59.5,
                'max' => 60.5,
                'ideal' => 60.0,
                'unidade' => 'Hz',
                'nome' => 'Frequência'
            ],
            'rpm' => [
                'min' => 1750,
                'max' => 1850,
                'ideal' => 1800,
                'unidade' => 'RPM',
                'nome' => 'Rotação por Minuto'
            ]
        ];
    }

    /**
     * Validar um parâmetro específico
     */
    public static function validarParametro(string $parametro, $valor): array
    {
        $parametros = self::getParametrosNormais();
        
        if (!isset($parametros[$parametro]) || $valor === null) {
            return [
                'status' => 'indefinido',
                'classe' => 'text-muted',
                'badge' => 'bg-secondary',
                'icone' => 'bi-question-circle',
                'mensagem' => 'Não informado'
            ];
        }

        $config = $parametros[$parametro];
        $valor = (float) $valor;

        // Verificar se está na faixa crítica
        if (isset($config['critico_min']) && $valor < $config['critico_min']) {
            return [
                'status' => 'critico',
                'classe' => 'text-danger',
                'badge' => 'bg-danger',
                'icone' => 'bi-exclamation-triangle-fill',
                'mensagem' => 'CRÍTICO - Abaixo do mínimo seguro'
            ];
        }

        if (isset($config['critico_max']) && $valor > $config['critico_max']) {
            return [
                'status' => 'critico',
                'classe' => 'text-danger',
                'badge' => 'bg-danger',
                'icone' => 'bi-exclamation-triangle-fill',
                'mensagem' => 'CRÍTICO - Acima do máximo seguro'
            ];
        }

        // Verificar se está na faixa normal
        if ($valor >= $config['min'] && $valor <= $config['max']) {
            return [
                'status' => 'normal',
                'classe' => 'text-success',
                'badge' => 'bg-success',
                'icone' => 'bi-check-circle-fill',
                'mensagem' => 'NORMAL'
            ];
        }

        // Verificar se está em atenção (fora da faixa normal mas não crítico)
        $tolerancia = ($config['max'] - $config['min']) * 0.1; // 10% de tolerância
        
        if ($valor >= ($config['min'] - $tolerancia) && $valor <= ($config['max'] + $tolerancia)) {
            return [
                'status' => 'atencao',
                'classe' => 'text-warning',
                'badge' => 'bg-warning',
                'icone' => 'bi-exclamation-triangle',
                'mensagem' => 'ATENÇÃO - Fora da faixa ideal'
            ];
        }

        // Fora de qualquer faixa aceitável
        return [
            'status' => 'anormal',
            'classe' => 'text-danger',
            'badge' => 'bg-danger',
            'icone' => 'bi-x-circle-fill',
            'mensagem' => 'ANORMAL - Verificar imediatamente'
        ];
    }

    /**
     * Validar todos os parâmetros de uma inspeção
     */
    public static function validarInspecao($inspecao): array
    {
        $parametros = self::getParametrosNormais();
        $resultados = [];
        $resumo = [
            'normal' => 0,
            'atencao' => 0,
            'anormal' => 0,
            'critico' => 0,
            'total' => 0
        ];

        foreach ($parametros as $campo => $config) {
            if (isset($inspecao->$campo) && $inspecao->$campo !== null) {
                $validacao = self::validarParametro($campo, $inspecao->$campo);
                $resultados[$campo] = array_merge($config, $validacao, [
                    'valor' => $inspecao->$campo
                ]);
                
                $resumo[$validacao['status']]++;
                $resumo['total']++;
            }
        }

        return [
            'parametros' => $resultados,
            'resumo' => $resumo,
            'status_geral' => self::determinarStatusGeral($resumo)
        ];
    }

    /**
     * Determinar status geral da inspeção
     */
    private static function determinarStatusGeral(array $resumo): array
    {
        if ($resumo['critico'] > 0) {
            return [
                'status' => 'critico',
                'classe' => 'text-danger',
                'badge' => 'bg-danger',
                'icone' => 'bi-exclamation-triangle-fill',
                'mensagem' => 'CRÍTICO - Intervenção imediata necessária'
            ];
        }

        if ($resumo['anormal'] > 0) {
            return [
                'status' => 'anormal',
                'classe' => 'text-danger',
                'badge' => 'bg-danger',
                'icone' => 'bi-x-circle-fill',
                'mensagem' => 'ANORMAL - Verificação necessária'
            ];
        }

        if ($resumo['atencao'] > 0) {
            return [
                'status' => 'atencao',
                'classe' => 'text-warning',
                'badge' => 'bg-warning',
                'icone' => 'bi-exclamation-triangle',
                'mensagem' => 'ATENÇÃO - Monitoramento recomendado'
            ];
        }

        if ($resumo['normal'] > 0) {
            return [
                'status' => 'normal',
                'classe' => 'text-success',
                'badge' => 'bg-success',
                'icone' => 'bi-check-circle-fill',
                'mensagem' => 'NORMAL - Todos os parâmetros OK'
            ];
        }

        return [
            'status' => 'indefinido',
            'classe' => 'text-muted',
            'badge' => 'bg-secondary',
            'icone' => 'bi-question-circle',
            'mensagem' => 'Dados insuficientes'
        ];
    }

    /**
     * Obter recomendações baseadas na validação
     */
    public static function obterRecomendacoes($validacao): array
    {
        $recomendacoes = [];
        
        foreach ($validacao['parametros'] as $campo => $dados) {
            if ($dados['status'] === 'critico') {
                $recomendacoes[] = [
                    'prioridade' => 'alta',
                    'parametro' => $dados['nome'],
                    'valor' => $dados['valor'],
                    'descricao' => 'Parar operação e verificar imediatamente - ' . $dados['nome'] . ': ' . $dados['valor'] . $dados['unidade']
                ];
            } elseif ($dados['status'] === 'anormal') {
                $recomendacoes[] = [
                    'prioridade' => 'media',
                    'parametro' => $dados['nome'],
                    'valor' => $dados['valor'],
                    'descricao' => 'Agendar manutenção preventiva - ' . $dados['nome'] . ': ' . $dados['valor'] . $dados['unidade']
                ];
            } elseif ($dados['status'] === 'atencao') {
                $recomendacoes[] = [
                    'prioridade' => 'baixa',
                    'parametro' => $dados['nome'],
                    'valor' => $dados['valor'],
                    'descricao' => 'Monitorar nas próximas inspeções - ' . $dados['nome'] . ': ' . $dados['valor'] . $dados['unidade']
                ];
            }
        }

        return $recomendacoes;
    }
} 