<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Admin login page (UI only — posts to Fortify /login)
Route::view('/admin/login', 'admin.auth.login')->name('admin.login');


/*
|--------------------------------------------------------------------------
| Authenticated routes (require authentication)
|--------------------------------------------------------------------------
| - Routes in this group require the user to be logged in.
| - Some sub-groups also require email verification and/or role checks.
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |-----------------------------------------------------------------------
    | Email verification helpers (minimal endpoints)
    |-----------------------------------------------------------------------
    | These are the standard verification routes. They are protected by auth
    | where appropriate.
    */
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->intended(route('dashboard'));
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');


    /*
    |-----------------------------------------------------------------------
    | Routes that require VERIFIED email
    |-----------------------------------------------------------------------
    | Dashboard and payment flows should only be accessible by users who have
    | verified their email addresses.
    */
    Route::middleware('verified')->group(function () {

        // User dashboard
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // 2FA UI pages (profile area)
        Route::prefix('user')->name('user.')->group(function () {
            Route::view('two-factor-authentication', 'profile.two-factor-authentication')
                ->name('two-factor.index');

            Route::view('two-factor-qr-code', 'profile.show-qr-code')
                ->name('two-factor.qr');

            Route::view('two-factor-recovery-codes', 'profile.recovery-codes')
                ->name('two-factor.recovery');
        });

        /*
        |-------------------------------------------------------------------
        | Payment routes (authenticated + email verified)
        |-------------------------------------------------------------------
        | All payment endpoints are POST webhooks from the payment provider,
        | so keep them under auth + verified to prevent abuse.
        */
        Route::prefix('payment')->name('sslc.')->group(function () {
            Route::post('success', [PaymentController::class, 'success'])->name('success');
            Route::post('failure', [PaymentController::class, 'failure'])->name('failure');
            Route::post('cancel',  [PaymentController::class, 'cancel'])->name('cancel');
            Route::post('ipn',     [PaymentController::class, 'ipn'])->name('ipn');

            // If you need a "start payment" page/action for logged-in users:
            Route::get('pay', [PaymentController::class, 'createPayment'])->name('pay');
        });

    }); // end verified
}); // end auth


/*
|--------------------------------------------------------------------------
| Admin area
|--------------------------------------------------------------------------
| Admin routes are under /admin and require the admin role. You may also
| require email verification for admins by adding 'verified' to middleware.
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // add other admin routes here
});