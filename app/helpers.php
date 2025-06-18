<?php

use App\Helpers\VersionHelper;

if (!function_exists('hasRole')) {
    function hasRole($roles) {
        if (!auth()->check()) {
            return false;
        }
        
        $user = auth()->user();
        
        // Se o método hasRole existe (Spatie Permission)
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole($roles);
        }
        
        // Fallback para role simples na coluna 'role'
        if (is_array($roles)) {
            return in_array($user->role ?? 'user', $roles);
        }
        
        return ($user->role ?? 'user') === $roles;
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return hasRole(['admin']);
    }
}

if (!function_exists('isAdminOrSupervisor')) {
    function isAdminOrSupervisor() {
        return hasRole(['admin', 'supervisor']);
    }
}

if (!function_exists('app_version')) {
    /**
     * Obter a versão atual do sistema
     */
    function app_version(): string
    {
        return VersionHelper::getVersion();
    }
}

if (!function_exists('app_version_full')) {
    /**
     * Obter a versão formatada completa
     */
    function app_version_full(): string
    {
        return VersionHelper::getFormattedVersion();
    }
}

if (!function_exists('app_version_info')) {
    /**
     * Obter informações completas da versão
     */
    function app_version_info(): array
    {
        return VersionHelper::getFullVersionInfo();
    }
}

if (!function_exists('app_build')) {
    /**
     * Obter o número do build
     */
    function app_build(): string
    {
        return VersionHelper::getBuild();
    }
}

if (!function_exists('system_info')) {
    /**
     * Obter informações do sistema
     */
    function system_info(): array
    {
        return VersionHelper::getSystemInfo();
    }
}

if (!function_exists('format_date_br')) {
    /**
     * Formatar data no padrão brasileiro
     */
    function format_date_br($date, $format = 'd/m/Y'): string
    {
        if (!$date) return '';
        
        try {
            // Sempre converter para timezone brasileiro
            return \Carbon\Carbon::parse($date)->setTimezone('America/Sao_Paulo')->format($format);
        } catch (\Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('format_datetime_br')) {
    /**
     * Formatar data e hora no padrão brasileiro
     */
    function format_datetime_br($datetime, $format = 'd/m/Y H:i:s'): string
    {
        if (!$datetime) return '';
        
        try {
            // Sempre converter para timezone brasileiro
            return \Carbon\Carbon::parse($datetime)->setTimezone('America/Sao_Paulo')->format($format);
        } catch (\Exception $e) {
            return $datetime;
        }
    }
}

if (!function_exists('now_br')) {
    /**
     * Obter data/hora atual no Brasil
     */
    function now_br(): \Carbon\Carbon
    {
        // Garantir que estamos usando o timezone correto
        return \Carbon\Carbon::now('America/Sao_Paulo');
    }
}

if (!function_exists('today_br')) {
    /**
     * Obter data de hoje no Brasil
     */
    function today_br(): \Carbon\Carbon
    {
        return \Carbon\Carbon::today('America/Sao_Paulo');
    }
}

if (!function_exists('format_currency_br')) {
    /**
     * Formatar moeda brasileira
     */
    function format_currency_br($value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('format_number_br')) {
    /**
     * Formatar número no padrão brasileiro
     */
    function format_number_br($number, $decimals = 2): string
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('current_time_br')) {
    /**
     * Obter hora atual exata do Brasil
     */
    function current_time_br($format = 'H:i:s'): string
    {
        // Criar nova instância do Carbon com timezone brasileiro
        $now = new \Carbon\Carbon('now', new \DateTimeZone('America/Sao_Paulo'));
        return $now->format($format);
    }
}

if (!function_exists('current_datetime_br')) {
    /**
     * Obter data e hora atual exata do Brasil
     */
    function current_datetime_br($format = 'd/m/Y H:i:s'): string
    {
        // Criar nova instância do Carbon com timezone brasileiro
        $now = new \Carbon\Carbon('now', new \DateTimeZone('America/Sao_Paulo'));
        return $now->format($format);
    }
} 