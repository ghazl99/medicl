<?php

namespace Modules\Order\Repositories;

use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;

interface OrderRepositoryInterface
{
    // Get orders list based on user role
    public function index($user);

    // Create new order with given data
    public function create(array $data): Order;

    // Find order by ID or return null
    public function find($id): ?Order;

    // Update status of order by ID
    public function updateStatus($orderId, $status);

    // Reject a medicine item in an order with a note
    public function rejectMedicine(Order $order, Medicine $medicine, $note);

    // Update medicine quantity and status in an order
    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity);
}
