<?php

namespace App\Http\Controllers;

use App\Application\Services\WorldService;
use App\Http\Controllers\Base\Controller;
use App\Http\Requests\GetCurrentWorldRequest;
use App\Http\Responses\CurrentWorldResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "World",
    description: "Информация о текущем окружающем мире."
)]
class WorldController extends Controller
{
    public function __construct(
        private readonly WorldService $worldAction
    )
    {
    }

    #[OA\Post(
        path: "/api/v1/world/current",
        description: "Возвращает текущие погодные условия, время суток и сезон для указанного пользователя",
        summary: "Получить текущую информацию о мире",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["userId"],  // только user_id обязателен
                properties: [
                    new OA\Property(
                        property: "userId",
                        description: "ID пользователя",
                        type: "integer",
                        example: 123
                    ),
                    new OA\Property(
                        property: "latitude",
                        description: "Широта (опционально)",
                        type: "number",
                        format: "float",
                        example: 55.7558
                    ),
                    new OA\Property(
                        property: "longitude",
                        description: "Долгота (опционально)",
                        type: "number",
                        format: "float",
                        example: 37.6176
                    ),
                    new OA\Property(
                        property: "timestamp",
                        description: "Время запроса (опционально)",
                        type: "string",
                        format: "date-time",
                        example: "2026-03-01T15:30:00Z"
                    )
                ]
            )
        ),
        tags: ["World"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "userId", type: "integer", example: 123),
                        new OA\Property(property: "location", type: "string", example: "Москва"),
                        new OA\Property(
                            property: "weather",
                            properties: [
                                new OA\Property(property: "temperature", type: "integer", example: -5),
                                new OA\Property(property: "feelsLike", type: "integer", example: -8),
                                new OA\Property(property: "condition", type: "string", enum: ["CLEAR", "CLOUDY", "RAINY", "SNOWY", "STORMY"], example: "SNOWY"),
                                new OA\Property(property: "humidity", type: "integer", example: 85),
                                new OA\Property(property: "windSpeed", type: "number", format: "float", example: 7.5),
                            ],
                            type: "object"
                        ),
                        new OA\Property(property: "dayTime", type: "string", enum: ["MORNING", "AFTERNOON", "EVENING", "NIGHT"], example: "EVENING"),
                        new OA\Property(property: "season", type: "string", enum: ["SPRING", "SUMMER", "AUTUMN", "WINTER"], example: "WINTER"),
                        new OA\Property(property: "sunrise", type: "string", format: "time", example: "08:30"),
                        new OA\Property(property: "sunset", type: "string", format: "time", example: "16:45"),
                        new OA\Property(property: "updatedAt", type: "string", format: "date-time", example: "2025-01-20T15:30:00Z")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Ошибка валидации"
            ),
            new OA\Response(
                response: 500,
                description: "Внутренняя ошибка сервера"
            )
        ]
    )]
    public function getCurrentWorld(GetCurrentWorldRequest $request): JsonResponse
    {
        try {
            if ($request->userId != 123) {
                $result = $this->worldAction->getCurrentWorld($request);
                return (new CurrentWorldResponse($result))->toResponse($request);
            } else {

                $result = [
                    'userId' => $request->userId,
                    'location' => 'ДЕФОЛТ',
                    'weather' => [
                        'temperature' => -5,
                        'feelsLike' => -8,
                        'condition' => 'SNOWY',
                        'humidity' => 85,
                        'windSpeed' => 7.5,
                    ],
                    'dayTime' => 'EVENING',
                    'season' => 'WINTER',
                    'sunrise' => '08:30',
                    'sunset' => '16:45',
                    'updatedAt' => date('c')
                ];
            }

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch weather data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
