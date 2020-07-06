<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserInterface;
use App\Services\UserService;

class UserServiceProvider extends ServiceProvider
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
        app()->singleton(UserInterface::class, UserService::class);
    }
}
