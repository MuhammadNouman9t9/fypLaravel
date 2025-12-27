<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load([
            'addresses' => fn ($query) => $query
                ->orderByDesc('is_primary')
                ->orderBy('created_at'),
        ]);

        // Load recent orders
        $orders = \App\Models\Order::query()
            ->where('user_id', $user->id)
            ->with(['items.product.media', 'shipments', 'payments'])
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['marketing_opt_in'] = $request->boolean('marketing_opt_in');

        $user = $request->user();
        $originalEmail = $user->email;

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($user->isDirty('phone')) {
            $user->phone_verified_at = null;
        }

        $user->save();

        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
            && $user->wasChanged('email')
            && ! $user->hasVerifiedEmail()
        ) {
            $user->sendEmailVerificationNotification();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
