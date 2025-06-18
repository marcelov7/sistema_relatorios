<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'titulo',
        'mensagem',
        'tipo',
        'user_id',
        'relatorio_id',
        'lida',
        'lida_em',
        'dados_extras',
    ];

    protected function casts(): array
    {
        return [
            'lida' => 'boolean',
            'lida_em' => 'datetime',
            'dados_extras' => 'json',
        ];
    }

    /**
     * Usuário que recebe a notificação
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relatório relacionado à notificação
     */
    public function relatorio(): BelongsTo
    {
        return $this->belongsTo(Relatorio::class);
    }

    /**
     * Marca a notificação como lida
     */
    public function marcarComoLida(): void
    {
        $this->update([
            'lida' => true,
            'lida_em' => now(),
        ]);
    }

    /**
     * Scopes
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    public function scopeLidas($query)
    {
        return $query->where('lida', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeRecentes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Constantes para tipos de notificação
     */
    const TIPOS = [
        'relatorio_criado' => 'Relatório Criado',
        'relatorio_atualizado' => 'Relatório Atualizado',
        'relatorio_concluido' => 'Relatório Concluído',
        'relatorio_atribuido' => 'Relatório Atribuído',
        'sistema' => 'Sistema',
    ];
}
