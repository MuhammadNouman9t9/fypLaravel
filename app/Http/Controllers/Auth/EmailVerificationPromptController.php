<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If email is already verified, go to dashboard
        if ($user->hasVerifiedEmail()) {
            // If phone is also verified, go to dashboard
            if ($user->phone_verified_at) {
                return redirect()->intended(route('dashboard'));
            }

            // If phone is not verified, redirect to OTP selection
            return redirect()->route('otp.select-option');
        }

        // If phone is already verified but email is not, show email verification page
        if ($user->phone_verified_at) {
            return view('auth.verify-email');
        }

        // If neither email nor phone is verified, redirect to OTP selection
        return redirect()->route('otp.select-option');
    }
}
