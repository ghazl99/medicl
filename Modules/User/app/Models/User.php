<?php

namespace Modules\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Scout\Searchable;
use Modules\Order\Models\Order;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, Searchable;

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
        'city',
        'password',
        'profile_photo','is_approved'
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
        if ($this->profile_photo && Storage::disk('public')->exists('profile_photos/'.$this->profile_photo)) {
            return url('storage/profile_photos/'.$this->profile_photo);
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

    public function Medicines()
    {
        return $this->belongsToMany(\Modules\Medicine\Models\Medicine::class, 'medicine_user', 'user_id', 'medicine_id')
            ->withPivot('is_available')
            ->withTimestamps();
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'workplace_name' => $this->workplace_name,
            'city' => $this->city,
        ];
    }
}
