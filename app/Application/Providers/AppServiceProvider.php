<?php

namespace App\Application\Providers;

use App\Application\Services\EntertainmentService;
use App\Application\Services\TimeService;
use App\Application\Services\WeatherService;
use App\Application\Services\WorldService;
use App\Domain\Repositories\EntertainmentPlaceRepository;
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
        $this->app->singleton(EntertainmentPlaceRepository::class, function ($app) {
            return new EntertainmentPlaceRepository();
        });
    }

    /**
     * Регистрация сервисов
     */
    public function registerServices(): void
    {
        $this->app->singleton(EntertainmentService::class, function ($app) {
            return new EntertainmentService(
                $app->make(EntertainmentPlaceRepository::class)
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
                $app->make(WeatherService::class),
                $app->make(TimeService::class),
                $app->make(EntertainmentService::class)
            );
        });
    }
}
