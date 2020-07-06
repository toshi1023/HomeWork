<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CompanyInterface;
use App\Services\CompanyService;

class CompanyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton(CompanyInterface::class, CompanyService::class);
    }
}
