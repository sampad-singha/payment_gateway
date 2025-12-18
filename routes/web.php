<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// USER DASHBOARD
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// ADMIN DASHBOARD (ROLE PROTECTED)
Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// ADMIN LOGIN PAGE (UI ONLY)
Route::view('/admin/login', 'admin.auth.login')->name('admin.login');

Route::middleware('auth')->group(function () {
    Route::view('/user/two-factor-authentication', 'profile.two-factor-authentication');
    Route::view('/user/two-factor-qr-code', 'profile.show-qr-code');
    Route::view('/user/two-factor-recovery-codes', 'profile.recovery-codes');
});


// SSLCommerz PAYMENT ROUTES
Route::prefix('payment')
    ->name('sslc.')
    ->group(function (){
        Route::post('success', [PaymentController::class, 'success'])->name('success');
        Route::post('failure', [PaymentController::class, 'failure'])->name('failure');
        Route::post('cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::post('ipn', [PaymentController::class, 'ipn'])->name('ipn');
    });

Route::get('pay', [PaymentController::class, 'createPayment'])->name('pay');
