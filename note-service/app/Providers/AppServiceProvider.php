<?php

namespace App\Providers;

use App\Repository\Contracts\NoteContract;
use App\Repository\Repos\NoteRepo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NoteContract::class,NoteRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
