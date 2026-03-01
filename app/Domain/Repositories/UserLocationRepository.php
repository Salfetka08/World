<?php

namespace App\Domain\Repositories;

use App\Domain\EloquentModels\UserLocation;

class UserLocationRepository
{
    /**
     * Найти локацию по ID
     */
    public function findById(int $id): ?UserLocation
    {
        return UserLocation::query()->find($id);
    }

    /**
     * Найти последнюю локацию пользователя
     */
    public function findLatestByUserId(int $userId): ?UserLocation
    {
        return UserLocation::query()
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Создать новую локацию
     */
    public function create(array $data): UserLocation
    {
        return UserLocation::query()->create([
            'user_id' => $data['user_id'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'city_name' => $data['city_name'] ?? null,
            'country' => $data['country'] ?? null,
        ]);
    }

    /**
     * Обновить локацию
     */
    public function update(int $id, array $data): ?UserLocation
    {
        $location = $this->findById($id);

        if (!$location) {
            return null;
        }

        $updateData = array_intersect_key($data, array_flip([
            'latitude', 'longitude', 'city_name', 'country'
        ]));

        $location->update($updateData);

        return $location->fresh();
    }

    /**
     * Удалить локацию по ID
     */
    public function delete(int $id): bool
    {
        $location = $this->findById($id);

        if (!$location) {
            return false;
        }

        return $location->delete();
    }
}
