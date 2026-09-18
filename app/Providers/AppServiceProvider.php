<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Di produksi SSL diterminasi di nginx, sehingga Laravel bisa menghasilkan
        // URL http://. Canonical dan og:url yang salah skema memecah sinyal SEO
        // antara versi http dan https, jadi paksa https di sana.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
