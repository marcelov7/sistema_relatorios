<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Verifica se o usuário tem permissão para uma ação específica de um módulo
     * 
     * @param string $module
     * @param string $action
     * @return bool
     */
    public static function can(string $module, string $action): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $permission = "{$module}_{$action}";
        return Auth::user()->can($permission);
    }

    /**
     * Verifica se o usuário pode visualizar um módulo
     */
    public static function canView(string $module): bool
    {
        return self::can($module, 'visualizar');
    }

    /**
     * Verifica se o usuário pode criar em um módulo
     */
    public static function canCreate(string $module): bool
    {
        return self::can($module, 'criar');
    }

    /**
     * Verifica se o usuário pode editar em um módulo
     */
    public static function canEdit(string $module): bool
    {
        return self::can($module, 'editar');
    }

    /**
     * Verifica se o usuário pode excluir em um módulo
     */
    public static function canDelete(string $module): bool
    {
        return self::can($module, 'excluir');
    }

    /**
     * Verifica se o usuário pode gerenciar um módulo (todas as ações)
     */
    public static function canManage(string $module): bool
    {
        return self::can($module, 'gerenciar');
    }

    /**
     * Verifica múltiplas permissões
     * 
     * @param array $permissions Array de permissões no formato ['module_action', 'module_action']
     * @param string $operator 'and' ou 'or'
     * @return bool
     */
    public static function canMultiple(array $permissions, string $operator = 'and'): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        if ($operator === 'or') {
            foreach ($permissions as $permission) {
                if ($user->can($permission)) {
                    return true;
                }
            }
            return false;
        }

        // Operator 'and'
        foreach ($permissions as $permission) {
            if (!$user->can($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtém todas as permissões de um módulo que o usuário possui
     */
    public static function getModulePermissions(string $module): array
    {
        if (!Auth::check()) {
            return [];
        }

        $actions = ['visualizar', 'criar', 'editar', 'excluir', 'gerenciar', 'atribuir'];
        $userPermissions = [];

        foreach ($actions as $action) {
            $permission = "{$module}_{$action}";
            if (Auth::user()->can($permission)) {
                $userPermissions[] = $action;
            }
        }

        return $userPermissions;
    }

    /**
     * Verifica se usuário tem acesso administrativo
     */
    public static function isAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasRole('admin') || 
               Auth::user()->can('sistema_configurar');
    }

    /**
     * Verifica se usuário tem acesso de supervisor
     */
    public static function isSupervisor(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->hasRole('supervisor') || 
               Auth::user()->can('dashboard_supervisor');
    }
} 