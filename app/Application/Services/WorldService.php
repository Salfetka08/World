<?php

namespace App\Application\Services;

use App\Application\Models\CurrentWorldModel;
use App\Http\Requests\GetCurrentWorldRequest;
use Exception;

class WorldService
{
    public function __construct(
        private readonly EnvironmentService   $environmentService,
        private readonly TimeService          $timeService,
        private readonly EntertainmentService $entertainmentService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getCurrentWorld(GetCurrentWorldRequest $request): CurrentWorldModel
    {
        // Получаем окружение по координатам
        $environment = $this->environmentService->fetchEnvironment(
            $request->latitude,
            $request->longitude
        );

        // Определяем время суток и сезон
        $dayTime = $this->timeService->getDayTime($environment->timestamp);
        $season = $this->timeService->getSeason($environment->timestamp);

        // Получаем развлекательные места рядом
        $nearbyEntertainment = $this->entertainmentService->findNearbyPlaces(
            $request->latitude,
            $request->longitude,
            5.0,
            null,
            20
        );

        // Получаем по категориям
//        $entertainmentByCategories = $this->entertainmentService->getPlacesByCategories(
//            $request->latitude,
//            $request->longitude,
//            ['restaurant', 'cafe', 'cinema', 'park', 'museum'],
//            5
//        );

        // Формируем ответ
        return new CurrentWorldModel(
            userId: $request->userId,
            environmentDataModel: $environment,
            dayTime: $dayTime,
            season: $season,
            updatedAt: now()->toIso8601String(),
            entertainment: [
                'nearby_places' => $nearbyEntertainment,
//                'places_by_category' => $entertainmentByCategories,
            ]
        );
    }
}
