<x-landing-layout title="Products">
    <div x-data="{ filterOpen: false }" class="bg-white min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <!-- Products Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 border border-purple-200 text-purple-600 rounded-full mb-6">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-semibold uppercase">Products</span>
                </div>

                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">
                    Smart Security Products
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Browse our AI-powered security solutions tailored for your home
                </p>
            </div>

            <!-- Category Filter Buttons -->
            <div class="mb-8 flex flex-wrap items-center gap-3">
                <!-- Filter Icon -->
                <button 
                    @click="filterOpen = !filterOpen" 
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 bg-white transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter</span>
                </button>

                <!-- Category Buttons -->
                <a 
                    href="{{ route('landing.products') }}" 
                    class="px-4 py-2 rounded-lg font-medium transition {{ !request('category') ? 'bg-purple-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                >
                    All Products
                </a>
                @foreach($categories as $cat)
                    <a 
                        href="{{ route('landing.products', ['category' => $cat->slug]) }}" 
                        class="px-4 py-2 rounded-lg font-medium transition {{ request('category') === $cat->slug ? 'bg-purple-600 text-white' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50' }}"
                    >
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Advanced Filters Panel -->
            <div 
                x-show="filterOpen" 
                x-cloak
                x-transition
                class="bg-gray-100 rounded-lg p-6 mb-8"
            >
                <form method="GET" action="{{ route('landing.products') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                    <!-- Preserve category if set -->
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <!-- Company/Brand Filter -->
                    <div class="flex-1 w-full">
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <div class="relative">
                            <select name="company" id="company" class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 appearance-none bg-white pr-8">
                                <option value="">All Companies</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('company') === $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Max Quantity Filter -->
                    <div class="flex-1 w-full">
                        <label for="max_quantity" class="block text-sm font-medium text-gray-700 mb-2">Max Quantity</label>
                        <input 
                            type="number" 
                            name="max_quantity" 
                            id="max_quantity" 
                            value="{{ request('max_quantity') }}"
                            placeholder="Any"
                            min="0"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 bg-white"
                        />
                    </div>

                    <!-- Filter Button -->
                    <div class="w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filter
                        </button>
                    </div>

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['company', 'max_quantity']))
                        <div class="w-full sm:w-auto">
                            <a href="{{ route('landing.products', request()->only('category')) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition bg-white">
                                Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <a href="{{ route('catalog.show', $product) }}" class="relative block h-56 overflow-hidden bg-[#f8fafc]">
                                @if ($product->cover_image_url)
                                    <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-[#94a3b8]">
                                        {{ __('Image coming soon') }}
                                    </div>
                                @endif
                            </a>
                            <div class="flex flex-1 flex-col gap-3 p-5">
                                <div class="flex items-center justify-between text-xs uppercase tracking-wide text-[#6366f1]">
                                    <span>{{ $product->primary_category_name ?? __('All smart security') }}</span>
                                    <span>{{ $product->brand }}</span>
                                </div>
                                <a href="{{ route('catalog.show', $product) }}" class="text-lg font-semibold text-[#0f172a] hover:text-purple-600">{{ $product->name }}</a>
                                <p class="line-clamp-2 text-sm leading-relaxed text-[#475569]">{{ $product->summary ?? \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                                <div class="mt-auto space-y-3 pt-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-semibold text-purple-600">${{ number_format($product->price, 2) }}</p>
                                        <div class="flex items-center gap-1 text-xs font-medium text-[#f97316]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span>{{ number_format($product->rating_average, 1) }}</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('cart.store') }}" method="POST" class="add-to-cart-form flex items-center justify-between gap-3" data-product-name="{{ $product->name }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="flex-1 rounded-full bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-700">
                                            {{ __('Add to cart') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination with Arrows -->
                @if($products->hasPages())
                    <div class="flex justify-end items-center gap-4">
                        <!-- Previous Button -->
                        @if($products->onFirstPage())
                            <button disabled class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed bg-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 bg-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        <!-- Page Info -->
                        <span class="text-sm text-gray-600">
                            Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                        </span>

                        <!-- Next Button -->
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 bg-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <button disabled class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed bg-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg mb-4">No products found matching your filters.</p>
                    <a href="{{ route('landing.products') }}" class="text-purple-600 hover:text-purple-700 font-semibold">Clear filters</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Cart Notification Popup -->
    <div id="cart-notification" class="fixed top-20 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 hidden transform transition-all duration-300 -translate-x-full">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div>
                <p class="font-semibold" id="cart-notification-text">Item added to cart!</p>
                <p class="text-sm text-green-100">Cart updated successfully</p>
            </div>
        </div>
    </div>

    <script>
        // Handle add to cart form submission
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const productName = this.dataset.productName;
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                // Disable button
                submitButton.disabled = true;
                submitButton.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (response.headers.get('content-type')?.includes('application/json')) {
                        return response.json();
                    }
                    return { redirect: response.url };
                })
                .then(data => {
                    // Show notification
                    const notification = document.getElementById('cart-notification');
                    const notificationText = document.getElementById('cart-notification-text');
                    notificationText.textContent = productName + ' added to cart!';
                    notification.classList.remove('hidden', '-translate-x-full');
                    notification.classList.add('translate-x-0');
                    
                    // Update cart count in navbar
                    if (data.cart_count) {
                        updateCartCount(data.cart_count);
                    }
                    
                    // Hide notification after 3 seconds
                    setTimeout(() => {
                        notification.classList.remove('translate-x-0');
                        notification.classList.add('-translate-x-full');
                        setTimeout(() => {
                            notification.classList.add('hidden');
                        }, 300);
                    }, 3000);
                    
                    // Redirect after showing notification
                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                    // Fallback to normal form submission
                    this.submit();
                });
            });
        });
        
        function updateCartCount(count) {
            // Update desktop cart badge
            const desktopCartLink = document.querySelector('a[data-cart-link="desktop"]');
            if (desktopCartLink) {
                let badge = desktopCartLink.querySelector('span.rounded-full');
                if (!badge && count > 0) {
                    badge = document.createElement('span');
                    badge.className = 'absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-1 text-[11px] font-semibold text-white';
                    desktopCartLink.appendChild(badge);
                }
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = '';
                    } else {
                        badge.remove();
                    }
                }
            }

            // Update mobile cart badge
            const mobileCartLink = document.querySelector('a[data-cart-link="mobile"]');
            if (mobileCartLink) {
                let badge = mobileCartLink.querySelector('span.rounded-full') || mobileCartLink.querySelector('span.ml-auto');
                if (!badge && count > 0) {
                    badge = document.createElement('span');
                    badge.className = 'ml-auto inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-purple-600 px-2 text-[11px] font-semibold text-white';
                    mobileCartLink.appendChild(badge);
                }
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = '';
                    } else {
                        badge.remove();
                    }
                }
            }
        }
    </script>
</x-landing-layout>
