<?php

namespace App\Application\Services;

use App\Application\Models\WeatherDataModel;

class WeatherService
{
    private string $provider;

    public function __construct()
    {
        $this->provider = config('services.weather.provider', 'mock');
    }

    /**
     * Получить погоду по координатам
     *
     * @param float $latitude
     * @param float $longitude
     * @return WeatherDataModel
     */
    public function fetchWeather(float $latitude, float $longitude): WeatherDataModel
    {
        // TODO: Потенциально можно брать погоду из открытых источников.
        return match($this->provider) {
            'mock' => $this->fetchMockData($latitude, $longitude),
            default => $this->fetchMockData($latitude, $longitude),
        };
    }

    /**
     * Мок-данные для разработки (без API ключа)
     */
    private function fetchMockData(float $latitude, float $longitude): WeatherDataModel
    {
        // Простая логика для демо: на основе координат генерируем "погоду"
        $hash = crc32("{$latitude}{$longitude}");
        $tempBase = 10 + ($hash % 20); // 10-30 градусов
        $conditionIndex = abs($hash) % 5;

        $conditions = ['CLEAR', 'CLOUDY', 'RAINY', 'SNOWY', 'STORMY'];
        $condition = $conditions[$conditionIndex];

        // Корректируем температуру для снега
        if ($condition === 'SNOWY') {
            $tempBase = -5 - (abs($hash) % 10);
        }

        return new WeatherDataModel(
            temperature: $tempBase,
            feelsLike: $tempBase - 2,
            condition: $condition,
            humidity: 50 + (abs($hash) % 40),
            windSpeed: 5 + (abs($hash) % 15),
            sunrise: '06:30',
            sunset: '20:15',
            icon: $this->getMockIcon($condition)
        );
    }

    /**
     * Иконка для мок-данных
     */
    private function getMockIcon(string $condition): string
    {
        return match($condition) {
            'CLEAR' => '01d',
            'CLOUDY' => '03d',
            'RAINY' => '10d',
            'SNOWY' => '13d',
            'STORMY' => '11d',
            default => '01d',
        };
    }
}
