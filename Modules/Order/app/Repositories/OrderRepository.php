<?php

namespace Modules\Order\Repositories;

use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    // Get orders list based on user role with pagination
    public function index($user)
    {
        if ($user->hasRole('المشرف')) {
            return Order::with(['pharmacist', 'supplier'])->paginate(10);
        } elseif ($user->hasRole('صيدلي')) {
            return Order::with('supplier')
                ->where('pharmacist_id', $user->id)
                ->paginate(10);
        } elseif ($user->hasRole('مورد')) {
            return Order::with('pharmacist')
                ->where('supplier_id', $user->id)
                ->paginate(10);
        }

        return collect();
    }

    // Create a new order
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    // Find order by id or fail
    public function find($id): Order
    {
        return Order::findOrFail($id);
    }

    // Update order status
    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();

        return $order;
    }

    // Reject specific medicine in an order with a note
    // Also change order status to partial reject if currently waiting
    public function rejectMedicine(Order $order, Medicine $medicine, $note)
    {
        $order->medicines()->updateExistingPivot($medicine->id, ['status' => 'مرفوض', 'note' => $note]);

        if ($order->status == 'قيد الانتظار') {
            $order->status = 'مرفوض جزئياً';
            $order->save();
        }

        return $order;
    }

    // Update quantity and set medicine status to accepted
    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity)
    {
        $order->medicines()->updateExistingPivot($medicine->id, [
            'quantity' => $quantity,
            'status' => 'مقبول',
        ]);
    }
}
