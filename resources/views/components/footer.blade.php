@props(['variant' => 'default'])

@php
    $footerClass = $variant === 'landing'
        ? 'site-footer border-top text-white bg-gradient-purple mt-auto'
        : 'site-footer bg-dark text-white mt-auto';
@endphp

<footer {{ $attributes->merge(['class' => $footerClass]) }} role="contentinfo">
    <div class="container py-4">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 text-center text-sm-start">
            <a href="{{ route('landing.home') }}" class="d-inline-flex align-items-center gap-2 text-white text-decoration-none opacity-90">
                <span class="d-inline-flex align-items-center justify-content-center rounded-2 bg-white bg-opacity-25 fw-bold text-white px-2 py-1 small">SN</span>
                <span class="fw-semibold">SafeNest</span>
            </a>
            <p class="mb-0 small opacity-75">&copy; {{ now()->year }} SafeNest. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</footer>
