<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Onde os usuários são redirecionados após o login.
     */
    public const HOME = '/home';

    /**
     * Defina seus bindings e carregue as rotas aqui.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Rotas API (prefixo /api, middleware api)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Rotas Web (middleware web)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configura o rate limiter para a API.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                        ->by($request->user()?->id ?: $request->ip());
        });
    }
}
