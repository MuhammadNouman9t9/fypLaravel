<x-guest-layout>
    <div class="text-center mb-4">
        <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
    </div>

    <h2 class="h3 fw-bold text-center text-dark mb-2">
        {{ __('Recovery Codes') }}
    </h2>
    <p class="text-secondary text-center mb-4">
        {{ __('Store these codes in a safe place. Each can be used once if you lose access to your authenticator app.') }}
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if (! empty($recoveryCodes))
        <div class="bg-light border rounded-3 p-3 mb-4 font-monospace">
            <div class="row row-cols-2 g-2">
                @foreach ($recoveryCodes as $code)
                    <div class="col"><span class="text-dark">{{ $code }}</span></div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning small">
            {{ __('No recovery codes available. Regenerate them below.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('two-factor.recovery-codes.regenerate') }}">
        @csrf
        <div class="mb-3">
            <x-input-label for="password" :value="__('Confirm with current password to regenerate')" />
            <x-text-input id="password" class="mt-2" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <button type="submit" class="btn btn-outline-primary w-100">{{ __('Regenerate Recovery Codes') }}</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('profile.edit') }}" class="text-decoration-none small">{{ __('Back to Profile') }}</a>
    </div>
</x-guest-layout>
