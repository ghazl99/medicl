<?php

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Scout\Searchable;
use Modules\Cart\Models\Cart;
use Modules\Offer\Models\Offer;
use Modules\Order\Models\Order;
use Modules\Cart\Models\CartItem;
use Spatie\MediaLibrary\HasMedia;
use Modules\Core\Models\Notification;
// use Illuminate\Notifications\Notifiable;
use Modules\Medicine\Models\Medicine;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'workplace_name',
        'password',
        'is_approved',
        'fcm_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfilePhotoUrlAttribute()
    {
        $media = $this->getFirstMedia('profile_photo');
        if ($media) {
            return route('user.profile_photo', $media);
        }

        $firstLetter = strtoupper(mb_substr($this->name, 0, 1));
        $name = urlencode($firstLetter);

        return "https://ui-avatars.com/api/?name={$name}&background=0D8ABC&color=fff&size=256";
    }

    /**
     * Get the orders placed by this user (as a pharmacist).
     *
     * @return HasMany
     */
    public function placedOrders()
    {
        return $this->hasMany(Order::class, 'pharmacist_id');
    }

    /**
     * Get the orders received by this user (as a supplier).
     *
     * @return HasMany
     */
    public function receivedOrders()
    {
        return $this->hasMany(Order::class, 'supplier_id');
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medicine_user')
            ->withPivot([
                'id',
                'is_available',
                'notes',
                'price','offer_qty','offer_free_qty'
            ])
            ->withTimestamps();
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'workplace_name' => $this->workplace_name,
        ];
    }

    public function cities()
    {
        return $this->belongsToMany(\Modules\Core\Models\City::class, 'city_user');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function suppliedItems()
    {
        return $this->hasMany(CartItem::class, 'supplier_id');
    }

    public function scopeAvailableSuppliers($query)
    {
        return $query->where('is_approved', true);
    }
}
