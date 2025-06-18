<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipamentos';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'local_id',
        'tipo',
        'fabricante',
        'modelo',
        'numero_serie',
        'data_instalacao',
        'status_operacional',
        'ativo',
        'tenant_id'
    ];

    protected $casts = [
        'data_instalacao' => 'date',
        'ativo' => 'boolean',
        'tenant_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

    // Status operacionais disponíveis
    const STATUS_OPERANDO = 'operando';
    const STATUS_MANUTENCAO = 'manutencao';
    const STATUS_INATIVO = 'inativo';

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tenant_id = 1; // Temporário
        });
    }

    /**
     * Relacionamento com Local
     */
    public function local()
    {
        return $this->belongsTo(Local::class, 'local_id');
    }

    /**
     * Relacionamento com Relatórios
     */
    public function relatorios()
    {
        return $this->hasMany(Relatorio::class, 'equipamento_id');
    }

    /**
     * Scopes
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeInativos($query)
    {
        return $query->where('ativo', false);
    }

    public function scopeOperando($query)
    {
        return $query->where('status_operacional', self::STATUS_OPERANDO);
    }

    public function scopeEmManutencao($query)
    {
        return $query->where('status_operacional', self::STATUS_MANUTENCAO);
    }

    public function scopeInativo($query)
    {
        return $query->where('status_operacional', self::STATUS_INATIVO);
    }

    public function scopeTenant($query, $tenantId = null)
    {
        $tenantId = $tenantId ?: 1; // Temporário
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_OPERANDO => 'bg-success',
            self::STATUS_MANUTENCAO => 'bg-warning',
            self::STATUS_INATIVO => 'bg-danger'
        ];

        return $badges[$this->status_operacional] ?? 'bg-secondary';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_OPERANDO => 'Operando',
            self::STATUS_MANUTENCAO => 'Manutenção',
            self::STATUS_INATIVO => 'Inativo'
        ];

        return $labels[$this->status_operacional] ?? 'Indefinido';
    }

    public function getAtivoLabelAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    public function getAtivoBadgeAttribute()
    {
        return $this->ativo ? 'bg-success' : 'bg-secondary';
    }

    /**
     * Método para contagem de relatórios
     */
    public function getTotalRelatoriosAttribute()
    {
        return $this->relatorios()->count();
    }

    /**
     * Verifica se pode ser excluído
     */
    public function podeSerExcluido()
    {
        return $this->relatorios()->count() === 0;
    }

    /**
     * Métodos auxiliares para dados formatados
     */
    public function getCodigoFormatadoAttribute()
    {
        return $this->codigo ?: 'Não informado';
    }

    public function getFabricanteModeloAttribute()
    {
        $partes = array_filter([$this->fabricante, $this->modelo]);
        return implode(' - ', $partes) ?: 'Não informado';
    }

    public function getIdadeEquipamentoAttribute()
    {
        if (!$this->data_instalacao) {
            return 'Data não informada';
        }

        $anos = $this->data_instalacao->diffInYears(now());
        $meses = $this->data_instalacao->diffInMonths(now()) % 12;

        if ($anos > 0) {
            return $anos . ' ano(s)' . ($meses > 0 ? ' e ' . $meses . ' mês(es)' : '');
        }

        return $meses . ' mês(es)';
    }

    /**
     * Métodos estáticos para options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_OPERANDO => 'Operando',
            self::STATUS_MANUTENCAO => 'Manutenção',
            self::STATUS_INATIVO => 'Inativo'
        ];
    }

    /**
     * Verificar se está em condições operacionais
     */
    public function estaOperacional()
    {
        return $this->ativo && $this->status_operacional === self::STATUS_OPERANDO;
    }
} 