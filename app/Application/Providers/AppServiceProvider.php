<?php

namespace App\Application\Providers;

use App\Application\Services\TimeService;
use App\Application\Services\UserLocationService;
use App\Application\Services\WeatherService;
use App\Application\Services\WorldService;
use App\Domain\Repositories\UserLocationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Регистрация репозитория
     */
    public function registerRepositories(): void
    {
        $this->app->singleton(UserLocationRepository::class, function ($app) {
            return new UserLocationRepository();
        });
    }

    /**
     * Регистрация сервисов
     */
    public function registerServices(): void
    {
        $this->app->singleton(UserLocationService::class, function ($app) {
            return new UserLocationService(
                $app->make(UserLocationRepository::class)
            );
        });

        $this->app->singleton(WeatherService::class, function ($app) {
            return new WeatherService();
        });

        $this->app->singleton(TimeService::class, function ($app) {
            return new TimeService();
        });


        $this->app->singleton(WorldService::class, function ($app) {
            return new WorldService(
                $app->make(UserLocationService::class),
                $app->make(WeatherService::class),
                $app->make(TimeService::class),
            );
        });
    }
}
