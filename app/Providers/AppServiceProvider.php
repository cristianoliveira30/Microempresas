<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\VendaRepositoryInterface::class,
            \App\Repositories\VendaRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\CaixaRepositoryInterface::class,
            \App\Repositories\CaixaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
