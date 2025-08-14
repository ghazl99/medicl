<?php

namespace Modules\Order\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    use \Modules\Core\Traits\FirebaseNotificationTrait;

    /**
     * Get orders list based on user role with pagination.
     */
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

    /**
     * Create a new order.
     */
    public function create(array $data): Order
    {
        $order = Order::create($data);
        $recipient = $order->supplier;
        if ($recipient && $recipient->fcm_token) {
            $title = 'طلب جديد';
            $body = 'تم إنشاء طلب جديد برقم #'.$order->id;
            $url = route('orders.show', ['order' => $order->id]);
            $this->sendFirebaseNotification(
                $title,
                $body,
                $recipient->fcm_token,
                [
                    'order_id' => $order->id,
                    'title' => $title,
                    'body' => $body,
                    'url' => $url,
                    'icon' => asset('assets/img/capsule.png'),
                ],
                $recipient->id,
                $url
            );
        }

        return $order;
    }

    /**
     * Find order by ID or fail.
     */
    public function find($id): Order
    {
        return Order::findOrFail($id);
    }

    /**
     * Update order status and send notification accordingly.
     */
    public function updateStatus($orderId, $status)
    {
        return DB::transaction(function () use ($orderId, $status) {
            $order = Order::findOrFail($orderId);
            $order->status = $status;
            $order->save();

            $user = Auth::user();

            $notificationData = null;
            $recipient = null;

            if ($user->hasRole('مورد') && $status == 'تم التأكيد') {
                $recipient = $order->pharmacist;
                if ($recipient && $recipient->fcm_token) {
                    $notificationData = [
                        'title' => 'تم تأكيد طلبك رقم #'.$order->id,
                        'body' => 'المورد أكد طلبك.',
                    ];
                }
            } elseif ($user->hasRole('صيدلي')) {
                $recipient = $order->supplier;

                if ($recipient && $recipient->fcm_token) {
                    if ($status == 'تم التأكيد') {
                        $notificationData = [
                            'title' => 'تم تأكيد الطلب رقم #'.$order->id,
                            'body' => 'الصيدلاني أكد الطلب.',
                        ];
                    } elseif ($status == 'ملغي') {
                        $notificationData = [
                            'title' => 'تم إلغاء الطلب رقم #'.$order->id,
                            'body' => 'الصيدلاني ألغى الطلب.',
                        ];
                    }
                }
            }

            if ($notificationData && $recipient) {
                $url = route('orders.show', ['order' => $order->id]);

                $this->sendFirebaseNotification(
                    $notificationData['title'],
                    $notificationData['body'],
                    $recipient->fcm_token,
                    [
                        'order_id' => $order->id,
                        'title' => $notificationData['title'],
                        'body' => $notificationData['body'],
                        'url' => $url,
                        'icon' => asset('assets/img/capsule.png'),
                    ],
                    $recipient->id,
                    $url
                );
            }

            return $order;
        });
    }

    /**
     * Reject specific medicine in an order with a note.
     * Change order status to partial reject if currently waiting.
     */
    public function rejectMedicine(Order $order, Medicine $medicine, $note)
    {
        return DB::transaction(function () use ($order, $medicine, $note) {
            // Update medicine pivot with rejection status and note
            $order->medicines()->updateExistingPivot($medicine->id, ['status' => 'مرفوض', 'note' => $note]);

            // Change order status if still pending
            if ($order->status == 'قيد الانتظار') {
                $order->status = 'مرفوض جزئياً';
                $order->save();

                // Send notification to pharmacist
                $pharmacist = $order->pharmacist;
                $title = 'تم رفض طلبك جزئياً رقم #'.$order->id;
                $body = 'تم رفض أحد الأدوية في طلبك. يرجى مراجعة الطلب لمزيد من التفاصيل.';
                $url = route('orders.show', ['order' => $order->id]);

                $this->sendFirebaseNotification(
                    $title,
                    $body,
                    $pharmacist->fcm_token,
                    [
                        'order_id' => $order->id,
                        'title' => $title,
                        'body' => $body,
                        'url' => $url,
                        'icon' => asset('assets/img/capsule.png'),
                    ],
                    $pharmacist->id,
                    $url
                );
            }

            return $order;
        });
    }

    /**
     * Update quantity and set medicine status to accepted.
     */
    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity)
    {
        // Single DB operation, no transaction needed here
        $order->medicines()->updateExistingPivot($medicine->id, [
            'quantity' => $quantity,
            'status' => 'مقبول',
        ]);
    }
}
