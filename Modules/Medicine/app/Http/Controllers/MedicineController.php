<?php

namespace Modules\Medicine\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\MedicineImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Http\Requests\MedicineImportRequest;
use Modules\Medicine\Http\Requests\medicineRequest;
use Modules\Medicine\Services\MedicineService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MedicineController extends Controller
{
    public function __construct(
        protected MedicineService $medicineService,
        protected CategoryService $categoryService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the medicines with filtering capabilities.
     *
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search', null);
        $medicines = $this->medicineService->getAllMedicines($keyword);

        $supplierMedicineIds = [];
        if (Auth::user()->hasRole('مورد')) {
            $supplierMedicineIds = Auth::user()->medicines->pluck('id')->toArray();
        }

        // Handle AJAX request
        if ($request->ajax()) {
            $viewName = Auth::user()->hasRole('مورد')
                ? 'medicine::admin._medicines_supplier_table_rows' // New partial for supplier view
                : 'medicine::admin._medicines_admin_table_rows';   // New partial for admin view

            return response()->json([
                'html' => view($viewName, compact('medicines', 'supplierMedicineIds'))->render(),
                'pagination' => (string) $medicines->links()
            ]);
        }

        // Handle regular request (initial page load)
        return view('medicine::admin.index', compact('medicines', 'supplierMedicineIds'));
    }

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function getMedicinesBySupplier(Request $request)
    {
        $keyword = $request->input('search', null);
        $user = Auth::user();
        $medicines = $this->medicineService->getAllMedicinesSupplier($keyword, $user);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('medicine::admin._myMedicine_supplier_table_rows', ['medicines' => $medicines])->render(),
                'pagination' => (string) $medicines->links()
            ]);
        }
        return view('medicine::admin.medicineSupplier', [
            'medicines' => $medicines,
        ]); // Corrected line
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryService->getAllcategories();

        return view('medicine::admin.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(medicineRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $user = Auth::user();
            $this->medicineService->createMedicine($validatedData, $user);

            return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function import(MedicineImportRequest $request)
    {
        $validatedData = $request->validated();

        Excel::queueImport(new MedicineImport($validatedData), $validatedData['file']);

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
    }

    public function storeCheckedMedicine(Request $request)
    {
        $request->validate([
            'medicines' => 'required|array',
        ]);
        $supplier_id = Auth::user()->id;
        $this->medicineService->assignMedicinesToSupplier($request->medicines, $supplier_id);

        return redirect()->back()->with('success', 'تم ربط الأدوية بالمورد بنجاح');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('medicine::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (! $medicine) {
            abort(404, 'الدواء غير موجود.');
        }

        return view('medicine::admin.edit', compact('medicine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (! $medicine) {
            abort(404, 'الدواء غير موجود.');
        }

        // هنا يمكنك استخدام Form Request لـ validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'quantity_available' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|decimal:0,2',
        ]);

        $this->medicineService->updateMedicine($medicine, $validatedData);

        return redirect()->route('medicines.index')->with('success', 'تم تحديث الدواء بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    /**
     * Toggle medicine availability for the current supplier.
     *
     * @param  int  $medicineId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleAvailability($medicineId)
    {
        try {
            // Get current supplier ID
            $supplierId = Auth::id();

            // Toggle availability via MedicineService
            $this->medicineService->toggleAvailability($medicineId, $supplierId);

            return back()->with('success', 'تم تغيير حالة التوفر بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
