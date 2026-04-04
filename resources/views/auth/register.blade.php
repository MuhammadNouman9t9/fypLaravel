<x-guest-layout>
    <!-- Shield Icon -->
    <div class="flex justify-center mb-6">
        <x-shield-logo class="h-16 w-16 text-purple-600" />
    </div>

    <!-- Welcome Heading -->
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-2">
        Create Account
    </h2>
    <p class="text-gray-600 text-center mb-8">
        Sign up to get started with SafeNest
    </p>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register', absolute: false) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- First Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" class="font-semibold text-gray-900" />
                <x-text-input
                    id="first_name"
                    class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    type="text"
                    name="first_name"
                    :value="old('first_name')"
                    minlength="2"
                    maxlength="50"
                    required
                    autofocus
                    autocomplete="given-name"
                />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" class="font-semibold text-gray-900" />
                <x-text-input
                    id="last_name"
                    class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    type="text"
                    name="last_name"
                    :value="old('last_name')"
                    minlength="2"
                    maxlength="50"
                    required
                    autocomplete="family-name"
                />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-900" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500" 
                type="email" 
                name="email" 
                :value="old('email')" 
                placeholder="you@example.com"
                maxlength="255"
                required 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" class="font-semibold text-gray-900" />
            <x-text-input
                id="phone"
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                type="text"
                name="phone"
                :value="old('phone')"
                placeholder="+923001234567"
                maxlength="16"
                autocomplete="tel"
            />
            <p class="mt-1 text-xs text-gray-500">Optional. Format: +92XXXXXXXXXX</p>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Display Picture -->
        <div>
            <x-input-label for="avatar" :value="__('Display Picture')" class="font-semibold text-gray-900" />
            <input
                id="avatar"
                name="avatar"
                type="file"
                accept="image/jpeg,image/png"
                required
                class="block mt-2 w-full text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
            />
            <p class="mt-1 text-xs text-gray-500">JPG or PNG.</p>
            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-900" />
            <div class="relative">
                <x-text-input 
                    id="password" 
                    class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 pr-10"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" 
                />
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg id="password-eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="password-eye-off" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500">Min 8 characters, one uppercase, one special character.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- CNIC -->
        <div>
            <x-input-label for="cnic" :value="__('CNIC (13 digits)')" class="font-semibold text-gray-900" />
            <x-text-input
                id="cnic"
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                type="text"
                name="cnic"
                :value="old('cnic')"
                inputmode="numeric"
                placeholder="1234567890123"
                minlength="13"
                maxlength="13"
                pattern="^[0-9]{13}$"
                required
                autocomplete="off"
            />
            <p class="mt-1 text-xs text-gray-500">Enter 13 digits without spaces.</p>
            <x-input-error :messages="$errors->get('cnic')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- Preferred Language -->
            <div>
                <x-input-label for="preferred_language" :value="__('Preferred Language')" class="font-semibold text-gray-900" />
                <select
                    id="preferred_language"
                    name="preferred_language"
                    class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                >
                    <option value="" @selected(old('preferred_language') === null || old('preferred_language') === '')>{{ __('Select language') }}</option>
                    <option value="en" @selected(old('preferred_language') === 'en')>English</option>
                    <option value="ur" @selected(old('preferred_language') === 'ur')>Urdu</option>
                </select>
                <x-input-error :messages="$errors->get('preferred_language')" class="mt-2" />
            </div>

            <!-- Timezone -->
            <div>
                <x-input-label for="timezone" :value="__('Timezone')" class="font-semibold text-gray-900" />
                <x-text-input
                    id="timezone"
                    class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    type="text"
                    name="timezone"
                    :value="old('timezone')"
                    placeholder="Asia/Karachi"
                    maxlength="64"
                    autocomplete="off"
                />
                <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
            </div>
        </div>

        <!-- Study Program -->
        <div>
            <x-input-label for="study_program" :value="__('Study Program')" class="font-semibold text-gray-900" />
            <select
                id="study_program"
                name="study_program"
                required
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
            >
                <option value="" @selected(old('study_program') === null || old('study_program') === '')>Select program</option>
                <option value="BSCS" @selected(old('study_program') === 'BSCS')>BS Computer Science</option>
                <option value="BSSE" @selected(old('study_program') === 'BSSE')>BS Software Engineering</option>
                <option value="BSIT" @selected(old('study_program') === 'BSIT')>BS Information Technology</option>
                <option value="BBA" @selected(old('study_program') === 'BBA')>BBA</option>
                <option value="OTHER" @selected(old('study_program') === 'OTHER')>Other</option>
            </select>
            <x-input-error :messages="$errors->get('study_program')" class="mt-2" />
        </div>

        <!-- About Me -->
        <div>
            <x-input-label for="about_me" :value="__('About Me')" class="font-semibold text-gray-900" />
            <textarea
                id="about_me"
                name="about_me"
                required
                rows="4"
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
            >{{ old('about_me') }}</textarea>
            <x-input-error :messages="$errors->get('about_me')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div>
            <button type="submit" class="w-full bg-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                {{ __('Register') }}
            </button>
        </div>
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:text-purple-700">
                Login
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
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                field.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }

        // CNIC validation - only allow digits
        const cnic = document.getElementById('cnic');
        if (cnic) {
            cnic.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 13);
            });
        }
    </script>
</x-guest-layout>
