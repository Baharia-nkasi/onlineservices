<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Render terminates TLS at its edge proxy. Force generated application
        // URLs to use HTTPS in production so auth forms and redirects never
        // downgrade to an insecure HTTP URL.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
