<?php

namespace Modules\Offer\Repositories;

use Modules\Offer\Models\Offer;

class OfferModelRepository implements OfferRepository
{
    public function allOffers($user = null)
    {
        $query = \Modules\Offer\Models\Offer::query()->active()->latest();

        if ($user) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate(10);
    }

    public function store(array $data)
    {
        return Offer::create($data);
    }

    public function offerShow(Offer $offer): mixed
    {
        return $offer;
    }
}
