<?php

namespace App\Providers;

use App\Business\Interfaces\MessageServiceInterface;
use App\Business\Services\HiService;
use App\Business\Services\EncryptService;
use App\Business\Services\HiUserService;
use App\Business\Services\SingletonService;
use App\Business\Services\UserService;
use App\Http\Controllers\InfoController;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MessageServiceInterface::class, HiUserService::class);
        $this->app->bind(EncryptService::class, function () {
            return new EncryptService(env("KEY_ENCRYPT"));
        });
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(EncryptService::class));
        }); // No hace falta pero por si diese algún fallo con las dependencias de clases

        $this->app->when(InfoController::class)->needs(MessageServiceInterface::class)
            ->give(HiService::class);
        // Pongo como "default" por así decirlo y luego especifíco para cada controller

        $this->app->singleton(SingletonService::class, SingletonService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
