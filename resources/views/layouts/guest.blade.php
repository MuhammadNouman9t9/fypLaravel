<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SafeNest') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <x-layout-assets />
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex flex-column">
            <x-landing-nav />

            <div class="flex-grow-1 d-flex align-items-center justify-content-center py-5 px-3">
                <div class="w-100" style="max-width: 28rem;">
                    <div class="bg-white shadow rounded-4 p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <x-footer />
        </div>
    </body>
</html>
