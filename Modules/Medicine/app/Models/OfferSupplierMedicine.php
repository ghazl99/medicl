<?php

namespace Modules\Medicine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Medicine\Database\Factories\OfferSupplierMedicineFactory;

class OfferSupplierMedicine extends Model
{
    use HasFactory;

    protected $table = 'offer_supplier_medicine';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['medicine_user_id',
        'offer_buy_quantity',
        'offer_free_quantity',
        'offer_start_date',
        'offer_end_date',
        'notes', ];

    protected $casts = [
        'offer_start_date' => 'date',
        'offer_end_date' => 'date',
    ];

    public function medicineUser()
    {
        return $this->belongsTo(MedicineUser::class, 'medicine_user_id');
    }
}
