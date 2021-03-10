<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Contracts\CompanyRepositoryInterface',
            'App\Repositories\CompanyRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\ClientRepositoryInterface',
            'App\Repositories\ClientRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserRepositoryInterface',
            'App\Repositories\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\ProductRepositoryInterface',
            'App\Repositories\ProductRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CompanyTokenRepositoryInterface',
            'App\Repositories\CompanyTokenRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\SaleRepositoryInterface',
            'App\Repositories\SaleRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\PhotoRepositoryInterface',
            'App\Repositories\PhotoRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CategoryRepositoryInterface',
            'App\Repositories\CategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\ProviderRepositoryInterface',
            'App\Repositories\ProviderRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\StatusRepositoryInterface',
            'App\Repositories\StatusRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\SizeRepositoryInterface',
            'App\Repositories\SizeRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }

        Schema::defaultStringLength(191);
    }
}
