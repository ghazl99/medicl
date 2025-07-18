<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Http\Requests\orderRequest;
use Modules\Order\Services\OrderService;
use Modules\User\Services\UserService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $this->orderService->getAllOrders($user);

        return view('order::admin\index', compact('orders'));
    }

    public function getMedicinesBySupplier($id)
    {
        try {
            $medicines = $this->userService->getMedicinesBySupplier($id);

            return response()->json($medicines);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function create()
    {
        $suppliers = $this->userService->getSuppliers();

        return view('order::admin\create', compact('suppliers'));
    }

    public function store(orderRequest $request)
    {
        $validated = $request->validated();

        $orderData = [
            'pharmacist_id' => $request->user()->id,
            'supplier_id' => $validated['supplier_id'],
            'status' => 'pending',
        ];

        $order = $this->orderService->storeOrder($orderData, $validated);

        return redirect()->route('orders.index')->with('success', 'تم إضافة الطلب بنجاح.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('order::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
