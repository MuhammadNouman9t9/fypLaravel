<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing.home') }}">
            <x-shield-logo class="text-primary" style="width: 2rem; height: 2rem;" />
            <span class="fw-bold text-dark">{{ config('app.name', 'SafeNest') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNavbar" aria-controls="landingNavbar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="landingNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.home') ? 'active' : '' }}" href="{{ route('landing.home') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing.products') ? 'active' : '' }}" href="{{ route('landing.products') }}">{{ __('Shop') }}</a>
                </li>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm position-relative d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 1rem; height: 1rem;">
                        <path fill="currentColor" d="M7 4a1 1 0 0 0-1 .78l-2 9A1 1 0 0 0 4.97 15h12.06a1 1 0 0 0 .97-.78l1.6-7A1 1 0 0 0 18.62 6H7.8l.38-2H19a1 1 0 1 0 0-2H7Z"/>
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4"/>
                    </svg>
                    {{ __('Cart') }}
                    @php($cartCount = collect(session('cart.items', []))->sum('quantity'))
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.65rem;">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Admin link is intentionally NOT shown to guests. Admins access /admin/login directly. --}}
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">{{ __('Admin') }}</a>
                    @endif
                @endauth

                @auth
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">{{ __('Profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm w-100">{{ __('Logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
