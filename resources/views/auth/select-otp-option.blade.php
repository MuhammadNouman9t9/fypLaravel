<x-guest-layout>
    <!-- Shield Icon -->
    <div class="flex justify-center mb-6">
        <x-shield-logo class="h-16 w-16 text-blue-600" />
    </div>

    <!-- Welcome Heading -->
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-2">
        Verify Your Account
    </h2>
    <p class="text-gray-600 text-center mb-8">
        Choose how you'd like to receive your OTP code
    </p>

    @if (session('show_save_credentials_alert'))
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="h-5 w-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-900 mb-1">
                        {{ __('Important: Save Your Credentials') }}
                    </p>
                    <p class="text-sm text-amber-800">
                        {{ __('Please save your email and password in a safe place. You will need them to login to your account.') }}
                    </p>
                    <div class="mt-2 p-2 bg-white rounded border border-amber-200">
                        <p class="text-xs font-mono text-amber-900">
                            <span class="font-semibold">Email:</span> {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('status') == 'otp-sent')
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm font-medium text-green-800">
                {{ __('OTP has been sent successfully!') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- OTP Option Selection Form -->
    <form method="POST" action="{{ route('otp.select-option') }}" class="space-y-5">
        @csrf

        <!-- Email Option -->
        <button 
            type="submit" 
            name="channel" 
            value="email"
            class="w-full p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition flex items-center gap-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 text-left">
                <h3 class="font-semibold text-gray-900">Send OTP via Email</h3>
                <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
            </div>
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Phone Option -->
        <button 
            type="submit" 
            name="channel" 
            value="phone"
            class="w-full p-4 border-2 border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition flex items-center gap-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            <div class="flex-shrink-0">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </div>
            <div class="flex-1 text-left">
                <h3 class="font-semibold text-gray-900">Send OTP via Phone</h3>
                <p class="text-sm text-gray-600">{{ auth()->user()->phone }}</p>
            </div>
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </form>
</x-guest-layout>

