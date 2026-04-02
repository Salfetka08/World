<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Application\Services\TimeService;
use Carbon\Carbon;

class TimeServiceTest extends TestCase
{
    private TimeService $timeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->timeService = new TimeService();
    }

    public function test_get_day_time_returns_morning()
    {
        // Подменяем текущее время на 8 утра
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 8, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('MORNING', $result);
    }

    public function test_get_day_time_returns_morning_at_5am()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 5, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('MORNING', $result);
    }

    public function test_get_day_time_returns_morning_at_11_59am()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 11, 59, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('MORNING', $result);
    }

    public function test_get_day_time_returns_afternoon()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 14, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('AFTERNOON', $result);
    }

    public function test_get_day_time_returns_afternoon_at_12pm()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('AFTERNOON', $result);
    }

    public function test_get_day_time_returns_afternoon_at_4_59pm()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 16, 59, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('AFTERNOON', $result);
    }

    public function test_get_day_time_returns_evening()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 19, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('EVENING', $result);
    }

    public function test_get_day_time_returns_evening_at_5pm()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 17, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('EVENING', $result);
    }

    public function test_get_day_time_returns_evening_at_9_59pm()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 21, 59, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('EVENING', $result);
    }

    public function test_get_day_time_returns_night()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 23, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('NIGHT', $result);
    }

    public function test_get_day_time_returns_night_at_10pm()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 22, 0, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('NIGHT', $result);
    }

    public function test_get_day_time_returns_night_at_4_59am()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 1, 4, 59, 0, 'UTC'));

        $result = $this->timeService->getDayTime();

        $this->assertEquals('NIGHT', $result);
    }

    // Тесты для сезонов
    public function test_get_season_returns_spring()
    {
        Carbon::setTestNow(Carbon::create(2024, 3, 15, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SPRING', $result);
    }

    public function test_get_season_returns_spring_at_march()
    {
        Carbon::setTestNow(Carbon::create(2024, 3, 1, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SPRING', $result);
    }

    public function test_get_season_returns_spring_at_may()
    {
        Carbon::setTestNow(Carbon::create(2024, 5, 31, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SPRING', $result);
    }

    public function test_get_season_returns_summer()
    {
        Carbon::setTestNow(Carbon::create(2024, 7, 15, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SUMMER', $result);
    }

    public function test_get_season_returns_summer_at_june()
    {
        Carbon::setTestNow(Carbon::create(2024, 6, 1, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SUMMER', $result);
    }

    public function test_get_season_returns_summer_at_august()
    {
        Carbon::setTestNow(Carbon::create(2024, 8, 31, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('SUMMER', $result);
    }

    public function test_get_season_returns_autumn()
    {
        Carbon::setTestNow(Carbon::create(2024, 10, 15, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('AUTUMN', $result);
    }

    public function test_get_season_returns_autumn_at_september()
    {
        Carbon::setTestNow(Carbon::create(2024, 9, 1, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('AUTUMN', $result);
    }

    public function test_get_season_returns_autumn_at_november()
    {
        Carbon::setTestNow(Carbon::create(2024, 11, 30, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('AUTUMN', $result);
    }

    public function test_get_season_returns_winter()
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('WINTER', $result);
    }

    public function test_get_season_returns_winter_at_december()
    {
        Carbon::setTestNow(Carbon::create(2024, 12, 1, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('WINTER', $result);
    }

    public function test_get_season_returns_winter_at_february()
    {
        Carbon::setTestNow(Carbon::create(2024, 2, 28, 12, 0, 0, 'UTC'));

        $result = $this->timeService->getSeason();

        $this->assertEquals('WINTER', $result);
    }

    protected function tearDown(): void
    {
        // Сбрасываем подмену времени после тестов
        Carbon::setTestNow(null);
        parent::tearDown();
    }
}
