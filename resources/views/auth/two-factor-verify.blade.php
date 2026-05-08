<x-guest-layout>
    <div class="text-center mb-4">
        <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
    </div>

    <h2 class="h3 fw-bold text-center text-dark mb-2">
        {{ __('Two-Factor Verification') }}
    </h2>
    <p class="text-secondary text-center mb-4">
        {{ __('Enter the 6-digit code from your authenticator app, or one of your recovery codes.') }}
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('two-factor.verify.post') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="code" :value="__('Authentication Code')" />
            <x-text-input
                id="code"
                class="mt-2 text-center font-monospace fs-5"
                type="text"
                name="code"
                required
                autofocus
                autocomplete="one-time-code"
                inputmode="text"
                maxlength="20"
                placeholder="000000"
            />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-primary w-100">{{ __('Verify') }}</button>
    </form>

    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link p-0 small text-decoration-none">{{ __('Sign out') }}</button>
        </form>
    </div>
</x-guest-layout>
