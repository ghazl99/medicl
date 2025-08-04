<?php

namespace Modules\Offer\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Offer\Repositories\OfferRepository;
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
            ->paginate(5);
    }


    public function store(array $data)
    {
        return OfferSupplierMedicine::create($data);
    }
}
