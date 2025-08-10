<?php

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Modules\Medicine\Models\Medicine;
use Modules\Offer\Models\Offer;
use Modules\Order\Models\Order;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, InteractsWithMedia, Notifiable, Searchable;

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
        'is_approved', 'fcm_token',
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
                'offer',
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
}
