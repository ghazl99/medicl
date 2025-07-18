<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display the registration view.
     */
    public function create_pharmacists()
    {
        return view('user::admin.pharmacists.create');
    }

    public function pharmacistsList()
    {
        $pharmacists = $this->userService->getPharmacists();

        return view('user::admin.pharmacists.index', [
            'pharmacists' => $pharmacists,
        ]);
    }

    public function suppliersList(Request $request)
    {

        $suppliers = $this->userService->getSuppliers();

        return view('user::admin.suppliers.index', [
            'suppliers' => $suppliers,
        ]);
    }

    public function create_suppliers()
    {
        return view('user::admin.suppliers.create');
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

                return redirect()->route('suppliers.index');
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
        $user = $this->userService->getUserById($id);
        if ($user->hasRole('صيدلي')) {
            return view('user::admin.pharmacists.edit', [
                'pharmacist' => $user,
            ]);
        } elseif ($user->hasRole('مورد')) {
            return view('user::admin.suppliers.edit', [
                'supplier' => $user,
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
