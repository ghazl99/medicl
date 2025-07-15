<?php

namespace Modules\Medicine\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Medicine\Http\Requests\medicineRequest;
use Modules\Medicine\Services\MedicineService;

class MedicineController extends Controller
{
    public function __construct(
        protected MedicineService $medicineService
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
        // 1. Extract search filters
        $filters = $request->only(['search_term']);
        $searchTerm = $filters['search_term'] ?? null;

        // 2. Fetch medicines based on filters
        // If a search term exists, use the filtered method; otherwise, get all medicines.
        if ($searchTerm) {
            // Assuming getFilteredMedicinesByNamesOrManufacturer is now getFilteredMedicines in service
            $medicines = $this->medicineService->getFilteredMedicinesByNamesOrManufacturer($filters);
        } else {
            $medicines = $this->medicineService->getAllMedicines();
        }

        // 3. Check if the request is AJAX
        if ($request->ajax()) {
            // If AJAX, return HTML for table rows as JSON
            // Ensure this path points to the Blade partial containing only table rows
            $html = view('medicine::admin._medicines_rows', compact('medicines'))->render();

            return response()->json(['html' => $html]);
        }

        // 4. For regular HTTP requests (initial page load), return the full view
        return view('medicine::admin.index', [
            'medicines' => $medicines,
            'filters' => $filters, // Pass filters to retain search input value
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medicine::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(medicineRequest $request)
    {
        $validatedData = $request->validated();

        $this->medicineService->createMedicine($validatedData);

        return redirect()->route('medicines.index')->with('success', 'تم إضافة الدواء بنجاح.');
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
}
