<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'telefone',
        'cargo',
        'departamento',
        'ativo',
        'configuracoes_notificacao',
        'ultimo_acesso',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'configuracoes_notificacao' => 'json',
            'ultimo_acesso' => 'datetime',
            'ativo' => 'boolean',
        ];
    }

    /**
     * Relatórios criados pelo usuário
     */
    public function relatoriosCriados()
    {
        return $this->hasMany(Relatorio::class, 'criado_por');
    }

    /**
     * Relatórios que o usuário pode editar
     */
    public function relatoriosAtribuidos()
    {
        return $this->belongsToMany(Relatorio::class, 'relatorio_usuarios')
                    ->withPivot('permissao', 'atribuido_em', 'atribuido_por');
    }

    /**
     * Notificações do usuário
     */
    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    /**
     * Notificações não lidas
     */
    public function notificacaoNaoLidas()
    {
        return $this->hasMany(Notificacao::class)->where('lida', false);
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar por departamento
     */
    public function scopePorDepartamento($query, $departamento)
    {
        return $query->where('departamento', $departamento);
    }

    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verifica se o usuário é supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    /**
     * Retorna o nome do primeiro role do usuário
     */
    public function getRoleNameAttribute(): string
    {
        return $this->roles->first()?->name ?? 'usuario';
    }

    /**
     * Atualiza o último acesso
     */
    public function updateLastAccess(): void
    {
        $this->update(['ultimo_acesso' => now()]);
    }

    /**
     * Encontra usuário por username ou email
     */
    public static function findByUsernameOrEmail(string $login): ?User
    {
        return static::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
    }

    /**
     * Scope para buscar por login (username ou email)
     */
    public function scopeByLogin($query, string $login)
    {
        return $query->where('email', $login)
                    ->orWhere('username', $login);
    }

    /**
     * Constantes para departamentos
     */
    const DEPARTAMENTOS = [
        'ti' => 'TI',
        'manutencao' => 'Manutenção',
        'producao' => 'Produção',
        'qualidade' => 'Qualidade',
        'seguranca' => 'Segurança',
        'administracao' => 'Administração',
    ];
}
