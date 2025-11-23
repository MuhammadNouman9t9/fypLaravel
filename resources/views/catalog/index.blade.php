@extends('layouts.storefront')

@section('content')
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-12 lg:flex-row lg:items-center lg:justify-between lg:py-16">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-purple-600">{{ __('Secure your home with confidence') }}</p>
                <h1 class="mt-3 text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                    {{ __('Discover AI-powered smart security fitted to your SafeNest') }}
                </h1>
                <p class="mt-4 text-base leading-relaxed text-[#475569]">
                    {{ __('Build a safer home with curated devices, expert guidance, and intelligent recommendations driven by SafeNest AI.') }}
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="#catalog" class="rounded-full bg-purple-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-purple-700">
                        {{ __('Explore devices') }}
                    </a>
                    <a href="#how-it-works" class="rounded-full border border-purple-600 px-6 py-2.5 text-sm font-semibold text-purple-600 hover:bg-purple-600 hover:text-white">
                        {{ __('How SafeNest guides you') }}
                    </a>
                </div>
            </div>
            <div class="h-48 w-full overflow-hidden rounded-3xl bg-purple-600 text-white shadow-xl lg:h-56 lg:w-96">
                <div class="flex h-full flex-col justify-between p-6">
                    <div>
                        <h3 class="text-lg font-semibold">{{ __('Real-time home safety insights') }}</h3>
                        <p class="mt-2 text-sm text-white/70">
                            {{ __('Connect your SafeNest devices to monitor, detect, and respond to threats instantly.') }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-white/60">{{ __('Risk score') }}</p>
                            <p class="text-3xl font-semibold">{{ __('24 / 100') }}</p>
                        </div>
                        <span class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide">
                            {{ __('Low risk') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="catalog" class="mx-auto max-w-7xl px-4 py-12 lg:py-16">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-[#fcd34d] bg-[#fef3c7] px-6 py-4 text-sm font-medium text-[#92400e]">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Category Quick Filters -->
        <div class="mb-8">
            <h2 class="mb-4 text-lg font-semibold text-[#111827]">{{ __('Shop by Category') }}</h2>
            <div class="flex flex-wrap gap-3">
                <a 
                    href="{{ route('catalog.index', ['category' => 'cctv-cameras']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'cctv-cameras' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('CCTV Cameras') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'motion-detectors']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'motion-detectors' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Motion Detectors') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'smart-locks']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'smart-locks' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Smart Locks') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'digital-doorbells']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'digital-doorbells' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Digital Doorbells') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'alarm-systems']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'alarm-systems' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Alarm Systems') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'biometric-access-controls']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'biometric-access-controls' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Biometric Access Controls') }}
                </a>
                <a 
                    href="{{ route('catalog.index', ['category' => 'automation-hubs']) }}" 
                    class="rounded-lg border border-purple-200 bg-white px-5 py-2.5 text-sm font-medium text-[#374151] transition-all hover:border-purple-600 hover:bg-purple-600 hover:text-white {{ ($activeFilters['category'] ?? '') === 'automation-hubs' ? 'border-purple-600 bg-purple-600 text-white' : '' }}"
                >
                    {{ __('Automation & Hubs') }}
                </a>
            </div>
        </div>

        <form method="get" action="{{ route('catalog.index') }}" class="grid gap-8 lg:grid-cols-[280px,1fr]">
            <aside class="space-y-6">
                <div class="rounded-2xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-[#111827]">{{ __('Filters') }}</h2>

                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="search" class="text-sm font-medium text-[#1f2937]">{{ __('Search') }}</label>
                            <input
                                id="search"
                                name="search"
                                type="search"
                                value="{{ $activeFilters['search'] ?? '' }}"
                                placeholder="{{ __('Camera, smart lock, AI bundle...') }}"
                                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                            />
                        </div>

                        <div>
                            <label class="text-sm font-medium text-[#1f2937]">{{ __('Categories') }}</label>
                            <div class="mt-3 space-y-3">
                                @foreach ($categories as $category)
                                    <div>
                                        <label class="flex items-start gap-3 text-sm text-[#374151]">
                                            <input
                                                type="radio"
                                                name="category"
                                                value="{{ $category->slug }}"
                                                @checked(($activeFilters['category'] ?? null) === $category->slug)
                                                class="mt-1 h-4 w-4"
                                            />
                                            <span>{{ $category->name }}</span>
                                        </label>
                                        @if ($category->children->isNotEmpty())
                                            <div class="ms-6 mt-2 space-y-2">
                                                @foreach ($category->children as $child)
                                                    <label class="flex items-start gap-3 text-sm text-[#6b7280]">
                                                        <input
                                                            type="radio"
                                                            name="category"
                                                            value="{{ $child->slug }}"
                                                            @checked(($activeFilters['category'] ?? null) === $child->slug)
                                                            class="mt-1 h-4 w-4"
                                                        />
                                                        <span>{{ $child->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if (filled($activeFilters['category'] ?? null))
                                    <button
                                        type="submit"
                                        name="category"
                                        value=""
                                        class="text-xs font-medium text-[#2563eb] hover:text-[#1d4ed8]"
                                    >
                                        {{ __('Clear category filter') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label for="brand" class="text-sm font-medium text-[#1f2937]">{{ __('Brand') }}</label>
                            <select
                                id="brand"
                                name="brand"
                                class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                            >
                                <option value="">{{ __('Any brand') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand }}" @selected(($activeFilters['brand'] ?? null) === $brand)>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="min_price" class="text-sm font-medium text-[#1f2937]">{{ __('Min price') }}</label>
                                <input
                                    id="min_price"
                                    name="min_price"
                                    type="number"
                                    min="0"
                                    step="10"
                                    value="{{ $activeFilters['min_price'] ?? '' }}"
                                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                />
                            </div>
                            <div>
                                <label for="max_price" class="text-sm font-medium text-[#1f2937]">{{ __('Max price') }}</label>
                                <input
                                    id="max_price"
                                    name="max_price"
                                    type="number"
                                    min="0"
                                    step="10"
                                    value="{{ $activeFilters['max_price'] ?? '' }}"
                                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Property Assessment Section -->
                    <div class="mt-6 pt-6 border-t border-[#e5e7eb]">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-[#6366f1]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <h3 class="text-sm font-semibold text-[#111827]">{{ __('AI Property Assessment') }}</h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="property_type" class="text-sm font-medium text-[#1f2937]">{{ __('Property Type') }}</label>
                                <select
                                    id="property_type"
                                    name="property_type"
                                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                >
                                    <option value="">{{ __('Select property type') }}</option>
                                    <option value="apartment" {{ ($activeFilters['property_type'] ?? '') === 'apartment' ? 'selected' : '' }}>{{ __('Apartment') }}</option>
                                    <option value="condo" {{ ($activeFilters['property_type'] ?? '') === 'condo' ? 'selected' : '' }}>{{ __('Condo') }}</option>
                                    <option value="house" {{ ($activeFilters['property_type'] ?? '') === 'house' ? 'selected' : '' }}>{{ __('House') }}</option>
                                    <option value="villa" {{ ($activeFilters['property_type'] ?? '') === 'villa' ? 'selected' : '' }}>{{ __('Villa') }}</option>
                                    <option value="office" {{ ($activeFilters['property_type'] ?? '') === 'office' ? 'selected' : '' }}>{{ __('Office') }}</option>
                                    <option value="commercial" {{ ($activeFilters['property_type'] ?? '') === 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="property_size" class="text-sm font-medium text-[#1f2937]">{{ __('Property Size') }}</label>
                                <select
                                    id="property_size"
                                    name="property_size"
                                    class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                >
                                    <option value="">{{ __('Select size') }}</option>
                                    <option value="small" {{ ($activeFilters['property_size'] ?? '') === 'small' ? 'selected' : '' }}>{{ __('Small (< 1000 sq ft)') }}</option>
                                    <option value="medium" {{ ($activeFilters['property_size'] ?? '') === 'medium' ? 'selected' : '' }}>{{ __('Medium (1000-2500 sq ft)') }}</option>
                                    <option value="large" {{ ($activeFilters['property_size'] ?? '') === 'large' ? 'selected' : '' }}>{{ __('Large (2500-5000 sq ft)') }}</option>
                                    <option value="very_large" {{ ($activeFilters['property_size'] ?? '') === 'very_large' ? 'selected' : '' }}>{{ __('Very Large (> 5000 sq ft)') }}</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="entry_points" class="text-sm font-medium text-[#1f2937]">{{ __('Entry Points') }}</label>
                                    <input
                                        id="entry_points"
                                        name="entry_points"
                                        type="number"
                                        min="0"
                                        max="20"
                                        value="{{ $activeFilters['entry_points'] ?? '' }}"
                                        placeholder="0"
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                    />
                                </div>
                                <div>
                                    <label for="exit_points" class="text-sm font-medium text-[#1f2937]">{{ __('Exit Points') }}</label>
                                    <input
                                        id="exit_points"
                                        name="exit_points"
                                        type="number"
                                        min="0"
                                        max="20"
                                        value="{{ $activeFilters['exit_points'] ?? '' }}"
                                        placeholder="0"
                                        class="mt-2 w-full rounded-xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                    />
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="ai_recommend"
                                        value="1"
                                        {{ ($activeFilters['ai_recommend'] ?? false) ? 'checked' : '' }}
                                        class="h-4 w-4 text-[#6366f1] focus:ring-[#6366f1] border-gray-300 rounded"
                                    />
                                    <span class="text-sm text-[#374151]">{{ __('Get AI Recommendations') }}</span>
                                </label>
                                <p class="text-xs text-[#6b7280] mt-1 ml-6">{{ __('AI will suggest products based on your property profile') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="flex-1 rounded-full bg-[#111827] px-4 py-2 text-sm font-semibold text-white hover:bg-[#0b1120]">
                            {{ __('Apply filters') }}
                        </button>
                        @if (collect($activeFilters)->filter(fn ($value) => filled($value))->isNotEmpty())
                            <a href="{{ route('catalog.index') }}" class="rounded-full border border-[#d1d5db] px-4 py-2 text-sm font-medium text-[#374151] hover:border-[#111827] hover:text-[#111827]">
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-[#dbe1fb] bg-[#eef2ff] p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[#4338ca]">{{ __('Need expert guidance?') }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-[#312e81]">
                        {{ __('Speak with a SafeNest security consultant for custom AI-backed recommendations tailored to your property profile.') }}
                    </p>
                    <a href="{{ route('support.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#3730a3] hover:text-[#312e81]">
                        {{ __('Chat with an expert') }}
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </aside>

            <div class="space-y-6">
                <!-- AI Recommendations Banner -->
                @if(!empty($aiRecommendations))
                    <div class="bg-gradient-to-r from-[#6366f1] to-[#8b5cf6] rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold mb-2">{{ __('AI Recommendations') }}</h3>
                                <ul class="space-y-1 text-sm text-white/90">
                                    @foreach($aiRecommendations as $recommendation)
                                        <li class="flex items-start gap-2">
                                            <span class="text-white/70">•</span>
                                            <span>{{ $recommendation }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col gap-4 rounded-2xl border border-[#e5e7eb] bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-[#475569]">
                        {{ number_format($products->total()) }}
                        {{ \Illuminate\Support\Str::plural('security solution', $products->total()) }}
                        {{ __('available') }}
                        @if($activeFilters['ai_recommend'] ?? false)
                            <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-[#6366f1]/10 px-2 py-1 text-xs font-medium text-[#6366f1]">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                AI Recommended
                            </span>
                        @endif
                    </p>
                    <div class="flex items-center gap-3">
                        <label for="sort" class="text-sm font-medium text-[#1f2937]">{{ __('Sort by') }}</label>
                        <select
                            id="sort"
                            name="sort"
                            class="rounded-xl border border-[#d1d5db] bg-white px-3 py-2 text-sm focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10"
                            onchange="this.form.submit()"
                        >
                        <option value="latest" @selected(($activeFilters['sort'] ?? 'latest') === 'latest')>{{ __('Newest') }}</option>
                        <option value="price_asc" @selected(($activeFilters['sort'] ?? '') === 'price_asc')>{{ __('Price: Low to High') }}</option>
                        <option value="price_desc" @selected(($activeFilters['sort'] ?? '') === 'price_desc')>{{ __('Price: High to Low') }}</option>
                        <option value="top_rated" @selected(($activeFilters['sort'] ?? '') === 'top_rated')>{{ __('Top rated') }}</option>
                        <option value="quality" @selected(($activeFilters['sort'] ?? '') === 'quality')>{{ __('Best Quality') }}</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($products as $product)
                        @php
                            $inventory = $product->inventory;
                            $availableUnits = $inventory ? max(0, (int) $inventory->quantity_on_hand - (int) $inventory->quantity_reserved) : null;
                            $inStock = $availableUnits === null ? true : $availableUnits > 0;
                            $inCart = collect(session('cart.items', []))->firstWhere('product_id', $product->getKey());
                        @endphp
                        <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-[#e5e7eb] bg-white shadow-sm transition-all hover:-translate-y-1 hover:border-[#111827] hover:shadow-xl">
                            <a href="{{ route('catalog.show', $product) }}" class="relative block h-64 overflow-hidden bg-gradient-to-br from-[#f8fafc] to-white">
                                @if ($product->cover_image_url)
                                    <img 
                                        src="{{ $product->cover_image_url }}" 
                                        alt="{{ $product->name }}" 
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#e0e7ff] to-[#f3f4f6]">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-[#94a3b8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-xs text-[#94a3b8]">{{ __('Image coming soon') }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute left-0 top-0 flex flex-col gap-2 p-4">
                                    @if ($product->is_featured)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-[#1d4ed8] px-3 py-1 text-xs font-semibold text-white shadow-lg">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            {{ __('Featured') }}
                                        </span>
                                    @endif
                                    @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                                        @php
                                            $discount = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
                                        @endphp
                                        <span class="inline-flex items-center rounded-full bg-[#22c55e] px-3 py-1 text-xs font-bold text-white shadow-lg">
                                            -{{ $discount }}%
                                        </span>
                                    @endif
                                </div>
                                @if (! $inStock)
                                    <span class="absolute right-4 top-4 rounded-full bg-[#fee2e2] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#b91c1c] shadow-lg">
                                        {{ __('Out of stock') }}
                                    </span>
                                @endif
                            </a>
                            <div class="flex flex-1 flex-col gap-3 p-6">
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center rounded-full bg-[#eef2ff] px-3 py-1 text-xs font-semibold text-[#4338ca]">
                                        {{ $product->primary_category_name ?? __('Smart Security') }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-[#6b7280]">{{ $product->brand }}</span>
                                        @if($product->rating_average >= 4.5)
                                            <span class="inline-flex items-center gap-0.5 rounded-full bg-[#22c55e]/10 px-2 py-0.5 text-xs font-semibold text-[#22c55e]" title="Premium Quality">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Premium
                                            </span>
                                        @elseif($product->rating_average >= 4.0)
                                            <span class="inline-flex items-center gap-0.5 rounded-full bg-[#3b82f6]/10 px-2 py-0.5 text-xs font-semibold text-[#3b82f6]" title="High Quality">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                Quality
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('catalog.show', $product) }}" class="text-xl font-bold text-[#0f172a] transition hover:text-[#1d4ed8] line-clamp-2">
                                    {{ $product->name }}
                                </a>
                                <p class="line-clamp-2 text-sm leading-relaxed text-[#475569]">{{ $product->summary }}</p>
                                
                                <!-- Rating & Reviews -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg 
                                                    class="h-4 w-4 {{ $i <= round($product->rating_average) ? 'text-[#f97316] fill-current' : 'text-[#e5e7eb]' }}" 
                                                    fill="currentColor" 
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-xs font-medium text-[#6b7280]">
                                            {{ number_format($product->rating_average, 1) }} ({{ number_format($product->reviews_count) }})
                                        </span>
                                    </div>
                                    <!-- Quality Score -->
                                    @php
                                        $qualityScore = min(100, ($product->rating_average * 20) + ($product->reviews_count > 100 ? 20 : ($product->reviews_count / 5)));
                                        $qualityLevel = $qualityScore >= 90 ? 'Excellent' : ($qualityScore >= 75 ? 'Very Good' : ($qualityScore >= 60 ? 'Good' : 'Fair'));
                                    @endphp
                                    <div class="flex items-center gap-1 text-xs">
                                        <span class="font-semibold text-[#6366f1]">{{ round($qualityScore) }}%</span>
                                        <span class="text-[#6b7280]">{{ $qualityLevel }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto space-y-3 border-t border-[#e5e7eb] pt-4">
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-2xl font-bold text-[#111827]">${{ number_format($product->price, 2) }}</p>
                                        @if ($product->compare_at_price)
                                            <p class="text-sm text-[#94a3b8] line-through">${{ number_format($product->compare_at_price, 2) }}</p>
                                        @endif
                                    </div>
                                    @if($availableUnits !== null && $availableUnits > 0)
                                        <p class="text-xs text-[#22c55e] font-medium">
                                            <svg class="inline h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __(':count in stock', ['count' => $availableUnits]) }}
                                        </p>
                                    @endif
                                    <form action="{{ route('cart.store') }}" method="post" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->getKey() }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button 
                                            type="submit" 
                                            class="flex-1 rounded-xl bg-[#111827] px-4 py-2.5 text-sm font-semibold text-white transition-all hover:bg-[#0b1120] hover:shadow-lg disabled:cursor-not-allowed disabled:bg-[#cbd5f5] disabled:text-[#475569]" 
                                            @disabled(! $inStock)
                                        >
                                            {{ $inStock ? __('Add to Cart') : __('Unavailable') }}
                                        </button>
                                        @if ($inCart)
                                            <span class="inline-flex items-center gap-1 rounded-xl bg-[#dbeafe] px-3 py-2.5 text-xs font-semibold text-[#1d4ed8]">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ __('In Cart') }}
                                            </span>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-3xl border border-dashed border-[#cbd5f5] bg-[#eef2ff] p-12 text-center">
                            <h3 class="text-lg font-semibold text-[#312e81]">{{ __('No devices match your filters yet') }}</h3>
                            <p class="mt-2 text-sm text-[#4338ca]">
                                {{ __('Try adjusting the filters or connect with a SafeNest expert for a curated bundle built for your property.') }}
                            </p>
                            <a href="#experts" class="mt-4 inline-flex items-center justify-center rounded-full bg-white px-5 py-2 text-sm font-semibold text-[#312e81] shadow-sm hover:bg-[#e0e7ff]">
                                {{ __('Contact a security consultant') }}
                            </a>
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </form>
    </section>

    <section id="how-it-works" class="border-t border-[#e5e7eb] bg-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 lg:grid-cols-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">{{ __('How it works') }}</p>
                <h2 class="mt-3 text-2xl font-semibold text-[#0f172a]">{{ __('Smart security tailored to your home in three guided steps') }}</h2>
            </div>
            <div class="lg:col-span-2 grid gap-6 sm:grid-cols-3">
                @foreach ([
                    ['title' => __('Assess risk'), 'body' => __('Answer a few questions and let SafeNest AI build a personalized home safety profile.'), 'icon' => '🛡️'],
                    ['title' => __('Compare devices'), 'body' => __('Browse recommended devices with clear specs, ratings, and bundled savings.'), 'icon' => '🔍'],
                    ['title' => __('Install confidently'), 'body' => __('Chat with SafeNest experts for installation advice and on-going monitoring tips.'), 'icon' => '🤝'],
                ] as $step)
                    <div class="rounded-2xl border border-[#e5e7eb] bg-[#f9fafb] p-6 shadow-sm">
                        <div class="text-2xl">{{ $step['icon'] }}</div>
                        <h3 class="mt-3 text-lg font-semibold text-[#0f172a]">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-[#475569]">{{ $step['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="experts" class="bg-[#111827]">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 text-white lg:grid-cols-[1.2fr,0.8fr]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-[#60a5fa]">{{ __('Need installation support?') }}</p>
                <h2 class="mt-3 text-3xl font-semibold">{{ __('Talk with SafeNest-certified security professionals') }}</h2>
                <p class="mt-4 text-base leading-relaxed text-white/70">
                    {{ __('Book a complimentary consultation to review your AI risk report, validate product placement, and plan a seamless installation journey.') }}
                </p>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="#" class="rounded-full bg-white px-6 py-2.5 text-sm font-semibold text-[#111827] hover:bg-white/90">{{ __('Chat now') }}</a>
                    <a href="#" class="rounded-full border border-white px-6 py-2.5 text-sm font-semibold text-white hover:bg-white/10">{{ __('Schedule a call') }}</a>
                </div>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <h3 class="text-base font-semibold">{{ __('Included expertise') }}</h3>
                <ul class="mt-4 space-y-3 text-sm text-white/80">
                    <li class="flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">1</span>
                        {{ __('Placement recommendations using AI threat modeling') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">2</span>
                        {{ __('Connected device automation playbooks tailored to your lifestyle') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">3</span>
                        {{ __('Fraud and tamper detection alerts for three-month trial period') }}
                    </li>
                </ul>
                <p class="mt-6 text-xs text-white/60">{{ __('SafeNest consultants are verified professionals specializing in smart security systems and privacy-first installations.') }}</p>
            </div>
        </div>
    </section>
@endsection

