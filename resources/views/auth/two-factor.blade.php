<x-guest-layout>
    <div class="text-center mb-4">
        <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
    </div>

    <h2 class="h3 fw-bold text-center text-dark mb-2">
        {{ __('Two-Factor Authentication') }}
    </h2>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if (session('recoveryCodes'))
        <div class="alert alert-warning small">
            <strong>{{ __('Save these recovery codes!') }}</strong>
            {{ __('They are shown only once.') }}
            <ul class="mt-2 mb-0 ps-3 font-monospace">
                @foreach (session('recoveryCodes') as $code)
                    <li>{{ $code }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($user->hasTwoFactorEnabled())
        <p class="text-secondary text-center mb-4">
            {{ __('Two-factor authentication is currently') }}
            <span class="fw-semibold text-success">{{ __('enabled') }}</span>.
        </p>

        <form method="POST" action="{{ route('two-factor.disable') }}" class="mb-3">
            @csrf
            <div class="mb-3">
                <x-input-label for="password" :value="__('Confirm with current password to disable 2FA')" />
                <x-text-input id="password" class="mt-2" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <button type="submit" class="btn btn-outline-danger w-100">{{ __('Disable Two-Factor Authentication') }}</button>
        </form>

        <a href="{{ route('two-factor.recovery-codes') }}" class="btn btn-link w-100">{{ __('View Recovery Codes') }}</a>
    @else
        <p class="text-secondary text-center mb-4">
            {{ __('Scan the QR code with your authenticator app (Google Authenticator, Authy, 1Password) and enter the 6-digit code below.') }}
        </p>

        <div class="text-center mb-3">
            <div id="qrcode" class="d-inline-block p-3 bg-white border rounded-3"></div>
        </div>

        <div class="mb-3">
            <div class="small text-secondary text-center">{{ __('Or enter this secret manually:') }}</div>
            <div class="font-monospace text-center bg-light p-2 rounded small">{{ $secret }}</div>
        </div>

        <form method="POST" action="{{ route('two-factor.enable') }}">
            @csrf
            {{-- Secret is server-side only (stashed in session by show()). --}}

            <div class="mb-3">
                <x-input-label for="code" :value="__('Verification Code')" />
                <x-text-input id="code" class="mt-2 text-center font-monospace fs-5" type="text" name="code" required autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="000000" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('Enable Two-Factor Authentication') }}</button>
        </form>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            (function () {
                var url = @json($qrCodeUrl);
                if (url && document.getElementById('qrcode')) {
                    new QRCode(document.getElementById('qrcode'), {
                        text: url,
                        width: 180,
                        height: 180,
                    });
                }
            })();
        </script>
    @endif

    <div class="text-center mt-3">
        <a href="{{ route('profile.edit') }}" class="text-decoration-none small">{{ __('Back to Profile') }}</a>
    </div>
</x-guest-layout>
