<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

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
    public function boot(UrlGenerator $url)
    {
        // if(env('APP_ENV') !== 'local') {
        //     $url->forceScheme('https');
        // }
    }
}
