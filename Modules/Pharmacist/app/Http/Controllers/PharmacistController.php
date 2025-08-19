<?php

namespace Modules\Pharmacist\Http\Controllers;

use Illuminate\Http\Request;
use Modules\User\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Services\OrderService;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Services\MedicineService;
use Modules\Core\Models\Notification;

class PharmacistController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected MedicineService $medicineService,
        protected OrderService $orderService,
    ) {}

    /**
     * Display the pharmacist home page.
     */
    public function home(Request $request)
    {
        $keyword = $request->input('search', null);

        // جلب الأدوية المطابقة أو لا شيء إذا لم يكن هناك كلمة
        $medicines = $this->medicineService->getAllMedicines($keyword);
        return view('pharmacist::admin.home', compact('medicines', 'keyword'));
    }

    public function getMainCategories()
    {
        $categories = $this->categoryService->getAllcategories();

        return view('pharmacist::admin.categories', compact('categories'));
    }

    public function getSubCategories($id)
    {
        $data = $this->categoryService->getSubcategoryWithMedicines($id);

        return view('pharmacist::admin.subCategories', [
            'category' => $data['subcategory'],
        ]);
    }

    public function medicinesBySubCategory($id)
    {
        $data = $this->categoryService->getSubcategoryWithMedicines($id);

        return view('pharmacist::admin.medicinesBySubCategory', [
            'subcategory' => $data['subcategory'],
            'medicines' => $data['medicines']
        ]);
    }

    public function NewMedicines()
    {
        $medicines = $this->medicineService->getNewMedicines();
        return view('pharmacist::admin.newMedicines', compact('medicines'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pharmacist::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmacist::create');
    }
    public function myOrders()
    {
        $user = Auth::user();
        $orders = $this->orderService->getAllOrders($user);
        return view('pharmacist::admin.myOrders', compact('orders'));
    }

    public function detailsOrders($id)
    {
        $order = $this->orderService->getOrderDetails($id);
        return view('pharmacist::admin.details-order', compact('order'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->latest()->take(10)->get();
        return view('pharmacist::admin.notification',compact('notifications'));
    }
}
