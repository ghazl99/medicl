<?php

namespace Modules\Offer\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Traits\ImageTrait;
use Modules\Offer\Repositories\OfferRepository;
use Modules\Medicine\Models\OfferSupplierMedicine;

class OfferService
{
    use ImageTrait;
    public function __construct(protected OfferRepository $offerRepository) {}

    public function getAll()
    {
        return $this->offerRepository->allWithMedicines();
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            $images = $data['images'] ;
            unset($data['images']);
            // dd(['data' => $images]);
            $offer = $this->offerRepository->store($data);
            // dd($offer);
            if (!empty($images) && is_array($images)) {
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

    public function details(OfferSupplierMedicine $offer)
    {
        return $this->offerRepository->offerWithRelation($offer);
    }
}
