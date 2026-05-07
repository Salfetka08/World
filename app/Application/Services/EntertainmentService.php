<?php

namespace App\Application\Services;

use App\Domain\Repositories\EntertainmentPlaceRepository;

class EntertainmentService
{
    public function __construct(
        private readonly EntertainmentPlaceRepository $repository
    ) {}

    /**
     * Поиск развлекательных мест рядом с координатами
     */
    public function findNearbyPlaces(
        float $latitude,
        float $longitude,
        float $radiusKm = 5.0,
        ?string $category = null,
        int $limit = 10
    ): array {

        $places = $this->repository->findNearby(
            $latitude,
            $longitude,
            $radiusKm,
            $category,
            $limit
        );

        return $this->formatPlacesResponse($places);
    }

    /**
     * Поиск мест по категориям
     */
    public function getPlacesByCategories(
        float $latitude,
        float $longitude,
        array $categories = [],
        int $limitPerCategory = 3
    ): array {
        if (empty($categories)) {
            $categories = ['restaurant', 'cafe', 'cinema', 'park', 'museum', 'club'];
        }

        $placesByCategory = $this->repository->findByCategories(
            $latitude,
            $longitude,
            $categories,
            $limitPerCategory
        );

        $result = [];
        foreach ($placesByCategory as $category => $places) {
            $result[$category] = $this->formatPlacesResponse($places);
        }

        return $result;
    }

    /**
     * Форматирование ответа
     */
    private function formatPlacesResponse($places): array
    {
        return $places->map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'category' => $place->category,
                'address' => $place->address,
                'city' => $place->city,
                'country' => $place->country,
                'rating' => $place->rating,
                'price_level' => $place->price_level,
                'phone' => $place->phone,
                'website' => $place->website,
                'details' => $place->details,
            ];
        })->toArray();
    }
}
