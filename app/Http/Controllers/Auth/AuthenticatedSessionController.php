<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If already logged in as admin, redirect to admin dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // If already logged in as owner, redirect to owner dashboard
        if (Auth::check() && Auth::user()->isOwner()) {
            return redirect()->route('owner.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Clear registered email from session after successful login
        $request->session()->forget('registered_email');

        $user = Auth::user();
        $user->refresh();

        // If user is admin, logout and redirect to admin login
        if ($user->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Admin users must login through the admin login page.',
            ])->onlyInput('email');
        }

        // If user is owner, logout and redirect to owner login
        if ($user->isOwner()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('owner.login')->withErrors([
                'email' => 'Owner users must login through the owner login page.',
            ])->onlyInput('email');
        }

        // Check if 2FA is enabled and not verified in this session
        if ($user->two_factor_confirmed_at && ! $request->session()->get('two_factor_verified')) {
            return redirect()->route('two-factor.verify');
        }

        // Regular users go to home page
        return redirect()->intended(route('landing.home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
