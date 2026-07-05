<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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

        $data = [
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
        ];

        // Reuse the pending (unverified) row from a prior registration attempt
        // instead of creating a duplicate for the same email.
        $user = User::where('email', $validated['email'])
            ->whereNull('email_verified_at')
            ->first();

        if ($user) {
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        Auth::login($user);

        $user->sendOtp('email');

        return redirect()
            ->route('otp.verify-page')
            ->with('status', 'otp-sent')
            ->with('channel', 'email');
    }
}
