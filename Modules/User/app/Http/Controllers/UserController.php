<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\CityService;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:المشرف', only: [
                'create_pharmacists',
                'pharmacistsList',
                'suppliersList',
                'show',
                'edit',
                'update',
                'destroy',
            ]),
        ];
    }

    public function __construct(
        protected UserService $userService,
        protected CityService $cityService
    ) {}

    /**
     * Display the registration view.
     */
    public function create_pharmacists()
    {
        $cities = $this->cityService->getAllCitiesWithSubCities();

        return view('user::admin.pharmacists.create', compact('cities'));
    }

    public function pharmacistsList(Request $request)
    {
        $keyword = $request->input('search', null);
        $pharmacists = $this->userService->getPharmacists($keyword);

        if ($request->ajax()) {
            $html = view('user::admin.pharmacists._pharmacists_table_rows', compact('pharmacists'))->render();
            // Convert pagination links to a string to send in JSON response
            $pagination = (string) $pharmacists->links();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination, // Send pagination HTML
            ]);
        }

        return view('user::admin.pharmacists.index', compact('pharmacists'));
    }

    public function suppliersList(Request $request)
    {
        $keyword = $request->input('search', null);
        // Assuming getSuppliers method exists in UserService and returns paginated results
        $suppliers = $this->userService->getSuppliers($keyword);

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('user::admin.suppliers._suppliers_table_rows', ['suppliers' => $suppliers])->render(),
                'pagination' => (string) $suppliers->links(), // Convert pagination to string
            ]);
        }

        return view('user::admin.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function create_suppliers()
    {
        $cities = $this->cityService->getAllCitiesWithSubCities();

        return view('user::auth.register', compact('cities'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        try {
            // Validate the incoming request data using the RegisterRequest
            $validatedData = $request->validated();

            // Create a new user using the UserService
            $user = $this->userService->registerUser($validatedData);
            if ($user->hasRole('صيدلي')) {
                session()->flash('success', 'تم إضافة الصيدلي بنجاح! 🧑‍⚕️');

                return redirect()->route('pharmacists.index');
            } elseif ($user->hasRole('مورد')) {

                session()->flash('success', 'تم إضافة المورد بنجاح! 🚚');

                return redirect()->route('login')->with('success', 'تم إنشاء الحساب بنجاح!  🎉');
            }
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
        return view('user::show');
    }

    /**
     * Display the user's profile form.
     */
    public function edit($id)
    {
        $cities = $this->cityService->getAllCitiesWithSubCities();
        $user = $this->userService->getUserById($id);
        if ($user->hasRole('صيدلي')) {
            return view('user::admin.pharmacists.edit', [
                'pharmacist' => $user,
                'cities' => $cities,
            ]);
        } elseif ($user->hasRole('مورد')) {
            return view('user::admin.suppliers.edit', [
                'supplier' => $user,
                'cities' => $cities,
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getUserById($id);

        $this->userService->updateUser($user, $request->validated());
        if ($user->hasRole('صيدلي')) {
            session()->flash('success', 'تم تعديل بيانات الصيدلي بنجاح! 🧑‍⚕️');

            return redirect()->route('pharmacists.index');
        } elseif ($user->hasRole('مورد')) {
            session()->flash('success', 'تم تعديل بيانات المورد بنجاح! 🚚');

            return redirect()->route('suppliers.index');
        }
    }

    public function edit_profile()
    {
        $user = Auth::user();
        $cities = $this->cityService->getAllCitiesWithSubCities();

        return view('user::admin.edit-profile', compact('user', 'cities'));
    }

    public function update_profile(UpdateUserRequest $request)
    {
        $user = Auth::user();

        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('profile.edit')->with('success', 'تم تحديث بياناتك بنجاح');
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
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
