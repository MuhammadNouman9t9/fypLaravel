<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Profile\AddressController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Landing Pages
Route::get('/', \App\Http\Controllers\Landing\HomeController::class)->name('landing.home');
Route::get('/about', fn () => redirect()->route('landing.home'))->name('landing.about');
Route::get('/products', \App\Http\Controllers\Landing\ProductsController::class)->name('landing.products');
Route::get('/products/{product:slug}', [\App\Http\Controllers\Landing\ProductsController::class, 'show'])->name('landing.product.show');

Route::view('/projects', 'pages.projects')->name('pages.projects');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product:slug}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');

Route::get('/dashboard', fn () => redirect()->route('landing.home'))
    ->middleware(['auth', \App\Http\Middleware\EnsureTwoFactorVerified::class])
    ->name('dashboard');

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

// Risk Analyzer Routes
Route::get('/risk-analyzer', [\App\Http\Controllers\RiskAnalyzerController::class, 'index'])->name('risk-analyzer.index');
Route::post('/risk-analyzer/analyze', [\App\Http\Controllers\RiskAnalyzerController::class, 'analyze'])->name('risk-analyzer.analyze');
Route::get('/risk-analyzer/{uuid}', [\App\Http\Controllers\RiskAnalyzerController::class, 'show'])->name('risk-analyzer.show');

// Chatbot Routes
Route::post('/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');

require __DIR__.'/auth.php';
