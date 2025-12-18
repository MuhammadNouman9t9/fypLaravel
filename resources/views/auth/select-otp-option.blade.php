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
        OTP has been sent to your email address
    </p>


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

    </form>
</x-guest-layout>

