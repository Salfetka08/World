<?php

namespace App\Application\Models;

class CurrentWorldModel
{
    public function __construct(
        public readonly string $userId,
        public readonly WeatherDataModel $weather,
        public readonly string $dayTime,
        public readonly string $season,
        public readonly string $sunrise,
        public readonly string $sunset,
        public readonly string $updatedAt,
        public readonly array $entertainment = [] // Добавляем поле
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'weather' => $this->weather,
            'day_time' => $this->dayTime,
            'season' => $this->season,
            'sunrise' => $this->sunrise,
            'sunset' => $this->sunset,
            'updated_at' => $this->updatedAt,
            'entertainment' => $this->entertainment
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
