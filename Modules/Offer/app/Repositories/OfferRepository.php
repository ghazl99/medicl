<?php

namespace Modules\Offer\Repositories;

interface  OfferRepository
{
    public function allWithMedicines();
    public function store(array $data);
}
