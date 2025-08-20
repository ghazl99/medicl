<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Order\Models\Order;
use Modules\Order\Repositories\OrderRepositoryInterface;
use Modules\User\Repositories\UserRepositoryInterface;

class OrderService
{
    use \Modules\Core\Traits\FirebaseNotificationTrait;

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

    public function storeOrder(array $orderData, array $rawData): Order
    {
        return DB::transaction(function () use ($orderData, $rawData) {

            if (empty($rawData['medicines']) || empty($rawData['quantities'])) {
                throw new \Exception('لا يمكن إنشاء الطلب بدون أدوية وكميات.');
            }

            $medicines = [];
            foreach ($rawData['medicines'] as $index => $medicineId) {
                $quantity = $rawData['quantities'][$index] ?? null;

                if (!$medicineId || !$quantity) {
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

            // إشعار المورد
            $recipient = $order->supplier;
            if ($recipient && $recipient->fcm_token) {
                $this->sendOrderNotification(
                    $recipient->fcm_token,
                    'طلب جديد',
                    'تم إنشاء طلب جديد برقم #' . $order->id,
                    $order->id,
                    $recipient->id
                );
            }

            return $order;
        });
    }

    public function updateStatus($orderId, $status): Order
    {
        $order = DB::transaction(function () use ($orderId, $status) {
            return $this->orderRepository->updateStatus($orderId, $status);
        });

        $user = Auth::user();
        $recipient = null;
        $title = '';
        $body = '';

        if ($user->hasRole('مورد') && $status == 'تم التأكيد') {
            $recipient = $order->pharmacist;
            $title = 'تم تأكيد طلبك رقم #' . $order->id;
            $body = 'المورد أكد طلبك.';
        } elseif ($user->hasRole('صيدلي')) {
            $recipient = $order->supplier;
            if ($status == 'تم التأكيد') {
                $title = 'تم تأكيد الطلب رقم #' . $order->id;
                $body = 'الصيدلاني أكد الطلب.';
            } elseif ($status == 'ملغي') {
                $title = 'تم إلغاء الطلب رقم #' . $order->id;
                $body = 'الصيدلاني ألغى الطلب.';
            }
        }

        if ($recipient && $recipient->fcm_token) {
            $this->sendOrderNotification(
                $recipient->fcm_token,
                $title,
                $body,
                $order->id,
                $recipient->id
            );
        }

        return $order;
    }

    public function getOrderDetails($id)
    {
        return $this->orderRepository->find($id);
    }

    public function rejectMedicineInOrder(Order $order, Medicine $medicine, $note)
    {
        $order = DB::transaction(function () use ($order, $medicine, $note) {
            return $this->orderRepository->rejectMedicine($order, $medicine, $note);
        });

        if ($order->status === 'مرفوض جزئياً') {
            $recipient = $order->pharmacist;
            $this->sendOrderNotification(
                $recipient->fcm_token,
                'تم رفض طلبك جزئياً رقم #' . $order->id,
                'تم رفض أحد الأدوية في طلبك. يرجى مراجعة الطلب لمزيد من التفاصيل.',
                $order->id,
                $recipient->id
            );
        }

        return $order;
    }

    public function updateMedicineQuantity(Order $order, Medicine $medicine, int $quantity)
    {
        $this->orderRepository->updateMedicineQuantity($order, $medicine, $quantity);
    }

    private function sendOrderNotification($fcmToken, $title, $body, $orderId, $recipientId)
    {
        $url = route('details.order', ['id' => $orderId]);
        $this->sendFirebaseNotification(
            $title,
            $body,
            $fcmToken,
            [
                'order_id' => $orderId,
                'title' => $title,
                'body' => $body,
                'url' => $url,
                'icon' => asset('assets/img/capsule.png'),
            ],
            $recipientId,
            $url
        );
    }
}
