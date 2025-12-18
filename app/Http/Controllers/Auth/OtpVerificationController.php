<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show OTP option selection page.
     * Automatically sends email OTP and redirects to verification page.
     */
    public function showSelectOption(Request $request): RedirectResponse
    {
        $user = $request->user();

        // If already verified, redirect to dashboard
        if ($user && $user->phone_verified_at) {
            return redirect(route('dashboard'));
        }

        // Automatically send OTP via email
        if ($user->email) {
            $user->sendOtp('email');

            return redirect(route('otp.verify-page'))->with('status', 'otp-sent')->with('channel', 'email');
        }

        return redirect(route('dashboard'));
    }

    /**
     * Handle OTP option selection and send OTP.
     * Only email channel is supported now.
     */
    public function selectOption(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->email) {
            return back()->withErrors(['channel' => 'Email address is required to send OTP via email.']);
        }

        $otp = $user->sendOtp('email');

        // Log OTP for debugging (only in development)
        if (config('app.debug')) {
            \Illuminate\Support\Facades\Log::info('OTP Generated', [
                'user_id' => $user->id,
                'email' => $user->email,
                'channel' => 'email',
                'otp' => $otp,
            ]);
        }

        return redirect(route('otp.verify-page'))->with('status', 'otp-sent')->with('channel', 'email');
    }

    /**
     * Send OTP to user's phone.
     */
    public function send(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->phone) {
            return back()->withErrors(['phone' => 'Phone number is required to send OTP.']);
        }

        $user->sendOtp('phone');

        return back()->with('status', 'otp-sent');
    }

    /**
     * Show OTP verification page.
     */
    public function showVerifyPage(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // If already verified, redirect to dashboard
        if ($user && $user->phone_verified_at) {
            return redirect(route('dashboard'));
        }

        // Get the latest unused OTP for the logged-in user
        $latestOtp = Otp::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return view('auth.verify-otp', [
            'latestOtp' => $latestOtp?->otp,
        ]);
    }

    /**
     * Verify OTP.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string'],
        ]);

        $user = $request->user();

        // Trim and get OTP value
        $otp = trim($request->otp);

        // Validate OTP format (6 digits)
        if (strlen($otp) !== 6 || ! ctype_digit($otp)) {
            return back()->withErrors(['otp' => 'OTP must be 6 digits.']);
        }

        $otpRecord = Otp::where('user_id', $user->id)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $otpRecord->update(['is_used' => true]);

        $user->update(['phone_verified_at' => now()]);

        return redirect(route('landing.home'))->with('status', 'phone-verified');
    }
}
