<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioHistorico extends Model
{
    use HasFactory;

    protected $table = 'relatorio_historico';

    protected $fillable = [
        'relatorio_id',
        'usuario_id',
        'status_anterior',
        'status_novo',
        'descricao',
        'progresso',
        'tenant_id'
    ];

    protected $casts = [
        'data_atualizacao' => 'datetime',
        'progresso' => 'integer',
        'tenant_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_atualizacao';
    const UPDATED_AT = null; // Não tem updated_at nesta tabela

    /**
     * Relacionamento com Relatório
     */
    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class, 'relatorio_id');
    }

    /**
     * Relacionamento com Usuário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relacionamento com Imagens do Histórico
     */
    public function imagens()
    {
        return $this->hasMany(RelatorioImagem::class, 'historico_id');
    }

    /**
     * Scope para tenant
     */
    public function scopeTenant($query, $tenantId = null)
    {
        $tenantId = $tenantId ?: (auth()->check() ? 1 : 1); // Temporário
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Accessor para status anterior formatado
     */
    public function getStatusAnteriorLabelAttribute()
    {
        return $this->formatarStatus($this->status_anterior);
    }

    /**
     * Accessor para status novo formatado
     */
    public function getStatusNovoLabelAttribute()
    {
        return $this->formatarStatus($this->status_novo);
    }

    /**
     * Formatar status para exibição
     */
    private function formatarStatus($status)
    {
        $labels = [
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'resolvido' => 'Resolvido'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Verificar se houve mudança de status
     */
    public function getHouveMudancaStatusAttribute()
    {
        return $this->status_anterior !== $this->status_novo;
    }

    /**
     * Obter ícone para o tipo de atualização
     */
    public function getIconeAttribute()
    {
        if ($this->houve_mudanca_status) {
            return match ($this->status_novo) {
                'pendente' => 'bi-clock',
                'em_andamento' => 'bi-gear',
                'resolvido' => 'bi-check-circle',
                default => 'bi-arrow-right'
            };
        }

        return 'bi-bar-chart';
    }

    /**
     * Obter cor para o tipo de atualização
     */
    public function getCorAttribute()
    {
        if ($this->houve_mudanca_status) {
            return match ($this->status_novo) {
                'pendente' => 'warning',
                'em_andamento' => 'info',
                'resolvido' => 'success',
                default => 'secondary'
            };
        }

        return 'primary';
    }

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tenant_id = 1; // Temporário
            $model->usuario_id = auth()->id();
        });
    }
} 