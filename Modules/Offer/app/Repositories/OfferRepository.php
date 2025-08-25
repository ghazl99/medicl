<?php

namespace Modules\Offer\Repositories;

use Modules\Offer\Models\Offer;

interface OfferRepository
{
    public function allOffers($user = null);

    public function store(array $data);

    public function offerShow(Offer $offer): mixed;
}
