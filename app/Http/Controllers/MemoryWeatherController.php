<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API для получения информации связанной с погодой.",
    title: "Weather API",
    contact: new OA\Contact(
        email: "test@example.com"
    )
)]
#[OA\Tag(
    name: "Weather",
    description: "Получение случайных идей о погоде"
)]
class MemoryWeatherController extends Controller
{
    #[OA\Get(
        path: "/v1/weather/random-idea",
        description: "Возвращает случайную идею, связанную с погодой, включая описание, настроение, время года и погодные условия",
        summary: "Получить случайную идею о погоде",
        tags: ["Ideas"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Успешный ответ",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "idea",
                            description: "Случайная идея о погоде",
                            properties: [
                                new OA\Property(
                                    property: "description",
                                    description: "Описание погодной идеи",
                                    type: "string",
                                    example: "Легкий снежок кружится под фонарями, создавая новогоднее настроение"
                                ),
                                new OA\Property(
                                    property: "mood",
                                    description: "Настроение, которое создает погода",
                                    type: "string",
                                    example: "Сказочная"
                                ),
                                new OA\Property(
                                    property: "season",
                                    description: "Время года",
                                    type: "string",
                                    enum: ["Зима", "Весна", "Лето", "Осень"],
                                    example: "Зима"
                                ),
                                new OA\Property(
                                    property: "weather_type",
                                    description: "Тип погоды",
                                    type: "string",
                                    enum: ["Снежно", "Дождливо", "Облачно", "Гроза", "Туманно", "Солнечно"],
                                    example: "Снежно"
                                ),
                                new OA\Property(
                                    property: "color_palette",
                                    description: "Цветовая палитра, ассоциирующаяся с погодой",
                                    type: "string",
                                    example: "Холодные тона",
                                    nullable: true
                                ),
                                new OA\Property(
                                    property: "has_rainbow",
                                    description: "Наличие радуги",
                                    type: "boolean",
                                    example: true,
                                    nullable: true
                                ),
                                new OA\Property(
                                    property: "has_wind",
                                    description: "Наличие ветра",
                                    type: "boolean",
                                    example: true,
                                    nullable: true
                                ),
                                new OA\Property(
                                    property: "has_fog",
                                    description: "Наличие тумана",
                                    type: "boolean",
                                    example: true,
                                    nullable: true
                                )
                            ],
                            type: "object"
                        )
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: 500,
                description: "Внутренняя ошибка сервера"
            )
        ]
    )]
    public function randomIdea(): JsonResponse
    {
        $ideas = [
            [
                'description' => 'Легкий снежок кружится под фонарями, создавая новогоднее настроение',
                'mood' => 'Сказочная',
                'season' => 'Зима',
                'weather_type' => 'Снежно',
                'color_palette' => 'Холодные тона'
            ],
            [
                'description' => 'Тёплый летний дождь, после которого появляется радуга',
                'mood' => 'Освежающая',
                'season' => 'Лето',
                'weather_type' => 'Дождливо',
                'has_rainbow' => true
            ],
            [
                'description' => 'Золотая осень, листва шуршит под ногами, солнце пробивается сквозь облака',
                'mood' => 'Ностальгическая',
                'season' => 'Осень',
                'weather_type' => 'Облачно',
                'color_palette' => 'Теплые тона'
            ],
            [
                'description' => 'Весенняя гроза, воздух пахнет озоном и свежестью',
                'mood' => 'Обновляющая',
                'season' => 'Весна',
                'weather_type' => 'Гроза',
                'has_wind' => true
            ],
            [
                'description' => 'Туманное утро, город окутан молочной пеленой',
                'mood' => 'Таинственная',
                'season' => 'Осень',
                'weather_type' => 'Туманно',
                'has_fog' => true
            ],
            [
                'description' => 'Яркий солнечный день, небо без единого облачка',
                'mood' => 'Бодрая',
                'season' => 'Лето',
                'weather_type' => 'Солнечно',
                'color_palette' => 'Яркие тона'
            ]
        ];

        return response()->json([
            'idea' => $ideas[array_rand($ideas)]
        ]);
    }
}
