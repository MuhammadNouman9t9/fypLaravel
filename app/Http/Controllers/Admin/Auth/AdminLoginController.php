<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::check()) {
            $user = Auth::user();
            $user->refresh();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if user is admin
            $user->refresh();
            if (! $user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Clear any intended URL and redirect directly to admin dashboard
            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
