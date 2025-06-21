<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Relatorio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'relatorios';

    protected $fillable = [
        'usuario_id',
        'local_id',
        'equipamento_id',
        'data_ocorrencia',
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'progresso',
        'editavel',
        'tenant_id'
    ];

    protected $dates = [
        'data_ocorrencia',
        'deleted_at'
    ];

    protected $casts = [
        'data_ocorrencia' => 'datetime',
        'progresso' => 'integer',
        'editavel' => 'boolean',
        'tenant_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    // Status disponíveis
    const STATUS_PENDENTE = 'pendente';
    const STATUS_EM_ANDAMENTO = 'em_andamento';
    const STATUS_RESOLVIDO = 'resolvido';

    // Prioridades disponíveis
    const PRIORIDADE_BAIXA = 'baixa';
    const PRIORIDADE_MEDIA = 'media';
    const PRIORIDADE_ALTA = 'alta';
    const PRIORIDADE_CRITICA = 'critica';

    /**
     * Scope global para tenant (desabilitado temporariamente)
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->usuario_id = auth()->id();
                // Temporariamente definindo tenant_id como 1 para todos
                $model->tenant_id = 1;
            }
        });
    }

    /**
     * Relacionamentos
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class, 'equipamento_id');
    }

    public function imagens()
    {
        return $this->hasMany(RelatorioImagem::class, 'relatorio_id');
    }

    public function historicos()
    {
        return $this->hasMany(RelatorioHistorico::class, 'relatorio_id')->orderBy('data_atualizacao', 'desc');
    }

    public function itens()
    {
        return $this->hasMany(RelatorioItem::class, 'relatorio_id')->ordenado();
    }

    /**
     * Verifica se é um relatório V2 (múltiplos equipamentos)
     */
    public function isV2()
    {
        try {
            // Verifica se a tabela existe antes de fazer a consulta
            if (!Schema::hasTable('relatorio_itens')) {
                return false;
            }
            
            return DB::table('relatorio_itens')
                     ->where('relatorio_id', $this->id)
                     ->exists();
        } catch (\Exception $e) {
            \Log::error('Erro ao verificar se é relatório V2: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna a rota correta para visualização baseada no tipo
     */
    public function getShowRouteAttribute()
    {
        return $this->isV2() ? route('relatorios-v2.show', $this) : route('relatorios.show', $this);
    }

    /**
     * Retorna a rota correta para edição baseada no tipo
     */
    public function getEditRouteAttribute()
    {
        return $this->isV2() ? route('relatorios-v2.edit', $this) : route('relatorios.edit', $this);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDENTE => 'bg-warning',
            self::STATUS_EM_ANDAMENTO => 'bg-info',
            self::STATUS_RESOLVIDO => 'bg-success'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getPrioridadeBadgeAttribute()
    {
        $badges = [
            self::PRIORIDADE_BAIXA => 'bg-success',
            self::PRIORIDADE_MEDIA => 'bg-warning',
            self::PRIORIDADE_ALTA => 'bg-danger',
            self::PRIORIDADE_CRITICA => 'bg-dark'
        ];

        return $badges[$this->prioridade] ?? 'bg-secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDENTE => 'Pendente',
            self::STATUS_EM_ANDAMENTO => 'Em Andamento',
            self::STATUS_RESOLVIDO => 'Resolvido'
        ];

        return $labels[$this->status] ?? 'Indefinido';
    }

    public function getPrioridadeLabelAttribute()
    {
        $labels = [
            self::PRIORIDADE_BAIXA => 'Baixa',
            self::PRIORIDADE_MEDIA => 'Média',
            self::PRIORIDADE_ALTA => 'Alta',
            self::PRIORIDADE_CRITICA => 'Crítica'
        ];

        return $labels[$this->prioridade] ?? 'Indefinida';
    }

    /**
     * Scopes
     */
    public function scopePendentes($query)
    {
        return $query->where('status', self::STATUS_PENDENTE);
    }

    public function scopeEmAndamento($query)
    {
        return $query->where('status', self::STATUS_EM_ANDAMENTO);
    }

    public function scopeResolvidos($query)
    {
        return $query->where('status', self::STATUS_RESOLVIDO);
    }

    public function scopePrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    public function scopeEditaveis($query)
    {
        return $query->where('editavel', true);
    }

    /**
     * Métodos auxiliares
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDENTE => 'Pendente',
            self::STATUS_EM_ANDAMENTO => 'Em Andamento',
            self::STATUS_RESOLVIDO => 'Resolvido'
        ];
    }

    public static function getPrioridadeOptions()
    {
        return [
            self::PRIORIDADE_BAIXA => 'Baixa',
            self::PRIORIDADE_MEDIA => 'Média',
            self::PRIORIDADE_ALTA => 'Alta',
            self::PRIORIDADE_CRITICA => 'Crítica'
        ];
    }

    /**
     * Verifica se o relatório pode ser editado
     */
    public function podeSerEditado()
    {
        return $this->editavel && $this->status !== self::STATUS_RESOLVIDO;
    }

    /**
     * Atualiza o progresso e status automaticamente
     */
    public function atualizarProgresso($progresso)
    {
        $this->progresso = min(100, max(0, $progresso));
        
        if ($this->progresso == 0) {
            $this->status = self::STATUS_PENDENTE;
        } elseif ($this->progresso == 100) {
            $this->status = self::STATUS_RESOLVIDO;
        } else {
            $this->status = self::STATUS_EM_ANDAMENTO;
        }
        
        $this->save();
    }

    /**
     * Usuários que podem editar o relatório
     */
    public function usuariosAtribuidos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'relatorio_usuarios')
                    ->withPivot('permissao', 'atribuido_em', 'atribuido_por');
    }

    /**
     * Notificações relacionadas ao relatório
     */
    public function notificacoes(): HasMany
    {
        return $this->hasMany(Notificacao::class);
    }

    /**
     * Verifica se um usuário pode editar o relatório
     */
    public function podeEditar(User $user): bool
    {
        // Se o relatório não é editável
        if (!$this->editavel) {
            return false;
        }

        // O criador sempre pode editar (exceto se resolvido)
        if ($this->usuario_id === $user->id && $this->status !== 'resolvido') {
            return true;
        }

        // Admins podem editar qualquer relatório
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se um usuário pode visualizar o relatório
     */
    public function podeVisualizar(User $user): bool
    {
        // O criador sempre pode visualizar
        if ($this->usuario_id === $user->id) {
            return true;
        }

        // Admins e supervisores podem visualizar qualquer relatório
        if ($user->hasAnyRole(['admin', 'supervisor'])) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se o relatório é novo (criado nas últimas 24h)
     */
    public function getIsNovoAttribute(): bool
    {
        return $this->data_criacao >= now()->subHours(24);
    }

    /**
     * Verifica se o relatório foi atualizado recentemente (nas últimas 24h)
     */
    public function getIsAtualizadoRecentementeAttribute(): bool
    {
        return $this->data_atualizacao >= now()->subHours(24) 
               && $this->data_atualizacao != $this->data_criacao;
    }

    /**
     * Scope para relatórios novos
     */
    public function scopeNovos($query, $horas = 24)
    {
        return $query->where('data_criacao', '>=', now()->subHours($horas));
    }

    /**
     * Scope para relatórios atualizados recentemente
     */
    public function scopeAtualizadosRecentemente($query, $horas = 24)
    {
        return $query->where('data_atualizacao', '>=', now()->subHours($horas))
                    ->whereColumn('data_atualizacao', '!=', 'data_criacao');
    }

    /**
     * Mutator para data_ocorrencia - garantir timezone brasileiro
     */
    public function setDataOcorrenciaAttribute($value)
    {
        if ($value) {
            // Converter para timezone brasileiro se necessário
            $date = \Carbon\Carbon::parse($value);
            if ($date->timezone->getName() !== 'America/Sao_Paulo') {
                $date = $date->setTimezone('America/Sao_Paulo');
            }
            $this->attributes['data_ocorrencia'] = $date;
        }
    }

    /**
     * Accessor para data_ocorrencia - sempre retornar em timezone brasileiro
     */
    public function getDataOcorrenciaAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value)->setTimezone('America/Sao_Paulo');
        }
        return $value;
    }
}
