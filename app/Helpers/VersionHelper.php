<?php

namespace App\Helpers;

class VersionHelper
{
    /**
     * Obter a versão atual do sistema
     */
    public static function getVersion(): string
    {
        return config('app.version', '1.0.0');
    }

    /**
     * Obter o nome da versão
     */
    public static function getVersionName(): string
    {
        return config('app.version_name', 'Genesis');
    }

    /**
     * Obter a data de lançamento
     */
    public static function getReleaseDate(): string
    {
        return config('app.release_date', '2024-12-19');
    }

    /**
     * Obter o número do build
     */
    public static function getBuild(): string
    {
        return config('app.build', '20241219001');
    }

    /**
     * Obter informações completas da versão
     */
    public static function getFullVersionInfo(): array
    {
        return [
            'version' => self::getVersion(),
            'version_name' => self::getVersionName(),
            'release_date' => self::getReleaseDate(),
            'build' => self::getBuild(),
            'formatted_version' => self::getFormattedVersion(),
            'formatted_date' => self::getFormattedReleaseDate(),
        ];
    }

    /**
     * Obter versão formatada
     */
    public static function getFormattedVersion(): string
    {
        return 'v' . self::getVersion() . ' "' . self::getVersionName() . '"';
    }

    /**
     * Obter data formatada
     */
    public static function getFormattedReleaseDate(): string
    {
        try {
            return \Carbon\Carbon::parse(self::getReleaseDate())
                ->setTimezone('America/Sao_Paulo')
                ->format('d/m/Y');
        } catch (\Exception $e) {
            return self::getReleaseDate();
        }
    }

    /**
     * Verificar se é uma versão de desenvolvimento
     */
    public static function isDevelopment(): bool
    {
        return str_contains(self::getVersion(), 'dev') || 
               str_contains(self::getVersion(), 'alpha') || 
               str_contains(self::getVersion(), 'beta');
    }

    /**
     * Obter informações do sistema
     */
    public static function getSystemInfo(): array
    {
        return [
            'app_name' => config('app.name', 'Sistema de Relatórios'),
            'app_env' => config('app.env', 'production'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timezone' => 'America/Sao_Paulo',
            'locale' => 'pt-BR',
            'country' => 'Brasil 🇧🇷',
            'currency' => 'Real (R$)',
            'date_format' => 'd/m/Y',
            'datetime_format' => 'd/m/Y H:i:s',
        ];
    }

    /**
     * Obter changelog da versão atual
     */
    public static function getCurrentChangelog(): array
    {
        $version = self::getVersion();
        $changelog = self::getChangelog();
        
        return $changelog[$version] ?? [];
    }

    /**
     * Obter histórico completo de versões
     */
    public static function getChangelog(): array
    {
        return [
            '1.0.0' => [
                'release_date' => '2024-12-19',
                'version_name' => 'Genesis',
                'type' => 'major',
                'features' => [
                    'Sistema completo de relatórios de manutenção',
                    'Gestão de inspeções de geradores',
                    'Análise de equipamentos e analisadores',
                    'Dashboard de analytics com gráficos interativos',
                    'Sistema dual de geração de PDFs (DomPDF + Browsershot)',
                    'Gestão de usuários com controle de acesso',
                    'Sistema de notificações',
                    'Interface responsiva e moderna',
                    'Controle de locais e equipamentos',
                    'Histórico de atividades',
                ],
                'improvements' => [
                    'Templates de PDF profissionais com design moderno',
                    'Validação automática de parâmetros técnicos',
                    'Análise inteligente de performance',
                    'Indicadores visuais de status',
                    'Otimização de performance',
                ],
                'fixes' => [
                    'Correção de campos de dados nas inspeções',
                    'Melhoria na geração de PDFs com imagens',
                    'Ajustes de responsividade',
                    'Correção de timestamps customizados',
                ],
                'technical' => [
                    'Laravel 10.x',
                    'Bootstrap 5.3',
                    'Chart.js para gráficos',
                    'Browsershot para PDFs premium',
                    'Sistema de versionamento implementado',
                ]
            ]
        ];
    }
} 