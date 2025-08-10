<?php

namespace Modules\Offer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Medicine\Services\MedicineService;
use Modules\Offer\Http\Requests\OfferRequest;
use Modules\Offer\Models\Offer;
use Modules\Offer\Services\OfferService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OfferController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:المشرف|مورد|صيدلي', only: ['index', 'showImage', 'show']),

            new Middleware('role:مورد', only: ['create', 'store']),
        ];
    }

    public function __construct(
        protected OfferService $offerService,
        protected MedicineService $medicineService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $offers = $this->offerService->getAll($user);

        return view('offer::admin.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('offer::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $this->offerService->store($data);

            return redirect()->route('offers.index')->with('success', 'تم إنشاء العرض بنجاح');
        } catch (\Exception $e) {
            // Return to the previous page with an error message
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Offer $offer)
    {
        $offer = $this->offerService->details($offer);

        if (! $offer) {
            abort(404, 'العرض غير موجود');
        }

        return view('offer::admin.show', compact('offer'));
    }

    public function showImage(Media $media)
    {
        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
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
