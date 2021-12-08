<?php

namespace App\Providers;

use App\Services\ProductService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Classes\Contracts\ProductService as ProductServiceContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductServiceContract::class, ProductService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
