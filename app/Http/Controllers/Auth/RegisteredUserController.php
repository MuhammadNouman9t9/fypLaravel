<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Prepend +92 to phone number
        $phone = '+92'.$request->validated('phone');

        // Validate unique phone with +92 prefix
        if (User::where('phone', $phone)->exists()) {
            return back()->withErrors(['phone' => 'The phone number has already been taken.'])->withInput();
        }

        $user = User::create([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'email' => $request->validated('email'),
            'phone' => $phone,
            'password' => Hash::make($request->validated('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Store registered email in session for login page suggestion
        $request->session()->put('registered_email', $request->email);

        // Redirect to OTP option selection page
        return redirect(route('otp.select-option'))->with('show_save_credentials_alert', true);
    }
}
