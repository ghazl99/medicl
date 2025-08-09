<?php

namespace Modules\Offer\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Medicine\Models\OfferSupplierMedicine;

class OfferModelRepository implements OfferRepository
{
    public function allWithMedicines()
    {
        $supplierId = Auth::id();

        return OfferSupplierMedicine::whereHas('medicineUser', function ($query) use ($supplierId) {
            $query->where('user_id', $supplierId);
        })
            ->with(['medicineUser.medicine'])
            ->latest()
            ->paginate(10);
    }

    public function store(array $data)
    {
        // dd($data);
        return OfferSupplierMedicine::create($data);
    }

    public function offerWithRelation(OfferSupplierMedicine $offer):mixed
    {
        return $offer->load('medicineUser.medicine');
    }
}
