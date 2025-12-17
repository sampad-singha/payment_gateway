<?php

use App\Http\Controllers\Auth\AdminAuthController;
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


Route::middleware('auth')->get('/user/two-factor-authentication', function () {
    return view('profile.two-factor-authentication');
});

Route::middleware('auth')->get('/user/two-factor-qr-code', function () {
    return view('profile.show-qr-code');
});

Route::middleware('auth')->get('/user/two-factor-recovery-codes', function () {
    return view('profile.recovery-codes');
});
