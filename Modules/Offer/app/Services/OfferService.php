<?php

namespace Modules\Offer\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\ImageTrait;
use Modules\Offer\Models\Offer;
use Modules\Offer\Repositories\OfferRepository;

class OfferService
{
    use ImageTrait;

    public function __construct(protected OfferRepository $offerRepository) {}

    public function getAll($user)
    {
        return $this->offerRepository->allOffers($user);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $images = $data['images'];
            unset($data['images']);

            $offer = $this->offerRepository->store($data);

            if (! empty($images) && is_array($images)) {
                $this->uploadOrUpdateImageWithResize(
                    $offer,
                    $images,
                    'offer_images',
                    'private_media',
                    false
                );
            }

            return $offer;
        });
    }

    public function details(Offer $offer)
    {
        return $this->offerRepository->offerShow($offer);
    }
}
