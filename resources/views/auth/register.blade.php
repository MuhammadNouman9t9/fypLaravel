<x-guest-layout>
    <div class="text-center mb-4">
        <x-shield-logo style="width: 56px; height: 56px; color: #6f42c1;" />
    </div>

    <h2 class="h3 text-center mb-1">Create Account</h2>
    <p class="text-muted text-center mb-4">Sign up to get started with SafeNest</p>

    <form method="POST" action="{{ route('register', absolute: false) }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="first_name" class="form-label fw-semibold">First Name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" minlength="2" maxlength="50" required autofocus autocomplete="given-name" class="form-control">
                @error('first_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="last_name" class="form-label fw-semibold">Last Name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" minlength="2" maxlength="50" required autocomplete="family-name" class="form-control">
                @error('last_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" maxlength="255" required autocomplete="username" class="form-control">
                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="phone" class="form-label fw-semibold">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+923001234567" maxlength="16" autocomplete="tel" class="form-control">
                <div class="form-text">Optional. Format: +92XXXXXXXXXX</div>
                @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="avatar" class="form-label fw-semibold">Display Picture</label>
                <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png" required class="form-control">
                <div class="form-text">JPG or PNG.</div>
                @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                        <svg id="password-eye" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="password-eye-off" class="d-none" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <div class="form-text">Min 8 characters, one uppercase, one special character.</div>
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="cnic" class="form-label fw-semibold">CNIC (13 digits)</label>
                <input id="cnic" type="text" name="cnic" value="{{ old('cnic') }}" inputmode="numeric" placeholder="1234567890123" minlength="13" maxlength="13" pattern="^[0-9]{13}$" required autocomplete="off" class="form-control">
                <div class="form-text">Enter 13 digits without spaces.</div>
                @error('cnic') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="study_program" class="form-label fw-semibold">Study Program</label>
                <select id="study_program" name="study_program" required class="form-select">
                    <option value="" @selected(old('study_program') === null || old('study_program') === '')>Select program</option>
                    <option value="BSCS" @selected(old('study_program') === 'BSCS')>BS Computer Science</option>
                    <option value="BSSE" @selected(old('study_program') === 'BSSE')>BS Software Engineering</option>
                    <option value="BSIT" @selected(old('study_program') === 'BSIT')>BS Information Technology</option>
                    <option value="BBA" @selected(old('study_program') === 'BBA')>BBA</option>
                    <option value="OTHER" @selected(old('study_program') === 'OTHER')>Other</option>
                </select>
                @error('study_program') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="about_me" class="form-label fw-semibold">About Me</label>
                <textarea id="about_me" name="about_me" required rows="4" class="form-control">{{ old('about_me') }}</textarea>
                @error('about_me') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 d-grid">
                <button type="submit" class="btn btn-primary btn-lg">{{ __('Register') }}</button>
            </div>
        </div>
    </form>

    <div class="text-center mt-4">
        <span class="text-muted">Already have an account?</span>
        <a href="{{ route('login') }}" class="fw-semibold ms-1">Login</a>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            const eyeOff = document.getElementById(fieldId + '-eye-off');

            if (!field || !eye || !eyeOff) return;

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

        const cnic = document.getElementById('cnic');
        if (cnic) {
            cnic.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
            });
        }
    </script>
</x-guest-layout>
