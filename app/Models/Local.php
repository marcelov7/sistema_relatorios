<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'locais';

    protected $fillable = [
        'nome',
        'descricao', 
        'endereco',
        'ativo',
        'tenant_id'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'tenant_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = 'data_atualizacao';

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
     * Relacionamento com Relatórios
     */
    public function relatorios()
    {
        return $this->hasMany(Relatorio::class, 'local_id');
    }

    /**
     * Relacionamento com Equipamentos (futuro)
     */
    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class, 'local_id');
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
     * Método para contagem de relatórios
     */
    public function getTotalRelatoriosAttribute()
    {
        return $this->relatorios()->count();
    }

    /**
     * Método para contagem de equipamentos
     */
    public function getTotalEquipamentosAttribute()
    {
        return $this->equipamentos()->count();
    }

    /**
     * Verifica se pode ser excluído
     */
    public function podeSerExcluido()
    {
        return $this->relatorios()->count() === 0 && $this->getTotalEquipamentosAttribute() === 0;
    }

    /**
     * Formatar endereço
     */
    public function getEnderecoFormatadoAttribute()
    {
        return $this->endereco ?: 'Não informado';
    }
} 