<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BreadcrumbHelper
{
    protected static $routeMap = [
        // Dashboard
        'home' => ['Dashboard', 'bi-house'],
        'relatorios.dashboard' => ['Dashboard', 'bi-house'],
        
        // Relatórios
        'relatorios.index' => ['Relatórios', 'bi-file-text'],
        'relatorios.create' => ['Novo Relatório', 'bi-plus-circle'],
        'relatorios.show' => ['Relatório', 'bi-eye'],
        'relatorios.edit' => ['Editar Relatório', 'bi-pencil'],
        
        // Motores
        'motores.index' => ['Motores', 'bi-gear'],
        'motores.create' => ['Novo Motor', 'bi-plus-circle'],
        'motores.show' => ['Motor', 'bi-eye'],
        'motores.edit' => ['Editar Motor', 'bi-pencil'],
        
        // Analisadores
        'analisadores.index' => ['Analisadores', 'bi-cpu'],
        'analisadores.create' => ['Novo Analisador', 'bi-plus-circle'],
        'analisadores.show' => ['Analisador', 'bi-eye'],
        'analisadores.edit' => ['Editar Analisador', 'bi-pencil'],
        
        // Inspeções de Gerador
        'inspecoes-gerador.index' => ['Inspeções de Gerador', 'bi-lightning-charge'],
        'inspecoes-gerador.create' => ['Nova Inspeção', 'bi-plus-circle'],
        'inspecoes-gerador.show' => ['Inspeção de Gerador', 'bi-eye'],
        'inspecoes-gerador.edit' => ['Editar Inspeção', 'bi-pencil'],
        
        // Equipamentos
        'equipamentos.index' => ['Equipamentos', 'bi-tools'],
        'equipamentos.create' => ['Novo Equipamento', 'bi-plus-circle'],
        'equipamentos.show' => ['Equipamento', 'bi-eye'],
        'equipamentos.edit' => ['Editar Equipamento', 'bi-pencil'],
        
        // Locais
        'locais.index' => ['Locais', 'bi-geo-alt'],
        'locais.create' => ['Novo Local', 'bi-plus-circle'],
        'locais.show' => ['Local', 'bi-eye'],
        'locais.edit' => ['Editar Local', 'bi-pencil'],
        
        // Admin
        'admin.dashboard' => ['Admin', 'bi-gear'],
        'admin.users.index' => ['Usuários', 'bi-people'],
        'admin.users.create' => ['Novo Usuário', 'bi-person-plus'],
        'admin.users.show' => ['Usuário', 'bi-person'],
        'admin.users.edit' => ['Editar Usuário', 'bi-person-gear'],
        'admin.roles.index' => ['Roles', 'bi-shield'],
        'admin.roles.create' => ['Nova Role', 'bi-shield-plus'],
        'admin.roles.show' => ['Role', 'bi-shield'],
        'admin.roles.edit' => ['Editar Role', 'bi-shield-exclamation'],
        
        // Analytics
        'analytics.dashboard' => ['Analytics', 'bi-graph-up'],
        
        // PDFs
        'pdf.index' => ['Geração de PDFs', 'bi-file-earmark-pdf'],
        'pdf.relatorio' => ['PDF do Relatório', 'bi-file-earmark-pdf'],
        'pdf.inspecao' => ['PDF da Inspeção', 'bi-file-earmark-pdf'],
        'pdf.analisador' => ['PDF do Analisador', 'bi-file-earmark-pdf'],
        'pdf.analytics' => ['PDF Analytics', 'bi-file-earmark-pdf'],
        'pdf.relatorios-lote' => ['PDF Relatórios em Lote', 'bi-file-earmark-pdf'],
        'pdf.inspecoes-lote' => ['PDF Inspeções em Lote', 'bi-file-earmark-pdf'],
    ];
    
    protected static $parentRoutes = [
        // Relatórios
        'relatorios.create' => 'relatorios.index',
        'relatorios.show' => 'relatorios.index',
        'relatorios.edit' => 'relatorios.index',
        
        // Motores
        'motores.create' => 'motores.index',
        'motores.show' => 'motores.index',
        'motores.edit' => 'motores.index',
        
        // Analisadores
        'analisadores.create' => 'analisadores.index',
        'analisadores.show' => 'analisadores.index',
        'analisadores.edit' => 'analisadores.index',
        
        // Inspeções de Gerador
        'inspecoes-gerador.create' => 'inspecoes-gerador.index',
        'inspecoes-gerador.show' => 'inspecoes-gerador.index',
        'inspecoes-gerador.edit' => 'inspecoes-gerador.index',
        
        // Equipamentos
        'equipamentos.create' => 'equipamentos.index',
        'equipamentos.show' => 'equipamentos.index',
        'equipamentos.edit' => 'equipamentos.index',
        
        // Locais
        'locais.create' => 'locais.index',
        'locais.show' => 'locais.index',
        'locais.edit' => 'locais.index',
        
        // Admin
        'admin.users.index' => 'admin.dashboard',
        'admin.users.create' => 'admin.users.index',
        'admin.users.show' => 'admin.users.index',
        'admin.users.edit' => 'admin.users.index',
        'admin.roles.index' => 'admin.dashboard',
        'admin.roles.create' => 'admin.roles.index',
        'admin.roles.show' => 'admin.roles.index',
        'admin.roles.edit' => 'admin.roles.index',
    ];

    public static function generate()
    {
        $currentRoute = Route::currentRouteName();
        $breadcrumbs = [];
        
        // Se for rota admin, começar com Admin Dashboard
        if (Str::startsWith($currentRoute, 'admin.')) {
            $breadcrumbs[] = [
                'title' => 'Dashboard',
                'icon' => 'bi-house',
                'url' => route('home'),
                'active' => false
            ];
            
            // Se não for admin.dashboard, adicionar hierarquia admin
            if ($currentRoute !== 'admin.dashboard') {
                $breadcrumbs = array_merge($breadcrumbs, self::buildBreadcrumbChain($currentRoute));
            } else {
                // Se for admin dashboard, adicionar e marcar como ativo
                $breadcrumbs[] = [
                    'title' => 'Admin',
                    'icon' => 'bi-gear',
                    'url' => route('admin.dashboard'),
                    'active' => true
                ];
            }
        } else {
            // Para rotas normais, sempre começar com Dashboard
            $breadcrumbs[] = [
                'title' => 'Dashboard',
                'icon' => 'bi-house',
                'url' => route('home'),
                'active' => false
            ];
            
            // Se não for dashboard, adicionar hierarquia
            if (!in_array($currentRoute, ['home', 'relatorios.dashboard'])) {
                $breadcrumbs = array_merge($breadcrumbs, self::buildBreadcrumbChain($currentRoute));
            } else {
                // Se for dashboard, marcar como ativo
                $breadcrumbs[0]['active'] = true;
            }
        }
        
        return $breadcrumbs;
    }
    
    protected static function buildBreadcrumbChain($routeName)
    {
        $chain = [];
        $current = $routeName;
        
        // Construir a cadeia de pais
        $visited = [];
        while ($current && !in_array($current, $visited)) {
            $visited[] = $current;
            
            if (isset(self::$routeMap[$current])) {
                $routeInfo = self::$routeMap[$current];
                $title = $routeInfo[0];
                
                // Se for uma rota show, personalizar título com ID apenas se for a rota atual
                if (Str::endsWith($current, '.show') && $current === Route::currentRouteName()) {
                    $route = Route::current();
                    $params = $route ? $route->parameters() : [];
                    
                    // Procurar por parâmetros que sejam IDs
                    foreach ($params as $key => $value) {
                        // Se for um modelo Eloquent, pegar o ID
                        if (is_object($value) && method_exists($value, 'getKey')) {
                            $id = $value->getKey();
                            $title = $routeInfo[0] . " #$id";
                            break;
                        }
                        // Se for um ID numérico
                        elseif (is_numeric($value)) {
                            $title = $routeInfo[0] . " #$value";
                            break;
                        }
                    }
                }
                
                $chain[] = [
                    'title' => $title,
                    'icon' => $routeInfo[1],
                    'url' => self::getRouteUrl($current),
                    'active' => false
                ];
            }
            
            // Próximo pai
            $current = self::$parentRoutes[$current] ?? null;
        }
        
        // Reverter para ordem correta (pai -> filho)
        $chain = array_reverse($chain);
        
        // Marcar o último como ativo
        if (!empty($chain)) {
            $chain[count($chain) - 1]['active'] = true;
        }
        
        return $chain;
    }
    
    protected static function getRouteUrl($routeName)
    {
        try {
            // Para rotas que precisam de parâmetros, tentar pegar do request atual
            $currentRoute = Route::current();
            $params = $currentRoute ? $currentRoute->parameters() : [];
            
            // Se for uma rota show/edit, usar index para evitar problemas de parâmetros
            if (Str::endsWith($routeName, ['.show', '.edit'])) {
                $indexRoute = Str::replaceLast('.show', '.index', $routeName);
                $indexRoute = Str::replaceLast('.edit', '.index', $indexRoute);
                return route($indexRoute);
            }
            
            // Tentar com parâmetros primeiro, depois sem
            try {
                return route($routeName, $params);
            } catch (\Exception $e) {
                return route($routeName);
            }
        } catch (\Exception $e) {
            return '#';
        }
    }
    
    public static function getCurrentPageTitle()
    {
        $currentRoute = Route::currentRouteName();
        $route = Route::current();
        $params = $route ? $route->parameters() : [];
        
        if (isset(self::$routeMap[$currentRoute])) {
            $title = self::$routeMap[$currentRoute][0];
            
            // Se for uma rota show e tiver parâmetro, personalizar o título
            if (Str::endsWith($currentRoute, '.show') && !empty($params)) {
                // Procurar por parâmetros que sejam IDs
                foreach ($params as $key => $value) {
                    // Se for um modelo Eloquent, pegar o ID
                    if (is_object($value) && method_exists($value, 'getKey')) {
                        $id = $value->getKey();
                        return $title . " #$id";
                    }
                    // Se for um ID numérico
                    elseif (is_numeric($value)) {
                        return $title . " #$value";
                    }
                }
            }
            
            return $title;
        }
        
        // Fallback: tentar extrair do nome da rota
        $parts = explode('.', $currentRoute);
        if (count($parts) >= 2) {
            $module = ucfirst($parts[0]);
            $action = $parts[1];
            
            switch ($action) {
                case 'create':
                    return "Novo $module";
                case 'edit':
                    return "Editar $module";
                case 'show':
                    if (!empty($params)) {
                        // Procurar por parâmetros que sejam IDs
                        foreach ($params as $key => $value) {
                            // Se for um modelo Eloquent, pegar o ID
                            if (is_object($value) && method_exists($value, 'getKey')) {
                                $id = $value->getKey();
                                return "$module #$id";
                            }
                            // Se for um ID numérico
                            elseif (is_numeric($value)) {
                                return "$module #$value";
                            }
                        }
                    }
                    return "Visualizar $module";
                default:
                    return $module;
            }
        }
        
        return 'Página';
    }
    
    protected static function getModuleName($routeName)
    {
        $moduleMap = [
            'relatorios' => 'Relatório',
            'motores' => 'Motor',
            'analisadores' => 'Analisador',
            'inspecoes-gerador' => 'Inspeção de Gerador',
            'equipamentos' => 'Equipamento',
            'locais' => 'Local',
        ];
        
        foreach ($moduleMap as $key => $name) {
            if (Str::startsWith($routeName, $key)) {
                return $name;
            }
        }
        
        // Fallback
        $parts = explode('.', $routeName);
        return ucfirst($parts[0] ?? 'Item');
    }
} 