<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'avatar_path' => $avatarPath,
            'cnic' => $validated['cnic'],
            'preferred_language' => $validated['preferred_language'] ?? null,
            'timezone' => $validated['timezone'] ?? null,
            'study_program' => $validated['study_program'],
            'about_me' => $validated['about_me'],
            'password' => $validated['password'],
        ]);

        // Store registered email in session for login page suggestion
        $request->session()->put('registered_email', $validated['email']);

        return redirect()
            ->route('login')
            ->with('status', 'registered');
    }
}
