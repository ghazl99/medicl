<?php

namespace Modules\Offer\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Offer\Repositories\OfferRepository;

class OfferService
{
    public function __construct(protected OfferRepository $offerRepository) {}

    public function getAll()
    {
        return $this->offerRepository->allWithMedicines();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->offerRepository->store($data);

        });
    }
}
