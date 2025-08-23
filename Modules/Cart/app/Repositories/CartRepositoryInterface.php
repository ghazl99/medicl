<?php

namespace Modules\Cart\Repositories;

interface CartRepositoryInterface
{
    public function getOrCreateUserCart(int $userId);
    public function addItem(int $cartId, int $medicineId, int $supplierId, int $quantity);
    public function getUserCartItems(int $userId);
    public function deleteItem($cartItemId);
    public function update($id, array $data);

}
