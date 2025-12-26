<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Verification Banner -->
            @if (!$hasAddress)
                <div class="mb-6 bg-gradient-to-r from-purple-500 to-blue-500 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-1">{{ __('Complete Your Profile Verification') }}</h3>
                                <p class="text-purple-100">{{ __('Add your address to complete profile verification and enable checkout.') }}</p>
                            </div>
                        </div>
                        <a href="#address-form" onclick="document.getElementById('address-form').scrollIntoView({behavior: 'smooth', block: 'start'}); return false;" class="bg-white text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-purple-50 transition whitespace-nowrap">
                            {{ __('Verify Now') }}
                        </a>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <a href="{{ route('orders.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('My Orders') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('View order history') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Profile') }}</h3>
                            <p class="text-sm text-gray-600">{{ __('Manage your account') }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- My Orders Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('Recent Orders') }}</h3>
                        <a href="{{ route('orders.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                            {{ __('View All') }}
                        </a>
                    </div>

                    @if ($orders->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-600 mb-4">{{ __('No orders yet') }}</p>
                            <a href="{{ route('landing.products') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                                {{ __('Start Shopping') }}
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="font-medium text-gray-900">{{ __('Order') }} #{{ $order->order_number }}</h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $order->created_at->format('M d, Y') }} • {{ $order->items->sum('quantity') }} {{ __('items') }} • {{ $order->currency }} {{ number_format($order->grand_total, 2) }}</p>
                                        </div>
                                        <a href="{{ route('orders.show', $order) }}" class="ml-4 text-sm text-purple-600 hover:text-purple-700 font-medium">
                                            {{ __('View') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="space-y-6">
                <!-- Update Profile Information -->
                <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Manage Addresses -->
                <div id="address-form" class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm scroll-mt-6">
                    <div class="max-w-3xl mx-auto">
                        @include('profile.partials.manage-addresses')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete User -->
                <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 sm:p-8 shadow-sm">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
