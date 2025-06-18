<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class RelatorioImagem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'relatorio_imagens';

    protected $fillable = [
        'relatorio_id',
        'historico_id',
        'nome_arquivo',
        'nome_original',
        'caminho_arquivo',
        'tamanho_arquivo',
        'tipo_mime',
        'tenant_id'
    ];

    protected $casts = [
        'data_upload' => 'datetime',
        'tamanho_arquivo' => 'integer',
        'tenant_id' => 'integer'
    ];

    // Override timestamps para usar nomes do banco existente
    const CREATED_AT = 'data_upload';
    const UPDATED_AT = null; // Não tem updated_at nesta tabela

    /**
     * Relacionamento com Relatório
     */
    public function relatorio()
    {
        return $this->belongsTo(Relatorio::class, 'relatorio_id');
    }

    /**
     * Relacionamento com Histórico
     */
    public function historico()
    {
        return $this->belongsTo(RelatorioHistorico::class, 'historico_id');
    }

    /**
     * Accessor para URL da imagem
     */
    public function getUrlAttribute()
    {
        if ($this->caminho_arquivo && \Storage::disk('public')->exists($this->caminho_arquivo)) {
            // Gerar URL correta considerando a estrutura do projeto
            $baseUrl = request()->getSchemeAndHttpHost();
            $basePath = str_replace('/public', '', request()->getBasePath());
            
            return $baseUrl . $basePath . '/storage/' . $this->caminho_arquivo;
        }
        
        return null;
    }

    /**
     * Accessor para tamanho formatado
     */
    public function getTamanhoFormatadoAttribute()
    {
        $bytes = $this->tamanho_arquivo;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' B';
        }
    }

    /**
     * Verifica se é uma imagem válida
     */
    public function isImagem()
    {
        return in_array($this->tipo_mime, [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    /**
     * Deleta o arquivo físico junto com o registro
     */
    public function deletarArquivo()
    {
        if ($this->caminho_arquivo && Storage::disk('public')->exists($this->caminho_arquivo)) {
            Storage::disk('public')->delete($this->caminho_arquivo);
        }
        
        return $this->delete();
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
     * Boot method
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->tenant_id = 1; // Temporário
        });

        static::deleting(function ($model) {
            // Auto-deletar arquivo físico quando deletar registro
            if ($model->caminho_arquivo && Storage::disk('public')->exists($model->caminho_arquivo)) {
                Storage::disk('public')->delete($model->caminho_arquivo);
            }
        });
    }
} 