<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    @if (session('status') == 'otp-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new OTP has been sent to your phone number.') }}
        </div>
    @endif

    <!-- OTP Verification Section -->
    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-2">
            {{ __('Verify Phone Number with OTP') }}
        </h3>
        <p class="text-sm text-gray-600 mb-4">
            {{ __('Enter the 6-digit OTP sent to your phone number.') }}
        </p>

        <form method="POST" action="{{ route('otp.verify') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="otp" :value="__('OTP Code')" />
                <x-text-input 
                    id="otp" 
                    name="otp" 
                    type="text" 
                    class="mt-1 block w-full" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required 
                    autofocus 
                    autocomplete="one-time-code" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('otp')" />
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Enter the 6-digit code sent to your phone') }}
                </p>
            </div>

            <div>
                <x-primary-button>
                    {{ __('Verify OTP') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('otp.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                {{ __('Resend OTP') }}
            </button>
        </form>
    </div>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
