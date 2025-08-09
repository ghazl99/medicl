<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\City;
use Modules\Core\Repositories\CityRepositoryInterface;

class CityRepository implements CityRepositoryInterface
{
    public function getAllWithSubCities()
    {
        return City::with('children')->whereNull('parent_id')->get();
    }
}
