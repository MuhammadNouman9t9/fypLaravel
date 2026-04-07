<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SafeNest') }} - Admin</title>

    <x-layout-assets />
    <style>
        /* Tailwind-like icon sizing helpers for admin Blade SVGs */
        .w-3 { width: .75rem; }
        .h-3 { height: .75rem; }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
        .w-5 { width: 1.25rem; }
        .h-5 { height: 1.25rem; }
        .w-6 { width: 1.5rem; }
        .h-6 { height: 1.5rem; }
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .mr-1 { margin-right: .25rem; }
        .mr-2 { margin-right: .5rem; }
    </style>
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex flex-column">
        <div class="d-flex flex-column min-vh-100">
            <header class="border-bottom bg-white">
                <div class="container-fluid py-3 px-4">
                    <h2 class="h5 mb-0 fw-semibold text-truncate">@yield('title', 'Control panel')</h2>
                    <p class="small text-secondary mb-0">Administration</p>
                </div>
            </header>

            <main class="flex-grow-1 container-fluid py-4 px-4">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
