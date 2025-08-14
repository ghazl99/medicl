<?php

namespace Modules\Cart\Services;

use Modules\Cart\Repositories\CartRepositoryInterface;

class CartService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
    ) {}

    public function addToCart(int $userId, int $medicineId, int $supplierId, int $quantity)
    {
        $cart = $this->cartRepository->getOrCreateUserCart($userId);

        return $this->cartRepository->addItem($cart->id, $medicineId, $supplierId, $quantity);
    }

    public function getUserCartItems(int $userId)
    {
        return $this->cartRepository->getUserCartItems($userId);
    }
    public function updateQuantity($cartItemId, $quantity)
    {
        return $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    public function deleteItem($cartItemId)
    {
        return $this->cartRepository->deleteItem($cartItemId);
    }
}
