<?php

namespace Modules\Medicine\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MedicineImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Medicine\Models\Medicine;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Services\MedicineService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\Medicine\Http\Requests\medicineRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\Medicine\Http\Requests\MedicineImportRequest;

class MedicineController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:المشرف|مورد|صيدلي', only: ['index', 'showImage','newMedicines']),
            new Middleware('role:المشرف|مورد', only: ['create', 'store', 'edit', 'update', 'destroy',
            'import','storeCheckedMedicine','toggleAvailability','updateNote']),
            new Middleware('role:المشرف', only: ['toggleNewStatus'])
        ];
    }

    public function __construct(
        protected MedicineService $medicineService,
        protected CategoryService $categoryService,
    ) {}

    // List medicines
    public function index(Request $request)
    {
        $keyword = $request->input('search', null);
        $medicines = $this->medicineService->getAllMedicines($keyword);

        $supplierMedicineIds = [];
        if (Auth::user()->hasRole('\u0645\u0648\u0631\u062f')) {
            $supplierMedicineIds = Auth::user()->medicines->pluck('id')->toArray();
        }

        // Handle AJAX request
        if ($request->ajax()) {
            $viewName = Auth::user()->hasRole('\u0645\u0648\u0631\u062f')
                ? 'medicine::admin._medicines_supplier_table_rows'
                : 'medicine::admin._medicines_admin_table_rows';

            return response()->json([
                'html' => view($viewName, compact('medicines', 'supplierMedicineIds'))->render(),
                'pagination' => (string) $medicines->links(),
            ]);
        }

        return view('medicine::admin.index', compact('medicines', 'supplierMedicineIds'));
    }

    // Display medicine image
    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    // Show supplier's medicines
    public function getMedicinesBySupplier(Request $request)
    {
        $keyword = $request->input('search', null);
        $user = Auth::user();
        $medicines = $this->medicineService->getAllMedicinesSupplier($keyword, $user);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('medicine::admin._myMedicine_supplier_table_rows', ['medicines' => $medicines])->render(),
                'pagination' => (string) $medicines->links(),
            ]);
        }

        return view('medicine::admin.medicineSupplier', ['medicines' => $medicines]);
    }

    // Show create form
    public function create()
    {
        $categories = $this->categoryService->getAllcategories();

        return view('medicine::admin.create', compact('categories'));
    }

    // Store new medicine
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

    // Import medicines from Excel
    public function import(MedicineImportRequest $request)
    {
        $validatedData = $request->validated();
        Excel::queueImport(new MedicineImport($validatedData), $validatedData['file']);

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
    }

    // Assign medicines to supplier
    public function storeCheckedMedicine(Request $request)
    {
        $request->validate(['medicines' => 'required|array']);

        $supplier_id = Auth::user()->id;
        $this->medicineService->assignMedicinesToSupplier($request->medicines, $supplier_id);

        return redirect()->back()->with('success', 'تم ربط الأدوية بالمورد بنجاح');
    }

    // Show single medicine
    public function show($id)
    {
        return view('medicine::show');
    }

    // Show edit form
    public function edit($id)
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (! $medicine) {
            abort(404, 'الدواء غير موجود.');
        }

        return view('medicine::admin.edit', compact('medicine'));
    }

    // Update medicine data
    public function update(Request $request, $id)
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (! $medicine) {
            abort(404, 'الدواء غير موجود.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'quantity_available' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|decimal:0,2',
        ]);

        $this->medicineService->updateMedicine($medicine, $validatedData);

        return redirect()->route('medicines.index')->with('success', 'تم تحديث الدواء بنجاح.');
    }

    // Delete medicine (not implemented)
    public function destroy($id) {}

    // Toggle availability for current supplier
    public function toggleAvailability($medicineId)
    {
        try {
            $supplierId = Auth::id();
            $this->medicineService->toggleAvailability($medicineId, $supplierId);

            return back()->with('success', 'تم تغيير حالة التوفر بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Update pivot note for supplier-medicine
    public function updateNote(Request $request, $pivotId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $notes = $request->input('notes') ?? '';
        $updated = $this->medicineService->updateNoteOnPivot($pivotId, $notes);

        if ($updated) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 500);
    }

    /**
     * Toggle the 'new' status with start and end dates.
     */
    public function toggleNewStatus(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'is_new' => 'required|boolean',
            'new_start_date' => 'required|date',
            'new_end_date' => 'required|date|after_or_equal:new_start_date',
        ], [
            'new_end_date.after_or_equal' => 'The new end date must be after or equal to the start date.',
        ]);

        $updatedMedicine = $this->medicineService->updateNewStatus(
            $medicine,
            $validated['is_new'],
            $validated['new_start_date'],
            $validated['new_end_date']
        );

        return response()->json(['success' => true, 'medicine' => $updatedMedicine]);
    }

    // Show all "new" medicines
    public function newMedicines()
    {
        $medicines = $this->medicineService->getNewMedicines();

        return view('medicine::admin.newMedicines', compact('medicines'));
    }
}
