<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;

interface OrderRepositoryInterface
{
    public function index($user);

    public function create(array $data): Order;

    public function find($id): ?Order;

    public function updateStatus($orderId, $status);

    /** Update a single order_item status */
    public function rejectMedicine($orderId, $medicineId);
}
