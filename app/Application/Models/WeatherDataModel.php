<?php

namespace App\Application\Models;

class WeatherDataModel
{
    public function __construct(
        public readonly float $temperature,
        public readonly ?float $feelsLike,
        public readonly string $condition,
        public readonly ?int $humidity,
        public readonly ?float $windSpeed,
        public readonly ?string $sunrise = null,
        public readonly ?string $sunset = null,
        public readonly ?string $icon = null
    ) {}
}
