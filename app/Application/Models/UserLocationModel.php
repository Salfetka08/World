<?php

namespace App\Application\Models;

use App\Domain\EloquentModels\UserLocation;

class UserLocationModel
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly ?string $cityName,
        public readonly ?string $country
    ) {}

    public static function fromEloquentModel(?UserLocation $location): ?self
    {
        if (!$location) {
            return null;
        }

        return new self(
            latitude: (float) $location->latitude,
            longitude: (float) $location->longitude,
            cityName: $location->city_name,
            country: $location->country
        );
    }
}
