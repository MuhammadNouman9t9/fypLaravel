<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SafeNest') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f9fafb] text-[#111827] antialiased font-[Instrument_Sans]">
        <div class="flex flex-col min-h-screen">
            <x-landing-nav />

            <main class="flex-1">
                @yield('content')
            </main>

            <footer class="bg-gradient-to-b from-[#111827] to-[#0f172a] text-white">
                <div class="mx-auto max-w-7xl px-4 py-16 lg:py-20">
                    <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Company Info -->
                        <div class="space-y-6 lg:col-span-1">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 text-white text-lg font-bold shadow-lg">SN</span>
                                <span class="text-2xl font-bold text-white">{{ config('app.name', 'SafeNest') }}</span>
                            </div>
                            <p class="text-sm leading-relaxed text-gray-300">
                                {{ __('AI-powered intelligent security solutions for your home and business. Protect what matters most with cutting-edge technology.') }}
                            </p>
                            <div class="flex items-center gap-4 pt-2">
                                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-white/20 hover:scale-110">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-white/20 hover:scale-110">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="#" class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-white/20 hover:scale-110">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.419-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.057-1.274-.07-1.649-.07-4.844 0-3.196.016-3.586.074-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-white">{{ __('Quick Links') }}</h3>
                            <ul class="space-y-4 text-sm">
                                <li>
                                    <a href="{{ route('landing.products') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Shop') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('pages.projects') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Projects') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('landing.about') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('About Us') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('support.index') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Security Experts') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cart.index') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Shopping Cart') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-white">{{ __('Support') }}</h3>
                            <ul class="space-y-4 text-sm">
                                <li>
                                    <a href="{{ route('support.index') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        {{ __('Contact Support') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Help Center') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ __('Documentation') }}
                                    </a>
                                </li>
                                @if (Route::has('login'))
                                    @auth
                                        <li>
                                            <a href="{{ route('profile.edit') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                {{ __('Dashboard') }}
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ route('login') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                                {{ __('Log in') }}
                                            </a>
                                        </li>
                                        @if (Route::has('register'))
                                            <li>
                                                <a href="{{ route('register') }}" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                    {{ __('Sign up') }}
                                                </a>
                                            </li>
                                        @endif
                                    @endauth
                                @endif
                            </ul>
                        </div>

                        <!-- Legal -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-bold text-white">{{ __('Legal') }}</h3>
                            <ul class="space-y-4 text-sm">
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Privacy Policy') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Terms of Service') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Cookie Policy') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-300 transition hover:text-white hover:translate-x-1 inline-flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        {{ __('Refund Policy') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="mt-12 border-t border-white/10 pt-8">
                        <div class="flex flex-col gap-4 text-sm text-gray-400 sm:flex-row sm:items-center sm:justify-between">
                            <p>&copy; {{ now()->year }} {{ config('app.name', 'SafeNest') }}. {{ __('All rights reserved.') }}</p>
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>{{ __('Powered by AI-driven security technology') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>

