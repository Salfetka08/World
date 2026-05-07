<?php

namespace App\Application\Models;

class EnvironmentDataModel
{
    public function __construct(
        public float   $temperature,
        public float   $feelsLike,
        public string  $condition,
        public int     $humidity,
        public float   $windSpeed,
        public string  $sunrise,
        public string  $sunset,
        public ?string $datetime = null,
        public ?int    $timestamp = null,
        public ?string $city = null,
        public ?string $country = null,
        public ?string $timezone = null,
        public ?int    $timezone_offset = null,
        public ?int    $pressure = null,
        public ?float  $windGust = null,
        public ?int    $clouds = null,
        public ?float  $visibility = null,
    ) {
        $this->timestamp = $timestamp ?? time();
        $this->datetime = $datetime ?? date('Y-m-d H:i:s', $this->timestamp);
    }

    public function toArray(): array
    {
        return [
            'temperature' => $this->temperature,
            'feelsLike' => $this->feelsLike,
            'condition' => $this->condition,
            'humidity' => $this->humidity,
            'windSpeed' => $this->windSpeed,
            'sunrise' => $this->sunrise,
            'sunset' => $this->sunset,
            'datetime' => $this->datetime,
            'timestamp' => $this->timestamp,
            'city' => $this->city,
            'country' => $this->country,
            'timezone' => $this->timezone,
            'timezone_offset' => $this->timezone_offset,
            'pressure' => $this->pressure,
            'windGust' => $this->windGust,
            'clouds' => $this->clouds,
            'visibility' => $this->visibility,
        ];
    }
}
