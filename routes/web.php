<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Profile\AddressController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing Pages
Route::get('/', \App\Http\Controllers\Landing\HomeController::class)->name('landing.home');
Route::get('/about', \App\Http\Controllers\Landing\AboutController::class)->name('landing.about');
Route::get('/products', \App\Http\Controllers\Landing\ProductsController::class)->name('landing.products');

// Catalog Routes
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::view('/projects', 'pages.projects')->name('pages.projects');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product:slug}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/support/experts', [\App\Http\Controllers\Support\ExpertConsultationController::class, 'store'])->name('support.experts.store');

    // User Support Routes
    Route::get('/support', [\App\Http\Controllers\Support\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{conversation}', [\App\Http\Controllers\Support\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{conversation}/respond', [\App\Http\Controllers\Support\SupportController::class, 'respond'])->name('support.respond');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('profile/addresses', AddressController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('profile.addresses');

    // Order History Routes
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// Payment Routes
Route::middleware('auth')->group(function () {
    Route::get('/payment/checkout/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/process', [\App\Http\Controllers\Payment\PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [\App\Http\Controllers\Payment\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Stripe Webhook (no auth required)
Route::post('/payment/webhook/stripe', [\App\Http\Controllers\Payment\PaymentController::class, 'webhook'])->name('payment.webhook.stripe');

// Test Email Route (Remove in production!)
Route::get('/test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('This is a test email from SafeNest application. If you receive this, your email configuration is working correctly!', function ($message) {
            $message->to('mnoumancreat@gmail.com')
                ->subject('SafeNest - Email Test');
        });

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully! Check your inbox at mnoumancreat@gmail.com',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: '.$e->getMessage(),
            'error' => $e->getTraceAsString(),
        ], 500);
    }
})->middleware('auth');

require __DIR__.'/auth.php';
