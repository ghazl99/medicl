<?php

namespace Modules\Medicine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\Order;

// use Modules\Medicine\Database\Factories\MedicineFactory;

class Medicine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'manufacturer',
        'quantity_available',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity_available' => 'integer',
        'price' => 'decimal:2', // يُحدد أن الحقل price يجب أن يُعامل كرقم عشري بدقة رقمين بعد الفاصلة
    ];

    /**
     * Get the orders that include this medicine.
     *
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'medicine_id', 'order_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(\Modules\User\Models\User::class, 'medicine_user', 'medicine_id', 'user_id')
            ->withPivot('is_available')
            ->withTimestamps();
    }
}
