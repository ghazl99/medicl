<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepositoryInterface;
use Modules\User\Repositories\UserRepositoryInterface;

class OrderService
{
    protected $orderRepository;

    protected $userRepository;

    // Dependency Injection of repositories
    public function __construct(OrderRepositoryInterface $orderRepository, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all orders based on user role
     */
    public function getAllOrders($user)
    {
        return $this->orderRepository->index($user);
    }

    /**
     * Store a new order with medicines and quantities in a transaction
     *
     * @throws \Exception if data is incomplete
     */
    public function storeOrder(array $orderData, array $rawData)
    {
        return DB::transaction(function () use ($orderData, $rawData) {

            if (empty($rawData['medicines']) || empty($rawData['quantities'])) {
                throw new \Exception('لا يمكن إنشاء الطلب بدون أدوية وكميات.');
            }

            $medicines = [];

            foreach ($rawData['medicines'] as $index => $medicineId) {
                $quantity = $rawData['quantities'][$index] ?? null;

                if (! $medicineId || ! $quantity) {
                    throw new \Exception('بيانات الدواء غير مكتملة.');
                }

                $medicines[] = [
                    'medicine_id' => $medicineId,
                    'quantity' => $quantity,
                ];
            }

            // Create the order itself
            $order = $this->orderRepository->create($orderData);

            // Attach medicines to the order with quantity
            foreach ($medicines as $medicine) {
                $order->medicines()->attach($medicine['medicine_id'], [
                    'quantity' => $medicine['quantity'],
                ]);
            }

            return $order;
        });
    }

    /**
     * Update the status of an order by ID
     */
    public function updateStatus($orderId, $status)
    {
        return $this->orderRepository->updateStatus($orderId, $status);
    }

    /**
     * Get order details by ID
     */
    public function getOrderDetails($id)
    {
        return $this->orderRepository->find($id);
    }

    /**
     * Reject a medicine in the order with a note
     */
    public function rejectMedicineInOrder(Order $order, Medicine $medicine, $note)
    {
        return $this->orderRepository->rejectMedicine($order, $medicine, $note);
    }

    /**
     * Update medicine quantity and status in an order
     */
    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity)
    {
        $this->orderRepository->updateMedicineQuantity($order, $medicine, $quantity);
    }
}
