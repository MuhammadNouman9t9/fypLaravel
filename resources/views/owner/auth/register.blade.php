<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Owner Register - {{ config('app.name', 'SafeNest') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg px-8 py-10">
            <!-- Shield Icon -->
            <div class="flex justify-center mb-6">
                <x-shield-logo class="h-16 w-16 text-blue-600" />
            </div>

            <!-- Welcome Heading -->
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-2">
                Owner Registration
            </h2>
            <p class="text-gray-600 text-center mb-8">
                Create your owner account
            </p>

            <!-- Register Form -->
            <form method="POST" action="{{ route('owner.register') }}" class="space-y-5">
                @csrf

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-900 mb-2">
                        First Name
                    </label>
                    <input 
                        id="first_name" 
                        class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm" 
                        type="text" 
                        name="first_name" 
                        value="{{ old('first_name') }}" 
                        required 
                        autofocus 
                        autocomplete="given-name" 
                    />
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-900 mb-2">
                        Last Name
                    </label>
                    <input 
                        id="last_name" 
                        class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm" 
                        type="text" 
                        name="last_name" 
                        value="{{ old('last_name') }}" 
                        required 
                        autocomplete="family-name" 
                    />
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
                        Email
                    </label>
                    <input 
                        id="email" 
                        class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="owner@example.com"
                        required 
                        autocomplete="username" 
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">
                        Phone Number
                    </label>
                    <div class="flex mt-2">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-700 text-sm">
                            +92
                        </span>
                        <input 
                            id="phone" 
                            class="block w-full rounded-r-lg rounded-l-none border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm" 
                            type="tel" 
                            name="phone" 
                            value="{{ old('phone') ? (str_starts_with(old('phone'), '+92') ? substr(old('phone'), 3) : old('phone')) : '' }}" 
                            placeholder="3001234567"
                            required 
                            autocomplete="tel" 
                        />
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm pr-10"
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
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-600 focus:ring-blue-600 shadow-sm pr-10"
                            type="password"
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password" 
                        />
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            <svg id="password_confirmation-eye" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="password_confirmation-eye-off" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Register
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('owner.login') }}" class="text-blue-600 font-semibold hover:text-blue-700">
                        Login
                    </a>
                </p>
            </div>

            <!-- Back to Website -->
            <div class="mt-4 text-center">
                <a href="{{ route('landing.home') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Back to Website
                </a>
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
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                field.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

