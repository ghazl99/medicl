<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\CityRepositoryInterface;

class CityService
{
    public function __construct(protected CityRepositoryInterface $cityRepository) {}
    public function getAllCitiesWithSubCities()
    {
        return $this->cityRepository->getAllWithSubCities();
    }
}
