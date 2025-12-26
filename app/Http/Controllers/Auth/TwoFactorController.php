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

    public function show(): View
    {
        $user = Auth::user();

        // Generate QR code if 2FA is not enabled
        $qrCodeUrl = null;
        $secret = null;

        if (! $user->hasTwoFactorEnabled()) {
            $secret = $this->google2fa->generateSecretKey();
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
        $secret = $request->validated('secret');
        $code = $request->validated('code');

        // Verify the code before enabling
        $valid = $this->google2fa->verifyKey($secret, $code);

        if (! $valid) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.'])->withInput();
        }

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
            return redirect()->route('profile.edit');
        }

        return view('auth.two-factor-verify');
    }

    public function verify(VerifyTwoFactorRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.edit');
        }

        $code = $request->validated('code');
        $secret = $user->getTwoFactorSecret();
        $valid = $this->google2fa->verifyKey($secret, $code);

        if (! $valid) {
            // Check recovery codes
            $recoveryCodes = $user->getRecoveryCodes();

            if (! in_array($code, $recoveryCodes)) {
                return back()->withErrors(['code' => 'Invalid verification code.']);
            }

            // Remove used recovery code
            $recoveryCodes = array_values(array_diff($recoveryCodes, [$code]));
            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);
        }

        // Mark 2FA as verified for this session
        $request->session()->put('two_factor_verified', true);

        return redirect()->intended(route('profile.edit'));
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
