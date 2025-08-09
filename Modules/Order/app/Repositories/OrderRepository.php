<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function index($user)
    {
        if ($user->hasRole('المشرف')) {
            $orders = Order::with(['pharmacist', 'supplier'])->paginate(10);
        } elseif ($user->hasRole('صيدلي')) {
            $orders = Order::with('supplier')
                ->where('pharmacist_id', $user->id)
                ->paginate(10);
        } elseif ($user->hasRole('مورد')) {
            $orders = Order::with('pharmacist')
                ->where('supplier_id', $user->id)
                ->paginate(10);
        } else {
            $orders = collect();
        }

        return $orders;
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function find($id): Order
    {
        return Order::findOrFail($id);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        return $order;
    }

    public function rejectMedicine($orderId, $medicineId)
    {
        $order = Order::findOrFail($orderId);
        $order->medicines()->updateExistingPivot($medicineId, ['status' => 'مرفوض']);

        return $order;
    }
}
