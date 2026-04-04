<x-guest-layout>
    <div class="text-center mb-4">
        <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
    </div>

    <h2 class="h3 fw-bold text-center text-dark mb-2">
        Welcome Back
    </h2>
    <p class="text-secondary text-center mb-4">
        Login to access your SafeNest account
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-2"
                type="email"
                name="email"
                :value="old('email', session('registered_email'))"
                placeholder="you@example.com"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="position-relative mt-2">
                <x-text-input
                    id="password"
                    class="pe-5"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />
                <button type="button" onclick="togglePassword('password')" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-secondary p-1 me-1" tabindex="-1" aria-label="Toggle password">
                    <svg id="password-eye" style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="password-eye-off" class="d-none" style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label small text-secondary" for="remember_me">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <a class="small link-primary text-decoration-none" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                {{ __('Login') }}
            </button>
        </div>
    </form>

    <div class="mt-4 text-center">
        <p class="small text-secondary mb-0">
            Don't have an account?
            <a href="{{ route('register') }}" class="fw-semibold link-primary text-decoration-none">
                Sign up
            </a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            const eyeOff = document.getElementById(fieldId + '-eye-off');

            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.add('d-none');
                eyeOff.classList.remove('d-none');
            } else {
                field.type = 'password';
                eye.classList.remove('d-none');
                eyeOff.classList.add('d-none');
            }
        }
    </script>
</x-guest-layout>
