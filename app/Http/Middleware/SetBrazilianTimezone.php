<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetBrazilianTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Garantir timezone brasileiro em cada requisição
        date_default_timezone_set('America/Sao_Paulo');
        config(['app.timezone' => 'America/Sao_Paulo']);
        
        // Limpar qualquer configuração de teste do Carbon
        \Carbon\Carbon::setTestNow(null);
        
        return $next($request);
    }
}
