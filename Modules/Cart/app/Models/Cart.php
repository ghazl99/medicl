<?php

namespace Modules\Cart\Models;

use Modules\User\Models\User;
use Modules\Cart\Models\CartItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cart\Database\Factories\CartFactory;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
