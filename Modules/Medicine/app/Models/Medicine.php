<?php

namespace Modules\Medicine\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Modules\Category\Models\Category;
use Modules\Order\Models\Order;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
        'price_change_percentage',
        'is_new',
        'new_start_date',
        'new_end_date','description'
    ];

    protected $casts = [
        'net_dollar_old' => 'decimal:2',
        'public_dollar_old' => 'decimal:2',
        'net_dollar_new' => 'decimal:2',
        'public_dollar_new' => 'decimal:2',
        'net_syp' => 'decimal:2',
        'public_syp' => 'decimal:2',
    ];

    /**
     * Get the orders that include this medicine.
     *
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'medicine_id', 'order_id')
            ->withPivot(['quantity', 'status'])
            ->withTimestamps();
    }

    public function suppliers()
    {
        return $this->belongsToMany(\Modules\User\Models\User::class, 'medicine_user', 'medicine_id', 'user_id')
            ->withPivot(
                'id',
                'is_available',
                'notes',
                'offer'
            )
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toSearchableArray()
    {

        return [
            'type' => $this->type,
            'composition' => $this->composition,
            'form' => $this->form,
            'company' => $this->company,
            'note' => $this->note,
            'description'=> $this->description,
        ];
    }
}
