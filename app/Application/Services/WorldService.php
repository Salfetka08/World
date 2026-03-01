<?php

namespace App\Application\Services;

use App\Application\Models\CurrentWorldModel;
use App\Application\Models\UserLocationModel;
use App\Http\Requests\GetCurrentWorldRequest;
use Exception;

class WorldService
{
    public function __construct(
        private readonly UserLocationService $userLocationService,
        private readonly WeatherService      $weatherService,
        private readonly TimeService         $timeService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrentWorld(GetCurrentWorldRequest $request): CurrentWorldModel
    {
        // 1. Получаем координаты пользователя
//        TODO: Нужно сначала клиента в базу добавить, чтобы использовать этот метод.
//        $location = $this->userLocationService->getLatestCoordinatesByUserId($request->userId);
        $location = new UserLocationModel(
            latitude: 55.7558,
            longitude: 37.6176,
            cityName: 'Москва',
            country: 'Россия'
        );;

        if (!$location) {
            throw new Exception("Не найдена локация для {$request->userId}", 404);
        }

        // 2. Получаем погоду по координатам
        $weatherData = $this->weatherService->fetchWeather(
            $location->latitude,
            $location->longitude
        );

        // 3. Определяем время суток и сезон
        $dayTime = $this->timeService->getDayTime();
        $season = $this->timeService->getSeason();

        // 4. Формируем ответ
        return new CurrentWorldModel(
            userId: $request->userId,
            location: $location->cityName ?? 'Unknown',
            weather: $weatherData,
            dayTime: $dayTime,
            season: $season,
            sunrise: '08:00', // можно получать из API погоды
            sunset: '20:00',   // можно получать из API погоды
            updatedAt: now()->toIso8601String()
        );
    }
}
