<?php

namespace App\Providers;

use App\Repository\Contracts\AuthContract;
use App\Repository\Repos\AuthRepo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthContract::class, AuthRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
