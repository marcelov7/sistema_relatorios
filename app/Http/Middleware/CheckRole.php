<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        $user = auth()->user();

        // Verifica se o usuário está ativo
        if (!$user->ativo) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Sua conta está inativa. Entre em contato com o administrador.');
        }

        // Atualiza último acesso
        $user->updateLastAccess();

        // Se não foram especificados roles, permite acesso a usuários autenticados
        if (empty($roles)) {
            return $next($request);
        }

        // Verifica se o usuário tem pelo menos um dos roles necessários
        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        // Se chegou até aqui, não tem permissão
        abort(403, 'Você não tem permissão para acessar esta página.');
    }
}
