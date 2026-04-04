<nav class="navbar navbar-expand-sm navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing.home') }}">
            <x-shield-logo class="text-primary" style="width: 2rem; height: 2rem;" />
            <span class="fw-bold text-dark">{{ config('app.name', 'SafeNest') }}</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAuth" aria-controls="navbarAuth" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAuth">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item">
                    <x-nav-link :href="route('landing.home')" :active="request()->routeIs('landing.home')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </li>
            </ul>

            <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                <a href="{{ route('cart.index') }}" data-cart-link="desktop" class="btn btn-outline-secondary btn-sm position-relative d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 1.25rem; height: 1.25rem;">
                        <path fill="currentColor" d="M7 4a1 1 0 0 0-1 .78l-2 9A1 1 0 0 0 4.97 15h12.06a1 1 0 0 0 .97-.78l1.6-7A1 1 0 0 0 18.62 6H7.8l.38-2H19a1 1 0 1 0 0-2H7Z"/>
                        <path fill="currentColor" d="M7 18a2 2 0 1 0 0 4a2 2 0 0 0 0-4m10 0a2 2 0 1 0 0 4a2 2 0 0 0 0-4"/>
                    </svg>
                    <span>{{ __('Cart') }}</span>
                    @php($cartCount = collect(session('cart.items', []))->sum('quantity'))
                    @if ($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.65rem;">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100 w-sm-auto">{{ __('Logout') }}</button>
                </form>
            </div>

            <div class="mt-3 pt-3 border-top d-sm-none">
                <div class="small text-secondary">{{ Auth::user()->name }}</div>
                <div class="small text-muted mb-2">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</nav>
