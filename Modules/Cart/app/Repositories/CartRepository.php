<?php

namespace Modules\Cart\Repositories;

use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartItem;
use Modules\Cart\Repositories\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public function getOrCreateUserCart(int $userId)
    {
        return Cart::firstOrCreate(
            ['user_id' => $userId]
        );
    }

    public function addItem(int $cartId, int $medicineId, int $supplierId, int $quantity)
    {
        return CartItem::create([
            'cart_id' => $cartId,
            'medicine_id' => $medicineId,
            'supplier_id' => $supplierId,
            'quantity' => $quantity,
        ]);
    }

    public function getUserCartItems(int $userId)
    {
        return CartItem::with(['medicine', 'supplier'])
            ->whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->get();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->quantity = $quantity;
        $cartItem->save();
        return $cartItem;
    }

    public function deleteItem($cartItemId)
    {
        $cartItem = CartItem::with('cart')->findOrFail($cartItemId);

        $userId = $cartItem->cart->user_id;

        $cartItem->delete();

        $cartCount = CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))->count();
        return $cartCount;
    }
}
