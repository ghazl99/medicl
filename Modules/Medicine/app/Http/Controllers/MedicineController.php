<?php

namespace Modules\Medicine\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\MedicineImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Category\Services\CategoryService;
use Modules\Medicine\Http\Requests\MedicineImportRequest;
use Modules\Medicine\Http\Requests\MedicineRequest;
use Modules\Medicine\Models\Medicine;
use Modules\Medicine\Services\MedicineService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MedicineController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:المشرف|مورد|صيدلي', only: ['index', 'showImage', 'newMedicines']),
            new Middleware('role:المشرف|مورد', only: [
                'create',
                'store',
                'edit',
                'update',
                'destroy',
                'import',
                'storeCheckedMedicine',
                'toggleAvailability',
                'updateNote',
            ]),
            new Middleware('role:المشرف', only: ['toggleNewStatus']),
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
        if (Auth::user()->hasRole('مورد')) {
            $supplierMedicineIds = Auth::user()->medicines->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }
        if ($request->ajax()) {
            $viewName = Auth::user()->hasRole('مورد')
                ? 'medicine::admin._medicines_supplier_table_rows'
                : 'medicine::admin._medicines_admin_table_rows';

            return response()->json([
                'html' => view($viewName, compact('medicines', 'supplierMedicineIds'))->render(),
                'pagination' => (string) $medicines->links(),
                'supplierMedicineIds' => $supplierMedicineIds,
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
            try {
                return response()->json([
                    'html' => view('medicine::admin._myMedicine_supplier_table_rows', ['medicines' => $medicines])->render(),
                    'pagination' => (string) $medicines->links(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage(),
                ], 500);
            }
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
    public function store(MedicineRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['net_syp'] = $validatedData['net_dollar_new'] ?? null;
            $validatedData['public_syp'] = $validatedData['public_dollar_new'] ?? null;
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
        // Excel::queueImport(new MedicineImport($validatedData), $validatedData['file']);//with queue
        Excel::Import(new MedicineImport($validatedData), $validatedData['file']);

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
    }

    // Assign medicines to supplier
    public function storeCheckedMedicine(Request $request)
    {
        $request->validate(['all_selected_medicines' => 'required|string']);

        $supplier_id = Auth::user()->id;

        $medicineIds = explode(',', $request->input('all_selected_medicines'));

        $this->medicineService->assignMedicinesToSupplier($medicineIds, $supplier_id);

        return response()->json([
            'success' => true,
            'message' => 'تم ربط الأدوية بالمورد بنجاح'
        ]);
    }

    // Show single medicine
    public function show($id)
    {
        $medicine = $this->medicineService->getMedicineWithAvailableSuppliers($id);

        if (!$medicine) {
            abort(404, 'الدواء غير موجود أو لا يوجد موردين متاحين.');
        }

        return view('medicine::admin.show', compact('medicine'));
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
        try {
            $medicine = $this->medicineService->getMedicineById($id);

            if (! $medicine) {
                abort(404, 'الدواء غير موجود.');
            }

            $data = $request->only('type_ar', 'price');
            $this->medicineService->updateMedicine($medicine, $data);

            return redirect()->route('medicines.index')->with('success', 'تم تحديث الدواء بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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

    // Update pivot note or price for supplier-medicine
    public function updatePivot(Request $request, $pivotId)
    {
        try {
            $request->validate([
                'notes' => 'nullable|string|max:1000',
                'price' => 'nullable|numeric',
                'offer_qty' => 'nullable|numeric',
                'offer_free_qty' => 'nullable|numeric',
            ]);

            $data = $request->only(['notes', 'price', 'offer_qty', 'offer_free_qty']);
            $updated = $this->medicineService->updatePivotData($pivotId, $data);

            if ($updated) {
                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'error'], 500);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

    // Update the offer via service
    public function updateOffer(Request $request, $id)
    {
        $validated = $request->validate([
            'offer' => 'nullable|string', // Validate offer as percentage
        ]);

        $this->medicineService->updateOffer($id, $validated['offer']);

        return response()->json(['success' => true]);
    }
}
