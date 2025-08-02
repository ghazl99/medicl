<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->is_approved != 1) {
            // إذا المستخدم غير موافق عليه
            abort(403, 'حسابك قيد المراجعة. الرجاء الانتظار حتى تتم الموافقة.');
        }

        return $next($request);
    }
}
