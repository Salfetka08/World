<?php

namespace App\Application\Models;

class CurrentWorldModel
{
    public function __construct(
        public readonly int              $userId,
        public readonly string           $location,
        public readonly WeatherDataModel $weather,
        public readonly string           $dayTime,
        public readonly string           $season,
        public readonly ?string          $sunrise,
        public readonly ?string          $sunset,
        public readonly string           $updatedAt
    )
    {
    }
}
