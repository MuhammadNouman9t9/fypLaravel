<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EnableTwoFactorRequest;
use App\Http\Requests\Auth\VerifyTwoFactorRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA;
    }

    public function show(Request $request): View
    {
        $user = Auth::user();

        // Generate QR code if 2FA is not enabled
        $qrCodeUrl = null;
        $secret = null;

        if (! $user->hasTwoFactorEnabled()) {
            // Generate secret server-side and stash in session so the user
            // never gets to choose it. Reuse a stashed secret if one exists
            // so refreshing the page doesn't invalidate the QR they scanned.
            $secret = $request->session()->get('pending_2fa_secret');
            if (! $secret) {
                $secret = $this->google2fa->generateSecretKey();
                $request->session()->put('pending_2fa_secret', $secret);
            }
            $qrCodeUrl = $this->google2fa->getQRCodeUrl(
                config('app.name', 'SafeNest'),
                $user->email,
                $secret
            );
        }

        return view('auth.two-factor', [
            'user' => $user,
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $secret,
        ]);
    }

    public function enable(EnableTwoFactorRequest $request): RedirectResponse
    {
        $user = Auth::user();
        // Trust only the server-side secret we issued in show(); ignore any
        // value submitted in the request body to prevent secret-substitution.
        $secret = $request->session()->get('pending_2fa_secret');
        if (! $secret) {
            return redirect()->route('two-factor.show')
                ->withErrors(['code' => 'Setup session expired. Please try again.']);
        }
        $code = $request->validated('code');

        $valid = $this->google2fa->verifyKey($secret, $code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.'])->withInput();
        }

        // Consume the pending secret so it can't be re-used.
        $request->session()->forget('pending_2fa_secret');

        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
        ]);

        // Mark as verified for current session
        $request->session()->put('two_factor_verified', true);

        return redirect()->route('profile.edit')
            ->with('status', 'Two-factor authentication has been enabled.')
            ->with('recoveryCodes', $recoveryCodes);
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('status', 'Two-factor authentication has been disabled.');
    }

    public function showVerify(): View
    {
        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-verify');
    }

    public function verify(VerifyTwoFactorRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        $code = $request->validated('code');
        $secret = $user->getTwoFactorSecret();
        $valid = $this->google2fa->verifyKey($secret, $code);

        if (! $valid) {
            // Check recovery codes using constant-time comparison to avoid
            // leaking information through response timing.
            $recoveryCodes = $user->getRecoveryCodes();
            $matched = null;
            foreach ($recoveryCodes as $stored) {
                if (hash_equals($stored, $code)) {
                    $matched = $stored;
                    break;
                }
            }

            if ($matched === null) {
                return back()->withErrors(['code' => 'Invalid verification code.']);
            }

            // Remove the consumed recovery code.
            $recoveryCodes = array_values(array_diff($recoveryCodes, [$matched]));
            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);
        }

        // Mark 2FA as verified for this session
        $request->session()->put('two_factor_verified', true);

        return redirect()->intended(route('dashboard'));
    }

    public function showRecoveryCodes(): View
    {
        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit');
        }

        $recoveryCodes = $user->getRecoveryCodes();

        return view('auth.recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ]);

        return redirect()->route('two-factor.recovery-codes')
            ->with('recoveryCodes', $recoveryCodes)
            ->with('status', 'Recovery codes have been regenerated.');
    }

    protected function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4)));
        }

        return $codes;
    }
}
