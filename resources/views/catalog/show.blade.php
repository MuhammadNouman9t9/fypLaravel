@extends('layouts.storefront')

@section('content')
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 lg:py-12">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm text-[#6b7280]">
                <a href="{{ route('landing.home') }}" class="hover:text-purple-600">{{ __('Home') }}</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('catalog.index') }}" class="hover:text-purple-600">{{ __('Shop') }}</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                @if($product->primaryCategoryName)
                    <a href="{{ route('catalog.index', ['category' => $product->primaryCategoryName]) }}" class="hover:text-purple-600">{{ $product->primaryCategoryName }}</a>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
                <span class="text-purple-600">{{ $product->name }}</span>
            </nav>

            <div class="mt-8 grid gap-10 lg:grid-cols-[1.2fr,0.8fr]">
                <!-- Left Column: Images & Details -->
                <div class="space-y-8">
                    <!-- Image Gallery -->
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr),minmax(0,0.35fr)]">
                        <div class="group relative overflow-hidden rounded-3xl border border-[#e5e7eb] bg-[#f8fafc]">
                            @if ($product->media->isNotEmpty())
                                <img 
                                    id="main-image"
                                    src="{{ $product->media->first()->file_path }}" 
                                    alt="{{ $product->name }}" 
                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                >
                            @else
                                <div class="flex h-80 items-center justify-center text-sm text-[#94a3b8]">
                                    {{ __('High-resolution imagery coming soon') }}
                                </div>
                            @endif
                        </div>
                        <div class="hidden flex-col gap-4 lg:flex">
                            @foreach ($product->media->slice(0, 4) as $index => $media)
                                <button 
                                    onclick="changeMainImage('{{ $media->file_path }}')"
                                    class="overflow-hidden rounded-2xl border-2 border-transparent bg-[#f8fafc] transition-all hover:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600"
                                >
                                    <img 
                                        src="{{ $media->file_path }}" 
                                        alt="{{ $media->alt_text ?? $product->name }}" 
                                        class="h-28 w-full object-cover"
                                    >
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Key Features -->
                    <div class="rounded-3xl border border-[#e5e7eb] bg-gradient-to-br from-[#f8fafc] to-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-purple-600">{{ __('Key Features') }}</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 rounded-lg bg-[#dbeafe] p-2">
                                    <svg class="h-5 w-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-purple-600">{{ __('Advanced Security') }}</h3>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('State-of-the-art encryption and protection') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 rounded-lg bg-[#d1fae5] p-2">
                                    <svg class="h-5 w-5 text-[#047857]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-purple-600">{{ __('Fast Response') }}</h3>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Real-time alerts and notifications') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 rounded-lg bg-[#fef3c7] p-2">
                                    <svg class="h-5 w-5 text-[#92400e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-purple-600">{{ __('Smart Integration') }}</h3>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Works with all major smart home systems') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 rounded-lg bg-[#e9d5ff] p-2">
                                    <svg class="h-5 w-5 text-[#6b21a8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-[#111827]">{{ __('AI Powered') }}</h3>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Machine learning for better detection') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Overview -->
                    <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-lg font-semibold text-[#111827]">{{ __('Product Overview') }}</h2>
                        <div class="space-y-4 text-sm leading-relaxed text-[#475569]">
                            @if($product->summary)
                                <p class="text-base font-medium text-[#111827]">{{ $product->summary }}</p>
                            @endif
                            @if($product->description)
                                <div class="prose prose-sm max-w-none">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    @if ($product->specifications->isNotEmpty())
                        <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-[#111827]">{{ __('Technical Specifications') }}</h3>
                            <div class="space-y-4">
                                @php
                                    $groupedSpecs = $product->specifications->groupBy('group');
                                @endphp
                                @foreach($groupedSpecs as $group => $specs)
                                    @if($group)
                                        <div class="border-b border-[#e5e7eb] pb-4 last:border-0 last:pb-0">
                                            <h4 class="mb-3 text-sm font-semibold uppercase tracking-wide text-[#64748b]">{{ $group }}</h4>
                                            <dl class="grid gap-3 sm:grid-cols-2">
                                                @foreach($specs as $spec)
                                                    <div class="rounded-xl border border-[#f1f5f9] bg-[#f8fafc] p-3">
                                                        <dt class="text-xs font-semibold text-[#64748b]">{{ $spec->name }}</dt>
                                                        <dd class="mt-1 text-sm font-medium text-[#0f172a]">
                                                            {{ $spec->value }}@if($spec->unit) <span class="text-[#64748b]">{{ $spec->unit }}</span>@endif
                                                        </dd>
                                                    </div>
                                                @endforeach
                                            </dl>
                                        </div>
                                    @else
                                        <dl class="grid gap-3 sm:grid-cols-2">
                                            @foreach($specs as $spec)
                                                <div class="rounded-xl border border-[#f1f5f9] bg-[#f8fafc] p-3">
                                                    <dt class="text-xs font-semibold text-[#64748b]">{{ $spec->name }}</dt>
                                                    <dd class="mt-1 text-sm font-medium text-[#0f172a]">
                                                        {{ $spec->value }}@if($spec->unit) <span class="text-[#64748b]">{{ $spec->unit }}</span>@endif
                                                    </dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Installation & Support -->
                    <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-lg font-semibold text-[#111827]">{{ __('Installation & Support') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-[#111827]">{{ __('Professional Installation Available') }}</p>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Our certified technicians can install and configure your system') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-[#111827]">{{ __('24/7 Customer Support') }}</p>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Get help anytime with our dedicated support team') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-[#111827]">{{ __('Comprehensive Documentation') }}</p>
                                    <p class="mt-1 text-xs text-[#6b7280]">{{ __('Step-by-step guides and video tutorials included') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bundle Offer -->
                    <div class="rounded-3xl border border-dashed border-[#cbd5f5] bg-gradient-to-br from-[#eef2ff] to-[#f8fafc] p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 rounded-xl bg-[#312e81] p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#312e81]">{{ __('Need a Tailored Bundle?') }}</h3>
                                <p class="mt-2 text-sm text-[#4338ca]">
                                    {{ __('Combine this device with SafeNest AI bundles optimized for apartments, family homes, and large properties. Save up to 20% with curated packages.') }}
                                </p>
                                <a href="#experts" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#312e81] hover:text-[#1e1b4b] transition">
                                    {{ __('Request a Personalized Bundle') }}
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Product Info & Purchase -->
                @php
                    $inventory = $product->inventory;
                    $availableUnits = $inventory ? max(0, (int) $inventory->quantity_on_hand - (int) $inventory->quantity_reserved) : null;
                    $inStock = $availableUnits === null ? true : $availableUnits > 0;
                @endphp
                <aside class="space-y-6">
                    <!-- Product Info Card -->
                    <div class="sticky top-4 rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center rounded-full bg-[#dbeafe] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#1d4ed8]">
                                {{ $product->primary_category_name ?? __('Smart Security') }}
                            </span>
                            <div class="flex items-center gap-1 text-xs font-medium text-[#f97316]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span>{{ number_format($product->rating_average, 1) }}</span>
                                <span class="text-[#94a3b8]">({{ number_format($product->reviews_count) }})</span>
                            </div>
                        </div>

                        <h1 class="mt-4 text-2xl font-bold text-[#0f172a]">{{ $product->name }}</h1>
                        <p class="mt-2 text-sm font-medium text-[#475569]">{{ $product->brand }}</p>

                        <!-- SKU & Availability -->
                        <div class="mt-4 flex items-center gap-4 text-xs text-[#6b7280]">
                            <span><strong class="text-[#111827]">{{ __('SKU') }}:</strong> {{ $product->sku }}</span>
                            @if($inStock)
                                <span class="flex items-center gap-1 text-[#22c55e]">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('In Stock') }}
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-[#ef4444]">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Out of Stock') }}
                                </span>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="mt-6 space-y-2">
                            <p class="text-3xl font-bold text-[#111827]">${{ number_format($product->price, 2) }}</p>
                            @if ($product->compare_at_price)
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-[#94a3b8] line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                                    <span class="rounded-full bg-[#d1fae5] px-3 py-1 text-xs font-semibold text-[#047857]">
                                        {{ __('Save :amount', ['amount' => '$' . number_format($product->compare_at_price - $product->price, 2)]) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Purchase Form -->
                        <div class="mt-6 space-y-4">
                            @if ($errors->any())
                                <div class="rounded-2xl border border-[#fcd34d] bg-[#fef3c7] px-4 py-3 text-sm font-medium text-[#92400e]">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <form action="{{ route('cart.store') }}" method="post" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->getKey() }}">
                                <div>
                                    <label class="block text-sm font-semibold text-[#374151] mb-2" for="quantity">
                                        {{ __('Quantity') }}
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input
                                            id="quantity"
                                            name="quantity"
                                            type="number"
                                            min="1"
                                            max="{{ $availableUnits ?? 10 }}"
                                            value="1"
                                            class="w-24 rounded-xl border border-[#d1d5db] bg-white px-3 py-2.5 text-sm font-medium focus:border-[#111827] focus:outline-none focus:ring-2 focus:ring-[#111827]/10"
                                            @disabled(! $inStock)
                                        >
                                        @if ($availableUnits !== null)
                                            <span class="text-xs text-[#475569]">
                                                {{ __(':count units available', ['count' => $availableUnits]) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    type="submit" 
                                    class="w-full rounded-xl bg-[#111827] px-4 py-3.5 text-sm font-semibold text-white transition-all hover:bg-[#0b1120] hover:shadow-lg disabled:cursor-not-allowed disabled:bg-[#cbd5f5] disabled:text-[#475569]" 
                                    @disabled(! $inStock)
                                >
                                    {{ $inStock ? __('Add to Cart') : __('Currently Unavailable') }}
                                </button>
                            </form>
                            <button 
                                type="button" 
                                onclick="window.location.href='{{ route('support.index') }}'"
                                class="w-full rounded-xl border-2 border-[#111827] px-4 py-3.5 text-sm font-semibold text-[#111827] transition-all hover:bg-[#111827] hover:text-white"
                            >
                                {{ __('Schedule Install Consultation') }}
                            </button>
                            @if (! $inStock)
                                <div class="rounded-xl bg-[#fef3c7] border border-[#fcd34d] px-4 py-3 text-sm font-medium text-[#92400e]">
                                    <p class="font-semibold">{{ __('Restock Coming Soon') }}</p>
                                    <p class="mt-1 text-xs">{{ __('Leave your email with our experts to be notified first.') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Benefits -->
                        <ul class="mt-6 space-y-3 border-t border-[#e5e7eb] pt-6 text-sm text-[#475569]">
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ __('Free 2-day shipping across major cities') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ __('Warranties up to :period', ['period' => $product->warranty_period ?? __('24 months')]) }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ __('Risk-free returns within 30 days') }}</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="h-5 w-5 flex-shrink-0 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>{{ __('24/7 customer support') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Related Products -->
                    @if ($relatedProducts->isNotEmpty())
                        <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                            <h3 class="mb-4 text-lg font-semibold text-[#111827]">{{ __('You May Also Like') }}</h3>
                            <div class="space-y-4">
                                @foreach ($relatedProducts->take(3) as $related)
                                    <a href="{{ route('catalog.show', $related) }}" class="flex gap-4 rounded-2xl border border-[#e5e7eb] bg-[#f9fafb] p-4 transition-all hover:border-[#111827] hover:shadow-md">
                                        <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-white shadow-sm">
                                            @if ($related->cover_image_url)
                                                <img src="{{ $related->cover_image_url }}" alt="{{ $related->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-[10px] text-[#94a3b8]">
                                                    {{ __('Preview') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-[#0f172a] line-clamp-1">{{ $related->name }}</p>
                                            <p class="mt-1 text-xs font-medium text-[#1d4ed8]">{{ __('From :price', ['price' => '$' . number_format($related->price, 2)]) }}</p>
                                            <p class="mt-1 text-xs text-[#475569] line-clamp-2">{{ $related->summary }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </section>

    <!-- Expert Consultation Section -->
    <section id="experts" class="border-t border-[#e5e7eb] bg-gradient-to-br from-[#111827] to-[#1f2937]">
        <div class="mx-auto max-w-7xl px-4 py-16 text-white">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur-lg">
                <div class="text-center">
                    <h2 class="text-3xl font-bold">{{ __('Pair This Device with SafeNest Concierge Support') }}</h2>
                    <p class="mt-4 text-sm leading-relaxed text-white/80 max-w-2xl mx-auto">
                        {{ __('SafeNest experts help calibrate detection zones, automate lighting, and configure AI monitoring for proactive response time under 10 seconds.') }}
                    </p>
                </div>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('support.index') }}" class="rounded-full bg-white px-8 py-3 text-sm font-semibold text-[#111827] transition-all hover:bg-white/90 hover:shadow-lg">
                        {{ __('Connect Me with an Expert') }}
                    </a>
                    <a href="{{ route('support.index') }}" class="rounded-full border-2 border-white px-8 py-3 text-sm font-semibold text-white transition-all hover:bg-white/10">
                        {{ __('Download Installation Checklist') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        function changeMainImage(imageSrc) {
            document.getElementById('main-image').src = imageSrc;
        }
    </script>
@endsection
