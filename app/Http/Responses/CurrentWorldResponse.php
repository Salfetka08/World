<?php

namespace App\Http\Responses;

use App\Application\Models\CurrentWorldModel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class CurrentWorldResponse implements Responsable
{
    public function __construct(
        private readonly CurrentWorldModel $currentWorldModel
    )
    {
    }

    /**
     * Преобразовать DTO в HTTP ответ
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'userId' => $this->currentWorldModel->userId,
            'location' => $this->currentWorldModel->location,
            'weather' => [
                'temperature' => $this->currentWorldModel->weather->temperature,
                'feelsLike' => $this->currentWorldModel->weather->feelsLike,
                'condition' => $this->currentWorldModel->weather->condition,
                'humidity' => $this->currentWorldModel->weather->humidity,
                'windSpeed' => $this->currentWorldModel->weather->windSpeed,
            ],
            'dayTime' => $this->currentWorldModel->dayTime,
            'season' => $this->currentWorldModel->season,
            'sunrise' => $this->currentWorldModel->sunrise,
            'sunset' => $this->currentWorldModel->sunset,
            'updatedAt' => $this->currentWorldModel->updatedAt,
        ]);
    }
}
