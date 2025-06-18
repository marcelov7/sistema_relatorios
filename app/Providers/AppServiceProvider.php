<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar helper personalizado para str_limit
        if (!function_exists('str_limit_safe')) {
            function str_limit_safe($value, $limit = 100, $end = '...') {
                return StringHelper::limit($value, $limit, $end);
            }
        }

        // Registrar diretiva Blade personalizada para truncar strings
        Blade::directive('truncate', function ($expression) {
            return "<?php echo strlen($expression) > 100 ? substr($expression, 0, 100) . '...' : $expression; ?>";
        });
        
        // Diretiva com limite customizado
        Blade::directive('limit', function ($expression) {
            $args = explode(',', $expression);
            $string = trim($args[0]);
            $limit = isset($args[1]) ? trim($args[1]) : 100;
            $end = isset($args[2]) ? trim($args[2], ' \'"') : '...';
            
            return "<?php echo strlen($string) > $limit ? substr($string, 0, $limit) . '$end' : $string; ?>";
        });
    }
}
