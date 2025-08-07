<?php

namespace Modules\Offer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Medicine\Models\Medicine;
use Modules\Medicine\Models\MedicineUser;
use Modules\Medicine\Services\MedicineService;
use Modules\Offer\Http\Requests\OfferRequest;
use Modules\Offer\Services\OfferService;

class OfferController extends Controller
{
    public function __construct(
        protected OfferService $offerService,
        protected MedicineService $medicineService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = $this->offerService->getAll();

        return view('offer::admin.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOffer(Medicine $medicine)
    {
        $user = Auth::user();
        $medicineUser = MedicineUser::firstWhere([
            'medicine_id' => $medicine->id,
            'user_id' => $user->id,
        ]);

        return view('offer::admin.create', compact('medicineUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request)
    {
        try {
            $this->offerService->store($request->validated());

            return redirect()->route('offers.index')->with('success', 'تم إنشاء العرض بنجاح');
        } catch (\Exception $e) {
            // Return to the previous page with an error message
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('offer::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('offer::edit');
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
