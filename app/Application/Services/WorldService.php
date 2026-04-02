<?php

namespace App\Application\Services;

use App\Application\Models\CurrentWorldModel;
use App\Http\Requests\GetCurrentWorldRequest;
use Exception;

class WorldService
{
    public function __construct(
        private readonly WeatherService      $weatherService,
        private readonly TimeService         $timeService,
        private readonly EntertainmentService $entertainmentService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrentWorld(GetCurrentWorldRequest $request): CurrentWorldModel
    {
        // Получаем погоду по координатам
        $weatherData = $this->weatherService->fetchWeather(
            $request->latitude,
            $request->longitude
        );

        // Определяем время суток и сезон
        $dayTime = $this->timeService->getDayTime();
        $season = $this->timeService->getSeason();

        // Получаем развлекательные места рядом
        $nearbyEntertainment = $this->entertainmentService->findNearbyPlaces(
            $request->latitude,
            $request->longitude,
            5.0,
            null,
            20
        );

        // Получаем по категориям
        $entertainmentByCategories = $this->entertainmentService->getPlacesByCategories(
            $request->latitude,
            $request->longitude,
            ['restaurant', 'cafe', 'cinema', 'park', 'museum'],
            5
        );

        // Формируем ответ (исправлено: убран $location)
        return new CurrentWorldModel(
            userId: $request->userId,
            weather: $weatherData,
            dayTime: $dayTime,
            season: $season,
            sunrise: '08:00',
            sunset: '20:00',
            updatedAt: now()->toIso8601String(),
            entertainment: [
                'nearby_places' => $nearbyEntertainment,
                'places_by_category' => $entertainmentByCategories,
            ]
        );
    }
}
