<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

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
        // Rutas API
        Route::middleware('api')
             ->prefix('api')
             ->group(base_path('routes/api.php'));

        // Rutas web
        Route::middleware('web')
             ->group(base_path('routes/web.php'));
    }
}
