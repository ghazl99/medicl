<?php

namespace Modules\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Contracts\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('user::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        $request->authenticate();
        $request->session()->regenerate();
        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        DB::commit();
        if (Auth::user()->hasRole('مورد') || Auth::user()->hasRole('المشرف')) {
                    return redirect()->route('dashboard');

        }
        return redirect()->route('pharmacist.home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
