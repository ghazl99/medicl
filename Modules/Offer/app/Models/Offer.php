<?php

namespace Modules\Offer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'details',
        'offer_start_date',
        'offer_end_date',
    ];

    protected $casts = [
        'offer_start_date' => 'date',
        'offer_end_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
