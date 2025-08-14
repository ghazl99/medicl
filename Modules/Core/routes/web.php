<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Models\Notification;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cores', CoreController::class)->names('core');
    Route::post('/save-token', [CoreController::class, 'saveToken'])->name('save-token');
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);

        // تحديث وقت القراءة إلى الوقت الحالي
        $notification->is_read = 1;
        $notification->save();

        // إعادة التوجيه إلى رابط الإشعار الأصلي أو الصفحة الرئيسية إذا غير موجود
        return redirect($notification->url ?? url('/'));
    })->name('notifications.read');
});
