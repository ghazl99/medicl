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
        'type',
        'composition',
        'form',
        'company',
        'note',
        'net_dollar_old',
        'public_dollar_old',
        'net_dollar_new',
        'public_dollar_new',
        'net_syp',
        'public_syp',
        'note_2',
        'price_change_percentage',
    ];

    protected $casts = [
        'net_dollar_old'     => 'decimal:2',
        'public_dollar_old'  => 'decimal:2',
        'net_dollar_new'     => 'decimal:2',
        'public_dollar_new'  => 'decimal:2',
        'net_syp'            => 'decimal:2',
        'public_syp'         => 'decimal:2',
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
