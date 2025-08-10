<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\City;

class CityRepository implements CityRepositoryInterface
{
    public function getAllWithSubCities()
    {
        return City::with('children')->whereNull('parent_id')->get();
    }
}
