@extends('admin.layout')

@section('title', 'User Details')

@section('content')
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="text-3xl font-bold text-gray-900">User Details</h2>
            </div>
            @if (!$user->isAdmin())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- User Profile Header Card -->
    <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700 rounded-2xl shadow-xl overflow-hidden mb-6">
        <div class="p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    @if($user->avatar_path)
                        <img src="{{ $user->avatar_path }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-white flex items-center justify-center">
                            <span class="text-3xl font-bold text-blue-600">{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    @if($user->email_verified_at && $user->phone_verified_at)
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $user->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-blue-100">
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
                <div class="flex flex-col items-end gap-3">
                    @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm text-white border border-white/30">
                            @if($role->name === 'admin')
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Spent</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_spent'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Pending Orders</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">First Name</label>
                            <p class="text-base font-medium text-gray-900">{{ $user->first_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Last Name</label>
                            <p class="text-base font-medium text-gray-900">{{ $user->last_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                            <div class="flex items-center gap-2">
                                <p class="text-base font-medium text-gray-900">{{ $user->email }}</p>
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone Number</label>
                            <div class="flex items-center gap-2">
                                <p class="text-base font-medium text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                                @if ($user->phone_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Verified
                                    </span>
                                @elseif($user->phone)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Account Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account Created</label>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-base font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $user->created_at->format('h:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Last Login</label>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-base font-medium text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y') : 'Never' }}</p>
                            </div>
                            @if($user->last_login_at)
                                <p class="text-sm text-gray-500 mt-1">{{ $user->last_login_at->diffForHumans() }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Two-Factor Authentication</label>
                            <div class="flex items-center gap-2">
                                @if($user->hasTwoFactorEnabled())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Enabled
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Disabled
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Marketing Opt-in</label>
                            <div class="flex items-center gap-2">
                                @if($user->marketing_opt_in)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        No
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Addresses Card -->
            @if ($user->addresses->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Addresses ({{ $user->addresses->count() }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($user->addresses as $address)
                                <div class="border-2 {{ $address->is_primary ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-gray-50' }} rounded-lg p-5 transition hover:shadow-md">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-semibold text-gray-900">{{ $address->label ?? ucfirst($address->type) }}</h4>
                                        @if ($address->is_primary)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                                Primary
                                            </span>
                                        @endif
                                    </div>
                                    <div class="space-y-1 text-sm text-gray-700">
                                        <p class="font-medium">{{ $address->line_one }}</p>
                                        @if($address->line_two)
                                            <p>{{ $address->line_two }}</p>
                                        @endif
                                        <p>{{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code){{ $address->postal_code }}@endif</p>
                                        <p class="font-semibold">{{ strtoupper($address->country_code) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">View Orders</p>
                            <p class="text-sm text-gray-500">{{ $stats['total_orders'] }} total orders</p>
                        </div>
                    </a>
                    <a href="mailto:{{ $user->email }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Send Email</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </a>
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Call User</p>
                                <p class="text-sm text-gray-500">{{ $user->phone }}</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Account Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Email Verification</span>
                        @if ($user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Phone Verification</span>
                        @if ($user->phone_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verified
                            </span>
                        @elseif($user->phone)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Not Set
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">2FA Status</span>
                        @if($user->hasTwoFactorEnabled())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Enabled
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Disabled
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
