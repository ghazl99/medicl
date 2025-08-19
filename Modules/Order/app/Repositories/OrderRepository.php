<?php

namespace Modules\Order\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function index($user)
    {
        if ($user->hasRole('المشرف')) {
            return Order::with(['pharmacist', 'supplier'])->latest()->paginate(10);
        } elseif ($user->hasRole('صيدلي')) {
            return Order::with('supplier')
                ->where('pharmacist_id', $user->id)->latest()
                ->paginate(10);
        } elseif ($user->hasRole('مورد')) {
            return Order::with('pharmacist')
                ->where('supplier_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return collect();
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function find($id): Order
    {
        return Order::findOrFail($id);
    }

    public function updateStatus($orderId, $status): Order
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();
        return $order;
    }

    public function rejectMedicine(Order $order, Medicine $medicine, $note): Order
    {
        $order->medicines()->updateExistingPivot($medicine->id, [
            'status' => 'مرفوض',
            'note' => $note
        ]);

        if ($order->status === 'قيد الانتظار') {
            $order->status = 'مرفوض جزئياً';
            $order->save();
        }

        return $order;
    }

    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity): void
    {
        $order->medicines()->updateExistingPivot($medicine->id, [
            'quantity' => $quantity,
            'status' => 'مقبول',
        ]);
    }
}
