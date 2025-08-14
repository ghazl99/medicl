<?php

namespace Modules\Pharmacist\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Services\MedicineService;
use Modules\User\Models\User;

class PharmacistController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
        protected MedicineService $medicineService,
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('pharmacist::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('pharmacist::edit');
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
