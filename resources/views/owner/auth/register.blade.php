<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Owner Register - {{ config('app.name', 'SafeNest') }}</title>

    <x-layout-assets />
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div class="w-100" style="max-width: 28rem;">
        <div class="bg-white rounded-4 shadow-lg p-4 p-md-5">
            <div class="text-center mb-4">
                <x-shield-logo class="text-primary mx-auto d-block" style="width: 4rem; height: 4rem;" />
            </div>

            <h2 class="h3 fw-bold text-center text-dark mb-2">Owner Registration</h2>
            <p class="text-secondary text-center mb-4">Create your owner account</p>

            <form method="POST" action="{{ route('owner.register') }}">
                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label fw-semibold">First Name</label>
                    <input id="first_name" class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" />
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label fw-semibold">Last Name</label>
                    <input id="last_name" class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" />
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="owner@example.com" required autocomplete="username" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+92</span>
                        <input
                            id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') ? (str_starts_with(old('phone'), '+92') ? substr(old('phone'), 3) : old('phone')) : '' }}"
                            placeholder="3001234567"
                            required
                            autocomplete="tel"
                        />
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="position-relative">
                        <input id="password" class="form-control pe-5 @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" />
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
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                    <div class="position-relative">
                        <input id="password_confirmation" class="form-control pe-5 @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <button type="button" onclick="togglePassword('password_confirmation')" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-secondary p-1 me-1" tabindex="-1" aria-label="Toggle password">
                            <svg id="password_confirmation-eye" style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="password_confirmation-eye-off" class="d-none" style="width: 1.25rem; height: 1.25rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Register</button>
                </div>
            </form>

            <p class="text-center small text-secondary mb-2">
                Already have an account?
                <a href="{{ route('owner.login') }}" class="fw-semibold link-primary text-decoration-none">Login</a>
            </p>

            <div class="text-center">
                <a href="{{ route('landing.home') }}" class="small link-secondary text-decoration-none">← Back to Website</a>
            </div>
        </div>
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
</body>
</html>
