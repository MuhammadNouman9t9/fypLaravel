<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SafeNest') }} - Owner</title>

    <x-layout-assets />
</head>
<body class="bg-light">
    <div class="d-flex min-vh-100">
        <aside class="bg-primary text-white d-flex flex-column" style="width: 16rem; min-height: 100vh;">
            <div class="border-bottom border-white border-opacity-25 p-4">
                <h1 class="h5 fw-bold mb-0">{{ config('app.name', 'SafeNest') }}</h1>
                <p class="small text-white-50 mb-0">Owner Panel</p>
            </div>

            <nav class="flex-grow-1 p-3 overflow-auto">
                <a href="{{ route('owner.dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none rounded px-3 py-2 mb-1 {{ request()->routeIs('owner.dashboard') ? 'bg-white bg-opacity-25' : '' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Dashboard
                </a>
            </nav>

            <div class="border-top border-white border-opacity-25 p-3">
                <form method="POST" action="{{ route('owner.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start p-2 d-flex align-items-center gap-2">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-grow-1 d-flex flex-column">
            <header class="bg-white border-bottom px-4 py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h2 class="h4 mb-0">@yield('title', 'Dashboard')</h2>
                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-secondary">{{ auth()->user()->name }}</span>
                        <a href="{{ route('landing.home') }}" class="small link-primary">View Site</a>
                    </div>
                </div>
            </header>

            <main class="flex-grow-1 p-4">
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

            <x-footer />
        </div>
    </div>
</body>
</html>
