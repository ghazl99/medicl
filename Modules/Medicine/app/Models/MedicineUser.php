<?php

namespace Modules\Medicine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Medicine\Database\Factories\MedicineUserFactory;

class MedicineUser extends Model
{
    use HasFactory;

    protected $table = 'medicine_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['medicine_id', 'user_id', 'is_available', 'notes'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function offers()
    {
        return $this->hasMany(OfferSupplierMedicine::class, 'medicine_user_id');
    }
}
