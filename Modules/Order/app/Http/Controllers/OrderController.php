<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Medicine\Models\Medicine;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use Modules\User\Services\UserService;

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
    public function store(OrderRequest $request)
    {
        $validated = $request->validated();

        $orderData = [
            'pharmacist_id' => $request->user()->id,
            'supplier_id' => $validated['supplier_id'],
            'status' => 'قيد الانتظار',
        ];

        $order = $this->orderService->storeOrder($orderData, $validated);

        return redirect()->route('orders.index')->with('success', 'تم إضافة الطلب بنجاح.');
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
            return redirect()->back()->with('error', 'حدث خطأ: '.$e->getMessage());
        }
    }

    /**
     * Reject a specific medicine in an order with a note.
     */
    public function rejectMedicine(Request $request, Order $order, Medicine $medicine)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        $this->orderService->rejectMedicineInOrder($order, $medicine, $validated['note']);

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
