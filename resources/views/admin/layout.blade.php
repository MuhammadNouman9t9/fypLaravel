<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SafeNest') }} - Admin</title>

    <x-layout-assets />
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex">
        <aside class="d-none d-lg-flex flex-column border-end bg-white" style="width: 18rem; min-height: 100vh;">
            <div class="border-bottom p-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary text-white fw-bold" style="width: 2.25rem; height: 2.25rem;">
                        {{ strtoupper(substr(config('app.name', 'SafeNest'), 0, 2)) }}
                    </span>
                    <div>
                        <div class="fw-semibold">{{ config('app.name', 'SafeNest') }}</div>
                        <div class="small text-secondary">Admin Panel</div>
                    </div>
                </div>
            </div>

            <nav class="flex-grow-1 p-3 overflow-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none small fw-medium mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Control panel
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none small fw-medium mb-1 {{ request()->routeIs('admin.users.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Manage users
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none small fw-medium mb-1 {{ request()->routeIs('admin.products.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    Manage products
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none small fw-medium mb-1 {{ request()->routeIs('admin.orders.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    View orders
                </a>
                <a href="{{ route('admin.analytics.index') }}"
                   class="d-flex align-items-center gap-2 rounded px-3 py-2 text-decoration-none small fw-medium mb-1 {{ request()->routeIs('admin.analytics.*') ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark' }}">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m4-14v14m4-10v10M7 7v14M3 11v10" /></svg>
                    View analytics
                </a>
            </nav>

            <div class="border-top p-3 mt-auto">
                <div class="small text-secondary mb-2">Signed in as <span class="text-dark fw-medium">{{ auth()->user()->name }}</span></div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-grow-1 d-flex flex-column min-vh-100">
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
