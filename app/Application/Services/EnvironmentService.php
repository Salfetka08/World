<?php

namespace App\Application\Services;

use App\Application\Models\EnvironmentDataModel;
use Illuminate\Support\Facades\Http;

class EnvironmentService
{
    public function fetchEnvironment(float $lat = 55.7558, float $lon = 37.6173): EnvironmentDataModel {

        try {

            $response = Http::timeout(10)->get(
                'https://api.open-meteo.com/v1/forecast',
                [
                    'latitude' => $lat,
                    'longitude' => $lon,

                    'current' => implode(',', [
                        'temperature_2m',
                        'relative_humidity_2m',
                        'apparent_temperature',
                        'weather_code',
                        'pressure_msl',
                        'wind_speed_10m',
                        'wind_gusts_10m',
                        'cloud_cover',
                    ]),

                    'daily' => implode(',', [
                        'sunrise',
                        'sunset',
                    ]),

                    'timezone' => 'auto',
                ]
            );

            if ($response->failed()) {
                error_log($response->body());
                return $this->getFallbackData();
            }

            $data = $response->json();
            $location = $this->getLocationData($lat, $lon);

            return $this->mapOpenWeatherResponse($data, $location);

        } catch (\Exception $e) {

            error_log($e->getMessage());

            return $this->getFallbackData();
        }
    }

    private function mapOpenWeatherResponse(array $data, array $location): EnvironmentDataModel
    {
        $current = $data['current'] ?? [];
        $daily = $data['daily'] ?? [];

        $weatherCode = $current['weather_code'] ?? 0;

        $conditionMap = [
            0 => 'CLEAR',
            1 => 'PARTLY_CLOUDY',
            2 => 'CLOUDY',
            3 => 'OVERCAST',

            45 => 'FOG',
            48 => 'RIME_FOG',

            51 => 'DRIZZLE',
            53 => 'DRIZZLE',
            55 => 'HEAVY_DRIZZLE',

            61 => 'LIGHT_RAIN',
            63 => 'RAIN',
            65 => 'HEAVY_RAIN',

            71 => 'LIGHT_SNOW',
            73 => 'SNOW',
            75 => 'HEAVY_SNOW',

            80 => 'SHOWERS',
            81 => 'HEAVY_SHOWERS',
            82 => 'VIOLENT_SHOWERS',

            95 => 'THUNDERSTORM',
            96 => 'THUNDERSTORM_HAIL',
            99 => 'SEVERE_THUNDERSTORM',
        ];

        return new EnvironmentDataModel(
            temperature: round($current['temperature_2m'] ?? 0),
            feelsLike: round($current['apparent_temperature'] ?? 0),
            condition: $conditionMap[$weatherCode] ?? 'UNDEFINED',
            humidity: $current['relative_humidity_2m'] ?? 0,
            windSpeed: $current['wind_speed_10m'] ?? 0,
            sunrise: isset($daily['sunrise'][0])
                ? date('H:i', strtotime($daily['sunrise'][0]))
                : '06:00',
            sunset: isset($daily['sunset'][0])
                ? date('H:i', strtotime($daily['sunset'][0]))
                : '18:00',
            datetime: $current['time'] ?? now()->toDateTimeString(),
            timestamp: isset($current['time'])
                ? strtotime($current['time'])
                : time(),
            city: $location['city'],
            country: $location['country'],
            timezone: $data['timezone'] ?? null,
            timezone_offset: $data['utc_offset_seconds'] ?? 0,
            pressure: $current['pressure_msl'] ?? null,
            windGust: $current['wind_gusts_10m'] ?? null,
            clouds: $current['cloud_cover'] ?? null,
            visibility: null,
        );
    }

    private function getLocationData(float $lat, float $lon): array
    {
        try {

            $response = Http::timeout(5)
                ->withHeaders([
                    'User-Agent' => 'WorldApp/1.0'
                ])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat,
                    'lon' => $lon,
                    'format' => 'json',
                    'accept-language' => 'en',
                ]);

            if ($response->failed()) {
                return [
                    'city' => 'Unknown',
                    'country' => null,
                ];
            }

            $data = $response->json();

            return [
                'city' =>
                    $data['address']['city']
                    ?? $data['address']['town']
                        ?? $data['address']['village']
                        ?? 'Unknown',

                'country' => $data['address']['country'] ?? null,
            ];

        } catch (\Exception $e) {

            return [
                'city' => 'Unknown',
                'country' => null,
            ];
        }
    }

    private function getFallbackData(): EnvironmentDataModel
    {
        return new EnvironmentDataModel(
            temperature: 10,
            feelsLike: 8,
            condition: "CLOUDY",
            humidity: 65,
            windSpeed: 3,
            sunrise: '06:30',
            sunset: '20:15',
            datetime: date('Y-m-d H:i:s'),
            timestamp: time(),
            city: 'Fallback',
            country: 'RU',
            timezone: '+03:00',
            timezone_offset: 10800,
            pressure: 1013,
            windGust: 4.5,
            clouds: 75,
            visibility: 10000,
        );
    }
}
