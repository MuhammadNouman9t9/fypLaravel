<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    private const ADMIN_EMAIL = 'admin@safenest.com';

    private const ADMIN_USERNAME = 'Admin';

    public function showLoginForm(): View|RedirectResponse
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check()) {
            $user = Auth::user()->load('roles');
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($credentials['username'] !== self::ADMIN_USERNAME) {
            return back()->withErrors([
                'username' => 'Invalid username.',
            ])->onlyInput('username');
        }

        if (Auth::attempt([
            'email' => self::ADMIN_EMAIL,
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            // Eager load roles to avoid N+1 queries
            $user = Auth::user()->load('roles');

            // Check if user is admin
            if (! $user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'You do not have admin privileges.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }
}
