<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Carbon::setLocale('id');
        Paginator::useBootstrapFive();
        View::composer(['layouts.site', 'pages.about'], function ($view) {
            $view->with('siteSettings', SiteSetting::first());
        });
    }
}
