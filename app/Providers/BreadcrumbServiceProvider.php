<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\BreadcrumbHelper;

class BreadcrumbServiceProvider extends ServiceProvider
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
        // Compartilhar breadcrumbs com todas as views
        View::composer('*', function ($view) {
            $view->with('breadcrumbs', BreadcrumbHelper::generate());
            $view->with('pageTitle', BreadcrumbHelper::getCurrentPageTitle());
        });
    }
}
