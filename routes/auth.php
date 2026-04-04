<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Two-Factor Authentication Routes
    Route::get('two-factor', [\App\Http\Controllers\Auth\TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('two-factor/enable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('two-factor/disable', [\App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::get('two-factor/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showVerify'])->name('two-factor.verify');
    Route::post('two-factor/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify'])->name('two-factor.verify.post');
    Route::get('two-factor/recovery-codes', [\App\Http\Controllers\Auth\TwoFactorController::class, 'showRecoveryCodes'])->name('two-factor.recovery-codes');
    Route::post('two-factor/recovery-codes/regenerate', [\App\Http\Controllers\Auth\TwoFactorController::class, 'regenerateRecoveryCodes'])->name('two-factor.recovery-codes.regenerate');

    Route::get('otp/select-option', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'showSelectOption'])
        ->name('otp.select-option');

    Route::post('otp/select-option', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'selectOption'])
        ->middleware('throttle:6,1')
        ->name('otp.select-option');

    Route::get('otp/verify', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'showVerifyPage'])
        ->name('otp.verify-page');

    Route::post('otp/send', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('otp.send');

    Route::post('otp/verify', [\App\Http\Controllers\Auth\OtpVerificationController::class, 'verify'])
        ->middleware('throttle:6,1')
        ->name('otp.verify');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
