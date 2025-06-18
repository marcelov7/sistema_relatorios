<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Services\GeradorParametrosService;
use App\Models\User;

class InspecaoGerador extends Model
{
    use HasFactory;

    protected $table = 'inspecoes_gerador';

    // Usar campos customizados para timestamps
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'data',
        'colaborador',
        'nivel_oleo',
        'nivel_agua',
        'tensao_sync_gerador',
        'tensao_sync_rede',
        'temp_agua',
        'pressao_oleo',
        'frequencia',
        'tensao_a',
        'tensao_b',
        'tensao_c',
        'rpm',
        'tensao_bateria',
        'tensao_alternador',
        'combustivel_50',
        'iluminacao_sala',
        'observacao',
        'ativo'
    ];

    protected $casts = [
        'data' => 'date',
        'tensao_sync_gerador' => 'decimal:2',
        'tensao_sync_rede' => 'decimal:2',
        'temp_agua' => 'decimal:2',
        'pressao_oleo' => 'decimal:2',
        'frequencia' => 'decimal:2',
        'tensao_a' => 'decimal:2',
        'tensao_b' => 'decimal:2',
        'tensao_c' => 'decimal:2',
        'rpm' => 'integer',
        'tensao_bateria' => 'decimal:2',
        'tensao_alternador' => 'decimal:2',
        'ativo' => 'boolean',
        'criado_em' => 'datetime',
        'atualizado_em' => 'datetime'
    ];

    // Boot method para eventos do modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
                $model->tenant_id = 1; // Temporário
            }
        });
    }

    // Relacionamentos
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorData($query, $dataInicio = null, $dataFim = null)
    {
        if ($dataInicio) {
            $query->where('data', '>=', $dataInicio);
        }
        
        if ($dataFim) {
            $query->where('data', '<=', $dataFim);
        }
        
        return $query;
    }

    public function scopePorColaborador($query, $colaborador)
    {
        if ($colaborador) {
            return $query->where('colaborador', 'like', '%' . $colaborador . '%');
        }
        
        return $query;
    }

    public function scopeComProblemas($query)
    {
        return $query->where(function ($q) {
            $q->where('nivel_oleo', 'Baixo')
              ->orWhere('nivel_agua', 'Baixo')
              ->orWhere('combustivel_50', 'Não')
              ->orWhere('iluminacao_sala', 'Anormal');
        });
    }

    // Accessors
    public function getDataFormatadaAttribute()
    {
        return $this->data ? $this->data->format('d/m/Y') : null;
    }

    public function getDataHoraFormatadaAttribute()
    {
        return $this->criado_em ? $this->criado_em->format('d/m/Y H:i') : null;
    }

    public function getTemProblemasAttribute()
    {
        return $this->temProblemas();
    }

    // Novos métodos de validação de parâmetros
    public function getValidacaoParametrosAttribute()
    {
        return GeradorParametrosService::validarInspecao($this);
    }

    public function getStatusGeralAttribute()
    {
        $validacao = $this->validacao_parametros;
        return $validacao['status_geral'];
    }

    public function getRecomendacoesAttribute()
    {
        $validacao = $this->validacao_parametros;
        return GeradorParametrosService::obterRecomendacoes($validacao);
    }

    public function temParametrosAnormais()
    {
        $validacao = $this->validacao_parametros;
        return $validacao['resumo']['anormal'] > 0 || $validacao['resumo']['critico'] > 0;
    }

    public function temParametrosCriticos()
    {
        $validacao = $this->validacao_parametros;
        return $validacao['resumo']['critico'] > 0;
    }

    public function getParametrosAnormaisAttribute()
    {
        $validacao = $this->validacao_parametros;
        $anormais = [];
        
        foreach ($validacao['parametros'] as $campo => $dados) {
            if (in_array($dados['status'], ['anormal', 'critico', 'atencao'])) {
                $anormais[] = [
                    'nome' => $dados['nome'],
                    'valor' => $dados['valor'],
                    'unidade' => $dados['unidade'],
                    'status' => $dados['status'],
                    'mensagem' => $dados['mensagem'],
                    'classe' => $dados['classe'],
                    'icone' => $dados['icone']
                ];
            }
        }
        
        return $anormais;
    }

    // Métodos estáticos para opções
    public static function getNivelOptions()
    {
        return [
            'Máximo' => 'Máximo',
            'Normal' => 'Normal',
            'Baixo' => 'Baixo'
        ];
    }

    public static function getSimNaoOptions()
    {
        return [
            'Sim' => 'Sim',
            'Não' => 'Não'
        ];
    }

    public static function getIluminacaoOptions()
    {
        return [
            'Normal' => 'Normal',
            'Anormal' => 'Anormal'
        ];
    }

    // Métodos de validação (mantidos para compatibilidade)
    public function temProblemas()
    {
        // Problemas básicos (níveis e equipamentos)
        $problemasBasicos = $this->nivel_oleo === 'Baixo' ||
                           $this->nivel_agua === 'Baixo' ||
                           $this->combustivel_50 === 'Não' ||
                           $this->iluminacao_sala === 'Anormal';

        // Problemas de parâmetros técnicos
        $problemasParametros = $this->temParametrosAnormais();

        return $problemasBasicos || $problemasParametros;
    }

    public function getProblemasAttribute()
    {
        $problemas = [];
        
        // Problemas básicos
        if ($this->nivel_oleo === 'Baixo') {
            $problemas[] = 'Nível de óleo: ' . $this->nivel_oleo;
        }
        
        if ($this->nivel_agua === 'Baixo') {
            $problemas[] = 'Nível de água: ' . $this->nivel_agua;
        }
        
        if ($this->combustivel_50 === 'Não') {
            $problemas[] = 'Combustível abaixo de 50%';
        }
        
        if ($this->iluminacao_sala === 'Anormal') {
            $problemas[] = 'Problema na iluminação da sala';
        }

        // Problemas de parâmetros
        foreach ($this->parametros_anormais as $parametro) {
            $problemas[] = $parametro['nome'] . ': ' . $parametro['mensagem'];
        }
        
        return $problemas;
    }
}
