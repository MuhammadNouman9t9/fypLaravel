@extends('admin.layout')

@section('title', 'User Details')

@section('content')
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
        </div>
    </div>

    <!-- User Profile Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-8">
            <div class="flex items-center gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-blue-600 shadow-lg">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2 flex items-center gap-2">
                        {{ $user->name }}
                        @if ($user->email_verified_at || $user->phone_verified_at)
                            <svg class="w-6 h-6 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </h1>
                    <div class="flex items-center gap-4 text-blue-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $user->email }}</span>
                        </div>
                        @if($user->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ $user->phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Role Badge -->
                <div class="flex-shrink-0">
                    @if ($user->isAdmin())
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white text-blue-600 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white text-gray-600 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            User
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->first_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-base font-semibold text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Address Section -->
                    @if ($user->addresses->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-4">Address</label>
                            <div class="space-y-4">
                                @foreach ($user->addresses as $address)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        @if ($address->is_primary)
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Primary Address
                                                </span>
                                            </div>
                                        @endif
                                        <p class="text-base font-semibold text-gray-900 leading-relaxed">
                                            {{ $address->line_one }}@if($address->line_two), {{ $address->line_two }}@endif<br>
                                            {{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) {{ $address->postal_code }}@endif<br>
                                            {{ strtoupper($address->country_code) }}
                                        </p>
                                        @if($address->phone || $address->email)
                                            <div class="mt-3 pt-3 border-t border-gray-200 text-sm text-gray-600">
                                                @if($address->phone)
                                                    <div class="mb-1">
                                                        <span class="font-medium">Phone:</span> {{ $address->phone }}
                                                    </div>
                                                @endif
                                                @if($address->email)
                                                    <div>
                                                        <span class="font-medium">Email:</span> {{ $address->email }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                            <p class="text-base text-gray-500">No address provided</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Orders -->
            @if ($user->orders->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Recent Orders ({{ $user->orders->count() }})
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach ($user->orders->take(5) as $order)
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-base font-semibold text-blue-600 hover:text-blue-800">
                                                {{ $order->order_number }}
                                            </a>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->created_at->format('M d, Y') }} • 
                                            <span class="font-semibold text-gray-900">${{ number_format($order->grand_total, 2) }}</span>
                                        </p>
                                    </div>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="ml-4 text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @if ($user->orders->count() > 5)
                            <div class="p-4 text-center bg-gray-50">
                                <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    View All Orders →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Account Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-base font-semibold text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Login</label>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Marketing Opt-in</label>
                        <div class="mt-1">
                            @if($user->marketing_opt_in)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Yes
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    No
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Actions
                    </h3>
                </div>
                <div class="p-6">
                    @if (!$user->isAdmin())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete User
                            </button>
                        </form>
                    @else
                        <p class="text-sm text-gray-500 text-center">Admin users cannot be deleted</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
