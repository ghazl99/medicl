<?php

namespace Modules\Cart\Repositories;

use Modules\Cart\Models\Cart;
use Modules\Cart\Models\CartItem;
use Modules\Cart\Repositories\CartRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    // Get existing cart for user or create a new one
    public function getOrCreateUserCart(int $userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    // Add item to cart or increase quantity if already exists
    public function addItem(int $cartId, int $medicineId, int $supplierId, int $quantity)
    {
        $existingItem = CartItem::where('cart_id', $cartId)
            ->where('medicine_id', $medicineId)
            ->where('supplier_id', $supplierId)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->save();
            return $existingItem;
        }

        return CartItem::create([
            'cart_id' => $cartId,
            'medicine_id' => $medicineId,
            'supplier_id' => $supplierId,
            'quantity' => $quantity,
        ]);
    }

    // Get all items in a user's cart
    public function getUserCartItems(int $userId)
    {
        return CartItem::with(['medicine', 'supplier'])
            ->whereHas('cart', fn($q) => $q->where('user_id', $userId))
            ->get();
    }

    // Delete an item from the cart and return remaining count
    public function deleteItem($cartItemId)
    {
        $cartItem = CartItem::with('cart')->findOrFail($cartItemId);
        $userId = $cartItem->cart->user_id;

        $cartItem->delete();

        return CartItem::whereHas('cart', fn($q) => $q->where('user_id', $userId))->count();
    }
    // Update the quantity or note of a cart item

    public function update($id, array $data)
    {
        $cartItem = CartItem::findOrFail($id);

        $cartItem->fill($data);
        $cartItem->save();

        return $cartItem;
    }
}
