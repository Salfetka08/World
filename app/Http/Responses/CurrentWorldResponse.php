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
            'weather' => $this->currentWorldModel
                ->environmentDataModel
                ->toArray(),
            'dayTime' => $this->currentWorldModel->dayTime,
            'season' => $this->currentWorldModel->season,
            'updatedAt' => $this->currentWorldModel->updatedAt,
            'entertainment' => $this->currentWorldModel->entertainment,
        ]);
    }
}
