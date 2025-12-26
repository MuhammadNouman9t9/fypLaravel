<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Admin Login Routes (public, but redirects if already logged in as admin)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login'])
        ->withoutMiddleware([VerifyCsrfToken::class]);
});

// Admin Protected Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    
    // User routes - specific routes before resource to avoid conflicts
    Route::post('users/delete-all', [UserController::class, 'deleteAll'])->name('users.delete-all');
    Route::post('users/{user}/restrict', [UserController::class, 'restrict'])->name('users.restrict');
    Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);

    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');

    Route::get('support', [SupportController::class, 'index'])->name('support.index');
    Route::get('support/{conversation}', [SupportController::class, 'show'])->name('support.show');
    Route::post('support/{conversation}/respond', [SupportController::class, 'respond'])->name('support.respond');
    Route::patch('support/{conversation}/status', [SupportController::class, 'updateStatus'])->name('support.update-status');

    // Analytics Routes
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics.index');

    // Fraud Alerts Routes
    Route::get('fraud-alerts', [\App\Http\Controllers\Admin\FraudAlertController::class, 'index'])->name('fraud-alerts.index');
    Route::get('fraud-alerts/{fraudAlert}', [\App\Http\Controllers\Admin\FraudAlertController::class, 'show'])->name('fraud-alerts.show');
    Route::post('fraud-alerts/{fraudAlert}/resolve', [\App\Http\Controllers\Admin\FraudAlertController::class, 'resolve'])->name('fraud-alerts.resolve');

    // Admin Logout
    Route::post('logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('logout');
});
