<?php

namespace Modules\Pharmacist\Http\Controllers;

use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\Offer\Models\Offer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Models\Notification;
use Modules\Medicine\Models\Medicine;
use Modules\Cart\Services\CartService;
use Modules\Core\Services\CityService;
use Modules\User\Services\UserService;
use Modules\Offer\Services\OfferService;
use Modules\Order\Services\OrderService;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Services\MedicineService;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Http\Requests\UpdateUserRequest;

class PharmacistController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected MedicineService $medicineService,
        protected OrderService $orderService,
        protected CartService $cartService,
        protected CityService $cityService,
        protected UserService $userService,
        protected OfferService $offerService,

    ) {}

    /**
     * Display the pharmacist home page.
     */
    public function home(Request $request)
    {
        $keyword = $request->input('search', null);

        // إذا لم يُدخل المستخدم كلمة بحث، أرسل صفحة فارغة بدون أدوية
        if (!$keyword) {
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), // مجموعة فارغة
                0,         // عدد العناصر الكلي
                10,        // عناصر لكل صفحة
                1,         // الصفحة الحالية
                ['path' => $request->url()]
            );

            if ($request->ajax()) {
                return view('pharmacist::admin.medicines_list', [
                    'medicines' => $emptyPaginator,
                    'keyword' => $keyword
                ])->render();
            }

            return view('pharmacist::admin.home', [
                'medicines' => $emptyPaginator,
                'keyword' => $keyword
            ]);
        }

        $query = Medicine::with('suppliers')
            ->where(function ($q) use ($keyword) {
                $q->where('type', 'like', "%$keyword%")
                    ->orWhere('type_ar', 'like', "%$keyword%")
                    ->orWhere('composition', 'like', "%$keyword%")
                    ->orWhere('company', 'like', "%$keyword%");
            });


        $allMedicines = $query->get()->filter(function ($medicine) {
            return $medicine->suppliers->where('pivot.is_available', 1)->count() > 0;
        })->values();

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $allMedicines->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $filteredMedicines = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allMedicines->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('pharmacist::admin.medicines_list', [
                'medicines' => $filteredMedicines,
                'keyword' => $keyword
            ])->render();
        }

        return view('pharmacist::admin.home', [
            'medicines' => $filteredMedicines,
            'keyword' => $keyword
        ]);
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

    public function offers()
    {
        $offers = $this->offerService->getAll();
        return view('pharmacist::admin.offers', compact('offers'));
    }

    public function showOffer(Offer $offer)
    {
        $offer = $this->offerService->details($offer);

        if (! $offer) {
            abort(404, 'العرض غير موجود');
        }

        return view('pharmacist::admin.offer-details', compact('offer'));
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

        $cartItems = $this->cartService->getUserCartItems($user->id);
        $orders = $this->orderService->getAllOrders($user);
        return view('pharmacist::admin.myOrders', compact('orders', 'cartItems'));
    }
    /**
     * Display a listing of delivered orders according to user role.
     */
    public function archive()
    {
        $user = Auth::user();
        $orders = $this->orderService->getArchivedOrders($user);
        return view('pharmacist::admin.archive', compact('orders'));
    }
    public function detailsOrders($id)
    {
        $order = $this->orderService->getOrderDetails($id);
        return view('pharmacist::admin.details-order', compact('order'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::user()->id)->latest()->take(10)->get();
        return view('pharmacist::admin.notification', compact('notifications'));
    }

    public function profile()
    {
        return view('pharmacist::admin.profile');
    }
    public function editProfile()
    {
        $user = Auth::user();
        $cities = $this->cityService->getAllCitiesWithSubCities();

        return view('pharmacist::admin.edit-profile', compact('user', 'cities'));
    }

    public function update_profile(UpdateUserRequest $request)
    {
        $user = Auth::user();

        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('edit.profile.user')->with('success', 'تم تحديث بياناتك بنجاح');
    }
}
