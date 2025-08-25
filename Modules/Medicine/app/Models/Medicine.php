<?php

namespace Modules\Medicine\Models;

use Laravel\Scout\Searchable;
use Modules\Order\Models\Order;
use Modules\Cart\Models\CartItem;
use Spatie\MediaLibrary\HasMedia;
use Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Medicine\Database\Factories\MedicineFactory;

class Medicine extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;

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
        'category_id',
        'type','type_ar',
        'composition',
        'form',
        'company',
        'note',
        'net_dollar_new',
        'public_dollar_new',
        'is_new',
        'new_start_date',
        'new_end_date',
        'description',
    ];

    protected $casts = [
        'net_dollar_new' => 'decimal:2',
        'public_dollar_new' => 'decimal:2',
    ];

    /**
     * Get the orders that include this medicine.
     *
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'medicine_id', 'order_id')
            ->withPivot(['quantity', 'status', 'note','rejection_reason','offer_qty','offer_free_qty'])
            ->withTimestamps();
    }

    public function suppliers()
    {
        return $this->belongsToMany(\Modules\User\Models\User::class, 'medicine_user', 'medicine_id', 'user_id')
            ->withPivot(
                'id',
                'is_available',
                'notes',
                'price','offer_qty','offer_free_qty'
            )
            ->withTimestamps();
    }
    public function scopeAvailableSuppliers($query)
    {
        return $query->with(['suppliers' => function ($q) {
            $q->where('is_approved', true);
        }]);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toSearchableArray()
    {

        return [
            'type' => $this->type,
            'type_ar'=>$this->type_ar,
            'composition' => $this->composition,
            'form' => $this->form,
            'company' => $this->company,
            'note' => $this->note,
            'description' => $this->description,
        ];
    }
}
