<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Application\Services\WorldService;
use App\Application\Services\WeatherService;
use App\Application\Services\TimeService;
use App\Application\Services\EntertainmentService;
use App\Application\Models\WeatherDataModel;
use App\Http\Requests\GetCurrentWorldRequest;
use Mockery;

class WorldServiceTest extends TestCase
{
    private WorldService $worldService;
    private $weatherServiceMock;
    private $entertainmentServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем моки только для WeatherService и EntertainmentService
        $this->weatherServiceMock = Mockery::mock(WeatherService::class);
        $this->entertainmentServiceMock = Mockery::mock(EntertainmentService::class);

        // Создаем реальный TimeService
        $timeService = new TimeService();

        // Создаем сервис с моками и реальным TimeService
        $this->worldService = new WorldService(
            $this->weatherServiceMock,
            $timeService,
            $this->entertainmentServiceMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_current_world_returns_correct_data()
    {

        // Создаем тестовый запрос
        $request = new GetCurrentWorldRequest();
        $request->userId = 1;
        $request->latitude = 55.7558;
        $request->longitude = 37.6176;

        // Создаем объект погоды
        $weatherDataModel = new WeatherDataModel(
            temperature: 20,
            feelsLike: 18,
            condition: 'CLEAR',
            humidity: 65,
            windSpeed: 5.5
        );

        // Настраиваем моки
        $this->weatherServiceMock
            ->shouldReceive('fetchWeather')
            ->once()
            ->with(55.7558, 37.6176)
            ->andReturn($weatherDataModel);

        $this->entertainmentServiceMock
            ->shouldReceive('findNearbyPlaces')
            ->once()
            ->with(55.7558, 37.6176, 5.0, null, 20)
            ->andReturn([
                ['id' => 1, 'name' => 'Test Place', 'distance_km' => 1.2]
            ]);

        $this->entertainmentServiceMock
            ->shouldReceive('getPlacesByCategories')
            ->once()
            ->with(55.7558, 37.6176, ['restaurant', 'cafe', 'cinema', 'park', 'museum'], 5)
            ->andReturn([
                'restaurant' => [['id' => 2, 'name' => 'Test Restaurant']],
                'cafe' => [],
                'cinema' => [],
                'park' => [],
                'museum' => []
            ]);

        // Выполняем метод
        $result = $this->worldService->getCurrentWorld($request);

        // Проверяем результат
        $this->assertInstanceOf(\App\Application\Models\CurrentWorldModel::class, $result);
        $this->assertEquals(1, $result->userId);
        $this->assertIsString($result->dayTime); // dayTime должен быть строкой
        $this->assertIsString($result->season);  // season должен быть строкой
        $this->assertEquals('08:00', $result->sunrise);
        $this->assertEquals('20:00', $result->sunset);

        // Проверяем погоду
        $this->assertEquals(20, $result->weather->temperature);
        $this->assertEquals('CLEAR', $result->weather->condition);

        // Проверяем развлечения
        $this->assertArrayHasKey('nearby_places', $result->entertainment);
        $this->assertArrayHasKey('places_by_category', $result->entertainment);
        $this->assertCount(1, $result->entertainment['nearby_places']);
    }

    public function test_get_current_world_handles_no_entertainment()
    {
        $request = new GetCurrentWorldRequest();
        $request->userId = 2;
        $request->latitude = 60.0;
        $request->longitude = 30.0;

        // Создаем объект погоды
        $weatherDataModel = new WeatherDataModel(
            temperature: 15,
            feelsLike: 14,
            condition: 'CLOUDY',
            humidity: 70,
            windSpeed: 10
        );

        // Мок погоды
        $this->weatherServiceMock
            ->shouldReceive('fetchWeather')
            ->andReturn($weatherDataModel);

        // Мок развлечений - пустой результат
        $this->entertainmentServiceMock
            ->shouldReceive('findNearbyPlaces')
            ->andReturn([]);

        $this->entertainmentServiceMock
            ->shouldReceive('getPlacesByCategories')
            ->andReturn([]);

        $result = $this->worldService->getCurrentWorld($request);

        $this->assertEmpty($result->entertainment['nearby_places']);
        $this->assertEmpty($result->entertainment['places_by_category']);
    }

    public function test_get_current_world_throws_exception_on_weather_error()
    {
        $request = new GetCurrentWorldRequest();
        $request->userId = 3;
        $request->latitude = 0;
        $request->longitude = 0;

        $this->weatherServiceMock
            ->shouldReceive('fetchWeather')
            ->andThrow(new \Exception('Weather service unavailable'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Weather service unavailable');

        $this->worldService->getCurrentWorld($request);
    }
}
