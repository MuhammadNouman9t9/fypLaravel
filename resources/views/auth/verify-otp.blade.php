<x-guest-layout>
    <!-- Shield Icon -->
    <div class="flex justify-center mb-6">
        <x-shield-logo class="h-16 w-16 text-blue-600" />
    </div>

    <!-- Welcome Heading -->
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-2">
        Verify OTP
    </h2>
    <p class="text-gray-600 text-center mb-8">
        Enter the 6-digit OTP sent to your email address
    </p>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- OTP Verification Form -->
    <form method="POST" action="{{ route('otp.verify') }}" class="space-y-5" id="otp-form">
        @csrf

        <div>
            <x-input-label for="otp" :value="__('OTP Code')" class="font-semibold text-gray-900" />
            <x-text-input 
                id="otp" 
                name="otp" 
                type="text" 
                class="block mt-2 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-center text-2xl tracking-widest" 
                placeholder="000000"
                maxlength="6"
                pattern="[0-9]{6}"
                required 
                autofocus 
                autocomplete="one-time-code" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('otp')" />
        </div>

        <div>
            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                {{ __('Verify OTP') }}
            </button>
        </div>
    </form>
</x-guest-layout>

