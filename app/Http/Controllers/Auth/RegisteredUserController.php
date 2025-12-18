<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
    public function store(Request $request): RedirectResponse
    {
        // Prepend +92 to phone number for validation
        $phone = '+92'.preg_replace('/^\+92/', '', $request->phone);

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:32', 'regex:/^[0-9]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Validate unique phone with +92 prefix
        if (User::where('phone', $phone)->exists()) {
            return back()->withErrors(['phone' => 'The phone number has already been taken.'])->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Store registered email in session for login page suggestion
        $request->session()->put('registered_email', $request->email);

        // Automatically send OTP via email
        $user->sendOtp('email');

        // Redirect directly to OTP verification page
        return redirect(route('otp.verify-page'))->with('status', 'otp-sent')->with('channel', 'email');
    }
}
