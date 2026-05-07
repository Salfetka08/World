<?php

namespace App\Domain\Repositories;

use App\Domain\EloquentModels\EntertainmentPlace;
use Illuminate\Database\Eloquent\Collection;

class EntertainmentPlaceRepository
{
    /**
     * Найти места в радиусе от координат (упрощенная версия)
     */
    public function findNearby(
        float $latitude,
        float $longitude,
        float $radiusKm = 5.0,
        ?string $category = null,
        int $limit = 20
    ): \Illuminate\Support\Collection
    {

        $places = EntertainmentPlace::active()->get();

        if ($category) {
            $places = $places->where('category', $category);
        }

        $places = $places
            ->map(function ($place) use ($latitude, $longitude) {

                $distance = $this->calculateDistance(
                    $latitude,
                    $longitude,
                    $place->latitude,
                    $place->longitude
                );

                $place->distance = $distance;

                return $place;
            })
            ->filter(fn($place) => $place->distance <= $radiusKm)
            ->sortBy('distance')
            ->take($limit);

        return collect($places->values());
    }

    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {

        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Найти места по категориям
     */
    public function findByCategories(
        float $latitude,
        float $longitude,
        array $categories,
        int $limitPerCategory = 5
    ): array {
        $result = [];

        foreach ($categories as $category) {
            $result[$category] = $this->findNearby(
                $latitude,
                $longitude,
                5.0,
                $category,
                $limitPerCategory
            );
        }

        return $result;
    }

    /**
     * Получить место по ID
     */
    public function findById(int $id): ?EntertainmentPlace
    {
        return EntertainmentPlace::active()->find($id);
    }

    /**
     * Получить все категории мест
     */
    public function getAllCategories(): array
    {
        return EntertainmentPlace::active()
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Создать новое место
     */
    public function create(array $data): EntertainmentPlace
    {
        return EntertainmentPlace::create($data);
    }

    /**
     * Обновить место
     */
    public function update(int $id, array $data): bool
    {
        $place = EntertainmentPlace::find($id);
        if (!$place) {
            return false;
        }

        return $place->update($data);
    }

    /**
     * Удалить место (мягкое удаление)
     */
    public function delete(int $id): bool
    {
        $place = EntertainmentPlace::find($id);
        if (!$place) {
            return false;
        }

        return $place->update(['is_active' => false]);
    }

    /**
     * Поиск мест по городу
     */
    public function findByCity(string $city, ?string $category = null, int $limit = 50): Collection
    {
        $query = EntertainmentPlace::active()->where('city', $city);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Получить топ мест по рейтингу в городе
     */
    public function getTopRated(string $city, int $limit = 10): Collection
    {
        return EntertainmentPlace::active()
            ->where('city', $city)
            ->orderBy('rating', 'desc')
            ->limit($limit)
            ->get();
    }
}
