<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisador extends Model
{
    use HasFactory;

    protected $table = 'analisadores';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'analyzer',
        'check_date',
        'acid_filter',
        'gas_dryer',
        'paper_filter',
        'peristaltic_pump',
        'rotameter',
        'disposable_filter',
        'blocking_filter',
        'room_temperature',
        'air_pressure',
        'observation',
        'image',
        'ativo'
    ];

    protected $casts = [
        'check_date' => 'date',
        'acid_filter' => 'boolean',
        'gas_dryer' => 'boolean',
        'paper_filter' => 'boolean',
        'peristaltic_pump' => 'boolean',
        'rotameter' => 'boolean',
        'disposable_filter' => 'boolean',
        'blocking_filter' => 'boolean',
        'room_temperature' => 'decimal:2',
        'air_pressure' => 'decimal:2',
        'ativo' => 'boolean',
        'tenant_id' => 'integer',
        'user_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
                $model->tenant_id = 1; // Temporário
            }
        });
    }

    /**
     * Relacionamento com Usuário
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relacionamento com Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
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
        return $this->ativo ? 'bg-success' : 'bg-danger';
    }

    public function getStatusLabelAttribute()
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    /**
     * Verifica se todos os filtros estão funcionando
     */
    public function getTodosComponentesOkAttribute()
    {
        return $this->acid_filter && 
               $this->gas_dryer && 
               $this->paper_filter && 
               $this->peristaltic_pump && 
               $this->rotameter && 
               $this->disposable_filter && 
               $this->blocking_filter;
    }

    /**
     * Conta quantos componentes estão com problema
     */
    public function getComponentesComProblemaAttribute()
    {
        $componentes = [
            'acid_filter', 'gas_dryer', 'paper_filter', 
            'peristaltic_pump', 'rotameter', 'disposable_filter', 'blocking_filter'
        ];
        
        return collect($componentes)->filter(function($componente) {
            return !$this->$componente;
        })->count();
    }

    /**
     * Lista dos componentes para verificação
     */
    public static function getComponentes()
    {
        return [
            'acid_filter' => 'Filtro Ácido',
            'gas_dryer' => 'Secador de Gás',
            'paper_filter' => 'Filtro de Papel',
            'peristaltic_pump' => 'Bomba Peristáltica',
            'rotameter' => 'Rotâmetro',
            'disposable_filter' => 'Filtro Descartável',
            'blocking_filter' => 'Filtro de Bloqueio'
        ];
    }

    /**
     * Tipos de analisadores
     */
    public static function getTiposAnalisadores()
    {
        return [
            'TORRE' => 'Torre',
            'CHAMINE' => 'Chaminé',
            'CAIXA DE FUMAÇA' => 'Caixa de Fumaça'
        ];
    }

    /**
     * Verifica se o analisador é novo (criado nas últimas 24h)
     */
    public function getIsNovoAttribute()
    {
        return $this->created_at->diffInHours(now()) <= 24;
    }

    /**
     * Scope para analisadores novos (últimas 24h)
     */
    public function scopeNovos($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }
}
