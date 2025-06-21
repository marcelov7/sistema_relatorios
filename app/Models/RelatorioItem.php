<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'relatorio_id',
        'equipamento_id', 
        'descricao_equipamento',
        'observacoes',
        'status_item',
        'ordem'
    ];

    protected $casts = [
        'ordem' => 'integer'
    ];

    /**
     * Relacionamento com Relatório
     */
    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class);
    }

    /**
     * Relacionamento com Equipamento
     */
    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    /**
     * Scope para ordenar por ordem
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('ordem');
    }

    /**
     * Badge CSS para status do item
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status_item) {
            'pendente' => 'bg-warning',
            'em_andamento' => 'bg-info', 
            'concluido' => 'bg-success',
            default => 'bg-secondary'
        };
    }

    /**
     * Label do status em português
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status_item) {
            'pendente' => 'Pendente',
            'em_andamento' => 'Em Andamento',
            'concluido' => 'Concluído',
            default => 'Indefinido'
        };
    }
}
