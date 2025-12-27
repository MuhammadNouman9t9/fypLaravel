<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SafeNest') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col">
            <x-landing-nav />

            <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="w-full sm:max-w-md">
                    <div class="bg-white shadow-lg rounded-2xl px-8 py-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <x-footer />
        </div>
    </body>
</html>
