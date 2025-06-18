<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class BrazilServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configurar timezone brasileiro PRIMEIRO
        $this->configureBrazilianTimezone();
        
        // Configurar locale brasileiro
        $this->configureBrazilianLocale();
        
        // Configurar Carbon para português brasileiro
        $this->configureCarbonLocale();
    }

    /**
     * Configurar timezone brasileiro
     */
    private function configureBrazilianTimezone(): void
    {
        // Definir timezone padrão do PHP
        date_default_timezone_set('America/Sao_Paulo');
        
        // Configurar timezone no Laravel
        config(['app.timezone' => 'America/Sao_Paulo']);
        
        // Limpar qualquer configuração de teste do Carbon
        \Carbon\Carbon::setTestNow(null);
    }

    /**
     * Configurar locale brasileiro
     */
    private function configureBrazilianLocale(): void
    {
        // Configurar locale da aplicação
        config(['app.locale' => 'pt_BR']);
        config(['app.fallback_locale' => 'pt_BR']);
        config(['app.faker_locale' => 'pt_BR']);
        
        // Tentar configurar locale do sistema
        $locales = ['pt_BR.UTF-8', 'pt_BR', 'portuguese'];
        foreach ($locales as $locale) {
            if (setlocale(LC_ALL, $locale)) {
                break;
            }
        }
        
        // Configurar locale para números e moeda
        if (function_exists('setlocale')) {
            setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
            setlocale(LC_NUMERIC, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
            setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
        }
    }

    /**
     * Configurar Carbon para português brasileiro
     */
    private function configureCarbonLocale(): void
    {
        try {
            Carbon::setLocale('pt_BR');
        } catch (\Exception $e) {
            // Fallback para português se pt_BR não estiver disponível
            try {
                Carbon::setLocale('pt');
            } catch (\Exception $e) {
                // Manter inglês como último recurso
                Carbon::setLocale('en');
            }
        }
    }
}
