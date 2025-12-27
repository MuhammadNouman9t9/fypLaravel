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

        // If email is already verified, always redirect to dashboard
        // Phone verification is optional, don't block access if email is verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // Show email verification page if email is not verified
        return view('auth.verify-email');
    }
}
