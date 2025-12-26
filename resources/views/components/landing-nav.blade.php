<nav x-data="{ open: false }" class="bg-white border-b border-purple-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <a href="{{ route('landing.home') }}" class="flex items-center gap-2">
                    <x-shield-logo class="h-8 w-8 text-purple-600" />
                    <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'SafeNest') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('landing.home') }}" class="text-sm font-medium {{ request()->routeIs('landing.home') ? 'text-gray-900' : 'text-gray-600' }} hover:text-gray-900 transition">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('landing.about') }}" class="text-sm font-medium {{ request()->routeIs('landing.about') ? 'text-gray-900' : 'text-gray-600' }} hover:text-gray-900 transition">
                    {{ __('About') }}
                </a>
                <a href="{{ route('landing.products') }}" class="text-sm font-medium {{ request()->routeIs('landing.products') ? 'text-gray-900' : 'text-gray-600' }} hover:text-gray-900 transition">
                    {{ __('Products') }}
                </a>
            </div>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                <!-- Cart Button (for all users) -->
                <a href="{{ route('cart.index') }}" data-cart-link="desktop" class="relative inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md hover:border-gray-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
                        <path fill="currentColor" d="M7 4a1 1 0 0 0-1 .78l-2 9A1 1 0 0 0 4.97 15h12.06a1 1 0 0 0 .97-.78l1.6-7A1 1 0 0 0 18.62 6H7.8l.38-2H19a1 1 0 1 0 0-2H7Z"/>
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4"/>
                    </svg>
                    <span>{{ __('Cart') }}</span>
                    @php($cartCount = collect(session('cart.items', []))->sum('quantity'))
                    @if ($cartCount > 0)
                        <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-1 text-[11px] font-semibold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                
                @auth
                    <a href="{{ route('support.index') }}" class="relative px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 border border-gray-300 rounded-md hover:border-gray-400 transition">
                        {{ __('Support') }}
                        @if (isset($unreadSupportCount) && $unreadSupportCount > 0)
                            <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-1.5 text-[11px] font-semibold text-white">
                                {{ $unreadSupportCount > 99 ? '99+' : $unreadSupportCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition">
                        {{ __('Profile') }}
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition">
                        {{ __('Register') }}
                    </a>
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition">
                        {{ __('Login') }}
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" x-cloak class="md:hidden pb-4">
            <div class="flex flex-col gap-2">
                <a href="{{ route('landing.home') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('landing.home') ? 'text-gray-900 bg-gray-100' : 'text-gray-600' }} rounded-md">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('landing.about') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('landing.about') ? 'text-gray-900 bg-gray-100' : 'text-gray-600' }} rounded-md">
                    {{ __('About') }}
                </a>
                <a href="{{ route('landing.products') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('landing.products') ? 'text-gray-900 bg-gray-100' : 'text-gray-600' }} rounded-md">
                    {{ __('Products') }}
                </a>
                <!-- Cart (for all users) -->
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <a href="{{ route('cart.index') }}" data-cart-link="mobile" class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
                            <path fill="currentColor" d="M7 4a1 1 0 0 0-1 .78l-2 9A1 1 0 0 0 4.97 15h12.06a1 1 0 0 0 .97-.78l1.6-7A1 1 0 0 0 18.62 6H7.8l.38-2H19a1 1 0 1 0 0-2H7Z"/>
                            <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4"/>
                        </svg>
                        {{ __('Cart') }}
                        @php($cartCount = collect(session('cart.items', []))->sum('quantity'))
                        @if ($cartCount > 0)
                            <span class="ml-auto inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-2 text-[11px] font-semibold text-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ route('support.index') }}" class="relative px-3 py-2 text-sm font-medium text-gray-600 rounded-md flex items-center justify-between">
                            <span>{{ __('Support') }}</span>
                            @if (isset($unreadSupportCount) && $unreadSupportCount > 0)
                                <span class="inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-2 text-[11px] font-semibold text-white">
                                    {{ $unreadSupportCount > 99 ? '99+' : $unreadSupportCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('profile.edit') }}" class="px-3 py-2 text-sm font-medium text-gray-600 rounded-md">
                            {{ __('Profile') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

