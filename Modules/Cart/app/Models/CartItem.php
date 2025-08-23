<?php

namespace Modules\Cart\Models;

use Modules\User\Models\User;
use Modules\Medicine\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cart\Database\Factories\CartItemFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'medicine_id', 'supplier_id', 'quantity', 'price','note'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
