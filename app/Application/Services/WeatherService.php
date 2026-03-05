<?php

namespace App\Application\Services;

use App\Application\Models\WeatherDataModel;

class WeatherService
{
    /**
     * Получить погоду по координатам
     *
     * @return WeatherDataModel
     */
    public function fetchWeather(): WeatherDataModel
    {
        $tempBase = 10;
        return new WeatherDataModel(
            temperature: $tempBase,
            feelsLike: $tempBase - 2,
            condition: "SHOW",
            humidity: 50,
            windSpeed: 5,
            sunrise: '06:30',
            sunset: '20:15',
        );
    }
}
