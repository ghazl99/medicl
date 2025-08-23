<?php

namespace Modules\Cart\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Cart\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function detailsItemsBySupplier($supplier_id)
    {
        $userId = Auth::id();
        $cartItems = $this->cartService->getUserCartItems($userId);

        return view('cart::index', compact('cartItems', 'supplier_id'));
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = $this->cartService->update($id, $request->quantity);

        return response()->json([
            'success' => true,
            'item_total' => number_format($cartItem->medicine->net_dollar_new * $cartItem->quantity, 2, '.', ''),
        ]);
    }

    public function deleteItem($cartItemId)
    {
        $cartCount = $this->cartService->deleteItem($cartItemId);

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cart::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'supplier_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cartService->addToCart(
                Auth::user()->id,
                $request->medicine_id,
                $request->supplier_id,
                $request->quantity,

            );

            // تحقق إذا كان الطلب AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'تمت إضافة المنتج إلى السلة بنجاح']);
            }

            return redirect()->route('pharmacist.home')
                ->with('success', 'تمت إضافة المنتج إلى السلة بنجاح');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
            }

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج إلى السلة: ' . $e->getMessage());
        }
    }



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('cart::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('cart::edit');
    }

    /**
     * Update the specified resource in storage.
     */

    public function updateQuantityOrNote(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        // Construct the data array based on the request
        $data = $request->only(['quantity', 'note']);
        
        // Pass the data array to the service
        $cartItem = $this->cartService->update($id, $data);

        // ... rest of your code to return a JSON response
        return response()->json([
            'success' => true,
            'quantity' => $cartItem->quantity,
            'note' => $cartItem->note,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
