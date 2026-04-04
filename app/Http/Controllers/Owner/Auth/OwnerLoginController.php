<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OwnerLoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        // If already logged in as owner, redirect to owner dashboard
        if (Auth::check()) {
            $user = Auth::user()->load('roles');
            if ($user->isOwner()) {
                return redirect()->route('owner.dashboard');
            }
        }

        return view('owner.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Eager load roles to avoid N+1 queries
            $user = Auth::user()->load('roles');

            // Check if user is owner
            if (! $user->isOwner()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'You do not have owner privileges.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('owner.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
