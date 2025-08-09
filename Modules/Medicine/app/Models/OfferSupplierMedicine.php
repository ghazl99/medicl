<?php

namespace Modules\Medicine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

// use Modules\Medicine\Database\Factories\OfferSupplierMedicineFactory;

class OfferSupplierMedicine extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $table = 'offer_supplier_medicine';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['medicine_user_id',
        'title',
        'details',
        'offer_start_date',
        'offer_end_date' ];

    protected $casts = [
        'offer_start_date' => 'date',
        'offer_end_date' => 'date',
    ];

    public function medicineUser()
    {
        return $this->belongsTo(MedicineUser::class, 'medicine_user_id');
    }
}
