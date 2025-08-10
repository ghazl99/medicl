<?php

namespace Modules\Offer\Repositories;

use Modules\Offer\Models\Offer;

class OfferModelRepository implements OfferRepository
{
    public function allOffers($user)
    {
        return $user->offers()
            ->latest()
            ->paginate(10);

    }

    public function store(array $data)
    {
        // dd($data);
        return Offer::create($data);
    }

    public function offerShow(Offer $offer): mixed
    {
        return $offer;
    }
}
