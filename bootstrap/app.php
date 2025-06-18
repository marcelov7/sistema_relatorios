<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global para timezone brasileiro
        $middleware->web(append: [
            \App\Http\Middleware\SetBrazilianTimezone::class,
        ]);
        
        // Registrar middleware customizado
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'brazil_timezone' => \App\Http\Middleware\SetBrazilianTimezone::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
