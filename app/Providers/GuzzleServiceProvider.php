<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GuzzleService;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GuzzleService::class, function ($app) {
            return new GuzzleService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
