<x-guest-layout>
    @php
        $email = auth()->user()->email;
        $atPos = strpos($email, '@');
        $maskedEmail = $atPos > 1
            ? substr($email, 0, 2) . str_repeat('*', max($atPos - 2, 3)) . substr($email, $atPos)
            : $email;
    @endphp

    <div class="text-center mb-4">
        <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
    </div>

    <h2 class="h3 fw-bold text-center text-dark mb-2">
        Verify Your Email
    </h2>
    <p class="text-secondary text-center mb-4">
        We sent a 6-digit code to <span class="fw-semibold text-dark">{{ $maskedEmail }}</span>. Enter it below to continue.
    </p>

    @if (session('status') === 'otp-sent')
        <div class="alert alert-success py-2 small" role="alert">
            A new code has been sent to your email.
        </div>
    @endif

    <x-input-error :messages="$errors->get('otp')" class="mb-3 text-center" />

    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form">
        @csrf

        <div class="mb-3">
            <x-input-label for="otp" :value="__('Verification Code')" class="visually-hidden" />
            <input
                id="otp"
                name="otp"
                type="text"
                class="form-control text-center font-monospace fs-3"
                style="letter-spacing: 0.5rem;"
                placeholder="000000"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                required
                autofocus
                autocomplete="one-time-code"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)"
            >
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                {{ __('Verify Code') }}
            </button>
        </div>
    </form>

    <div class="text-center mt-4">
        <p class="small text-secondary mb-1">Didn't get the code?</p>
        <form method="POST" action="{{ route('otp.select-option') }}" id="resend-form">
            @csrf
            <button type="submit" id="resend-btn" class="btn btn-link p-0 small text-decoration-none fw-semibold">
                Resend Code
            </button>
        </form>
        <p id="resend-countdown" class="small text-secondary mt-1 mb-0"></p>
    </div>

    <div class="text-center mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 small text-decoration-none text-secondary">
                Not you? Sign out
            </button>
        </form>
    </div>

    <script>
        (function () {
            const cooldownSeconds = 60;
            const storageKey = 'otp_resend_until_{{ auth()->id() }}';
            const btn = document.getElementById('resend-btn');
            const countdownEl = document.getElementById('resend-countdown');

            function render(until) {
                const remaining = Math.ceil((until - Date.now()) / 1000);

                if (remaining <= 0) {
                    btn.disabled = false;
                    countdownEl.textContent = '';
                    localStorage.removeItem(storageKey);
                    return;
                }

                btn.disabled = true;
                countdownEl.textContent = `You can resend in ${remaining}s`;
                setTimeout(() => render(until), 1000);
            }

            @if (session('status') === 'otp-sent')
                render(Date.now() + cooldownSeconds * 1000);
                localStorage.setItem(storageKey, Date.now() + cooldownSeconds * 1000);
            @else
                const stored = Number(localStorage.getItem(storageKey) || 0);
                if (stored > Date.now()) {
                    render(stored);
                }
            @endif
        })();
    </script>
</x-guest-layout>
