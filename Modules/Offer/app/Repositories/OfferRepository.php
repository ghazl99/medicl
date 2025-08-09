<?php

namespace Modules\Offer\Repositories;

use Modules\Medicine\Models\OfferSupplierMedicine;

interface OfferRepository
{
    public function allWithMedicines();

    public function store(array $data);
    public function offerWithRelation(OfferSupplierMedicine $offer): mixed;


}
