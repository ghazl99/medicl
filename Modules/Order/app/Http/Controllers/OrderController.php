<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Models\Order;
use Modules\Cart\Models\CartItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Medicine\Models\Medicine;
use Modules\User\Services\UserService;
use Modules\Order\Services\OrderService;
use Modules\Order\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    // Inject services via constructor property promotion (PHP 8+)
    public function __construct(
        protected OrderService $orderService,
        protected UserService $userService
    ) {}

    /**
     * Display a listing of orders according to user role.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $this->orderService->getAllOrders($user);

        return view('order::admin.index', compact('orders'));
    }

    /**
     * Fetch medicines by supplier (AJAX).
     */
    public function getMedicinesBySupplier($id)
    {
        try {
            $medicines = $this->userService->getMedicinesBySupplier($id);

            return response()->json($medicines);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Show form to create a new order.
     */
    public function create()
    {
        $suppliers = $this->userService->getSuppliers();

        return view('order::admin.create', compact('suppliers'));
    }

    /**
     * Store a new order with validated data.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $supplierId = $request->input('supplier_id'); // جاي من الفورم

        // جلب عناصر السلة الخاصة بهالمورد فقط
        $cartItems = CartItem::with(['medicine', 'supplier'])
            ->whereHas('cart', fn($q) => $q->where('user_id', $user->id))
            ->where('supplier_id', $supplierId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'السلة فارغة لهذا المورد.');
        }

        $lastOrder = null;

        DB::transaction(function () use ($cartItems, $user, $supplierId, &$lastOrder) {
            $orderData = [
                'pharmacist_id' => $user->id,
                'supplier_id' => $supplierId,
                'status' => 'قيد الانتظار',
            ];

            $rawData = [
                'medicines' => $cartItems->pluck('medicine_id')->toArray(),
                'quantities' => $cartItems->pluck('quantity')->toArray(),
                'notes' => $cartItems->pluck('note')->toArray(),
            ];

            $lastOrder = $this->orderService->storeOrder($orderData, $rawData);
            // حذف العناصر من السلة
            CartItem::whereIn('id', $cartItems->pluck('id'))->delete();
        });

        return redirect()->route('details.order', ['id' => $lastOrder->id]);

    }


    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        try {
            $this->orderService->updateStatus($id, $request->status);

            return redirect()->back()->with('success', 'تم تحديث الحالة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Reject a specific medicine in an order with a note.
     */
    public function rejectMedicine(Request $request, Order $order, Medicine $medicine)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $this->orderService->rejectMedicineInOrder($order, $medicine, $validated['rejection_reason']);

        return redirect()->back()->with('success', 'تم رفض الدواء مع حفظ السبب');
    }

    /**
     * Update quantity of a specific medicine in the order.
     */
    public function updateMedicineQuantity(Request $request, Order $order, Medicine $medicine)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        if ($order->status !== 'مرفوض جزئياً') {
            return redirect()->back()->with('error', 'غير مسموح بتحديث الكمية في هذه الحالة');
        }

        $pivot = $order->medicines()->where('medicine_id', $medicine->id)->first()->pivot;

        if ($pivot->status !== 'مرفوض') {
            return redirect()->back()->with('error', 'لا يمكن تعديل كمية دواء غير مرفوض');
        }

        $this->orderService->updateMedicineQuantity($order, $medicine, $request->quantity);

        return redirect()->back()->with('success', 'تم تحديث الكمية بنجاح');
    }

    /**
     * Show order details.
     */
    public function show($id)
    {
        $order = $this->orderService->getOrderDetails($id);

        return view('order::admin.show', compact('order'));
    }

    /**
     * Show form for editing an order (optional implementation).
     */
    public function edit($id)
    {
        return view('order::edit');
    }

    // You can implement update and destroy methods if needed
    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
