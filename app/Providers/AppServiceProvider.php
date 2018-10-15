<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\FigureService;
use App\Services\ProfileService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->singleton('auth-service', function () {
            return new AuthService();
        });
        $this->app->singleton('profile-service', function () {
            return new ProfileService();
        });
        $this->app->singleton('figure-service', function () {
            return new FigureService();
        });
    }
}
