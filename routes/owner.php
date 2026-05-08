<?php

use App\Http\Controllers\Owner\Auth\OwnerLoginController;
use App\Http\Controllers\Owner\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Owner Login Routes (public, but redirects if already logged in as owner).
// Self-registration intentionally disabled — owner accounts are provisioned manually.
// Throttle only the POST so the GET form remains available.
Route::middleware('guest')->prefix('owner')->name('owner.')->group(function () {
    Route::get('login', [OwnerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [OwnerLoginController::class, 'login'])->middleware('throttle:5,1');
});

// Owner Protected Routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Owner Logout
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('owner.login');
    })->name('logout');
});
