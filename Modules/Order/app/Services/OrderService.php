<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Modules\Order\Repositories\OrderRepositoryInterface;
use Modules\User\Repositories\UserRepositoryInterface;

class OrderService
{
    protected $orderRepository;

    protected $userRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, UserRepositoryInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllOrders($user)
    {
        return $this->orderRepository->index($user);
    }

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

            $order = $this->orderRepository->create($orderData);

            foreach ($medicines as $medicine) {
                $order->medicines()->attach($medicine['medicine_id'], [
                    'quantity' => $medicine['quantity'],
                ]);
            }

            return $order;
        });
    }
}
