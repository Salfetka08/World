<?php


namespace App\Application\Services;

use App\Application\Models\UserLocationModel;
use App\Domain\Repositories\UserLocationRepository;
use App\Http\Requests\GetCurrentWorldRequest;

class UserLocationService
{
    public function __construct(
        private readonly UserLocationRepository $locationRepository)
    {}

    /**
     * Найти последнюю локацию пользователя
     */
    public function getLatestCoordinatesByUserId(GetCurrentWorldRequest $request): ?UserLocationModel
    {
//        if($request->user_id == 123){
            return new UserLocationModel(
                latitude: 55.7558,
                longitude: 37.6176,
                cityName: 'Москва',
                country: 'Россия'
            );
//        }
//        $location = $this->locationRepository->findLatestByUserId($request->user_id);
//        return UserLocationModel::fromEloquentModel($location);
    }
}
