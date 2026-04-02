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
    ): Collection {
        // Простой запрос без расчета расстояния
        $query = EntertainmentPlace::active();

        if ($category) {
            $query->where('category', $category);
        }

        return $query->limit($limit)->get();
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
