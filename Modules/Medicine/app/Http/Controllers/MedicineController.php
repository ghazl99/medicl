<?php

namespace Modules\Medicine\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $user = Auth::user();
        $medicines = $this->medicineService->getAllMedicines($user);

        return view('medicine::admin.index', [
            'medicines' => $medicines,
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
        $user = Auth::user();
        $this->medicineService->createMedicine($validatedData, $user);

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
