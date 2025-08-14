<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

// use Modules\Core\Database\Factories\NotificationFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'data',
        'is_read', 'url',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead()
    {
        return $this->read_at !== null;
    }
}
