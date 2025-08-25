<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Medicine\Models\Medicine;
use Modules\User\Models\User;

// use Modules\Order\Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'pharmacist_id',
        'supplier_id',
        'order_date',
        'status',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * Get the pharmacist who placed this order.
     *
     * @return BelongsTo
     */
    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    /**
     * Get the supplier for this order.
     *
     * @return BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the medicines associated with this order.
     *
     * @return BelongsToMany
     */
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'order_items')
            ->withPivot(['quantity', 'status', 'note', 'rejection_reason', 'offer_qty', 'offer_free_qty'])
            ->withTimestamps();
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'تم التسليم');
    }
}
