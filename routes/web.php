<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');

// Admin login UI (Fortify handles POST /login)
Route::view('/admin/login', 'admin.auth.login')->name('admin.login');


/*
|--------------------------------------------------------------------------
| Authentication & Email Verification
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Email verification screens
    Route::get('/email/verify', fn () => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->intended(route('dashboard'));
    })
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })
        ->middleware('throttle:6,1')
        ->name('verification.send');
});


/*
|--------------------------------------------------------------------------
| Authenticated + Verified User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');


    /*
    |----------------------------------------------------------------------
    | User Profile / 2FA
    |----------------------------------------------------------------------
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::view('two-factor-authentication', 'profile.two-factor-authentication')
            ->name('two-factor.index');

        Route::view('two-factor-qr-code', 'profile.show-qr-code')
            ->name('two-factor.qr');

        Route::view('two-factor-recovery-codes', 'profile.recovery-codes')
            ->name('two-factor.recovery');
    });
});

/*
    |----------------------------------------------------------------------
    | Payments (SSLCOMMERZ)
    |----------------------------------------------------------------------
    */
Route::prefix('payment')->name('sslc.')->group(function () {
    Route::middleware(['auth', 'verified'])->get('pay', [PaymentController::class, 'createPayment'])->name('pay');

    Route::post('success', [PaymentController::class, 'success'])->name('success');
    Route::post('failure', [PaymentController::class, 'failure'])->name('failure');
    Route::post('cancel',  [PaymentController::class, 'cancel'])->name('cancel');
    Route::post('ipn',     [PaymentController::class, 'ipn'])->name('ipn');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', fn () => view('admin.dashboard'))
            ->name('dashboard');

        // Additional admin routes here
    });


/*
|--------------------------------------------------------------------------
| Social Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('social.')->group(function () {

    Route::get('{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->name('redirect');

    Route::get('{provider}/callback', [SocialAuthController::class, 'loginCallback'])
        ->name('callback');

    Route::middleware('auth')->group(function () {
        Route::get('{provider}/connect', [SocialAuthController::class, 'connect'])
            ->name('connect');

        Route::get('{provider}/connect/callback', [SocialAuthController::class, 'connectCallback'])
            ->name('connect.callback');
    });
});
