<x-landing-layout title="{{ $product->name }}">
    <div class="bg-gradient-to-b from-gray-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <!-- Breadcrumb -->
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('landing.home') }}" class="text-gray-500 hover:text-purple-600 transition">Home</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('landing.products') }}" class="text-gray-500 hover:text-purple-600 transition">Products</a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium truncate max-w-xs">{{ $product->name }}</li>
                </ol>
            </nav>

            @php
                $allImages = collect([$product->cover_image_url])->merge($product->media->pluck('file_path'))->filter()->unique()->values();
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16">
                <!-- Product Images Carousel -->
                <div x-data="productCarousel({{ $allImages->count() }})" class="space-y-4">
                    
                    @if ($allImages->count() > 0)
                        <!-- Main Carousel Image -->
                        <div class="relative group aspect-square overflow-hidden rounded-3xl bg-gradient-to-br from-gray-100 to-gray-50 border-2 border-gray-200 shadow-xl">
                            <!-- Navigation Buttons -->
                            @if ($allImages->count() > 1)
                                <button @click="previous()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-all duration-300 hover:scale-110">
                                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-3 bg-white/90 backdrop-blur-sm rounded-full shadow-lg hover:bg-white transition-all duration-300 hover:scale-110">
                                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @endif

                            <!-- Image Display -->
                            <div class="relative w-full h-full">
                                @foreach ($allImages as $index => $image)
                                    <img 
                                        x-show="currentIndex === {{ $index }}"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        src="{{ $image }}" 
                                        alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    >
                                @endforeach
                            </div>

                            <!-- Featured Badge -->
                            @if ($product->is_featured)
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Featured
                                    </span>
                                </div>
                            @endif

                            <!-- Image Counter -->
                            @if ($allImages->count() > 1)
                                <div class="absolute bottom-4 right-4 z-10 px-3 py-1.5 bg-black/50 backdrop-blur-sm rounded-full text-white text-sm font-semibold">
                                    <span x-text="currentIndex + 1"></span> / <span>{{ $allImages->count() }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnail Navigation -->
                        @if ($allImages->count() > 1)
                            <div class="grid grid-cols-4 gap-3">
                                @foreach ($allImages as $index => $image)
                                    <button 
                                        @click="goToSlide({{ $index }})"
                                        :class="currentIndex === {{ $index }} ? 'border-purple-500 ring-2 ring-purple-200' : 'border-gray-200'"
                                        class="aspect-square overflow-hidden rounded-xl border-2 cursor-pointer transition-all duration-300 hover:border-purple-400 hover:shadow-lg group"
                                    >
                                        <img 
                                            src="{{ $image }}" 
                                            alt="{{ $product->name }} - Thumbnail {{ $index + 1 }}" 
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <!-- Fallback when no images -->
                        <div class="relative group aspect-square overflow-hidden rounded-3xl bg-gradient-to-br from-gray-100 to-gray-50 border-2 border-gray-200 shadow-xl">
                            <div class="flex h-full items-center justify-center text-gray-400">
                                <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-6 lg:sticky lg:top-8 lg:self-start">
                    <div>
                        <div class="flex items-center gap-3 mb-4 flex-wrap">
                            @if ($product->categories->isNotEmpty())
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 border border-purple-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $product->primary_category_name ?? $product->categories->first()->name }}
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-gray-100 text-gray-700">
                                {{ $product->brand }}
                            </span>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $product->name }}</h1>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center gap-2 bg-orange-50 px-4 py-2 rounded-full">
                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-lg font-bold text-gray-900">{{ number_format($product->rating_average, 1) }}</span>
                                <span class="text-sm text-gray-600">({{ number_format($product->reviews_count) }} reviews)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border-2 border-purple-100 p-6">
                        <div class="flex items-baseline gap-4 mb-3">
                            <span class="text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">${{ number_format($product->price, 2) }}</span>
                            @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="text-2xl text-gray-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                    {{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        @if ($product->inventory && $product->inventory->quantity_on_hand > 0)
                            <div class="flex items-center gap-2 text-green-700 font-semibold">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>In Stock ({{ $product->inventory->quantity_on_hand }} available)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-600 font-semibold">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <span>Out of Stock</span>
                            </div>
                        @endif
                    </div>

                    @if ($product->summary)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Quick Overview
                            </h3>
                            <p class="text-gray-700 leading-relaxed">{{ $product->summary }}</p>
                        </div>
                    @endif

                    <!-- Add to Cart Form -->
                    <div class="bg-white rounded-2xl border-2 border-gray-200 p-6 shadow-lg">
                        <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-bold text-gray-900">Quantity:</label>
                                <div class="flex items-center border-2 border-gray-300 rounded-xl overflow-hidden">
                                    <button type="button" onclick="decreaseQty()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition font-bold text-gray-700">−</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->inventory->quantity_on_hand ?? 10 }}" class="w-20 px-4 py-2 text-center font-semibold border-0 focus:ring-0 focus:outline-none">
                                    <button type="button" onclick="increaseQty()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 transition font-bold text-gray-700">+</button>
                                </div>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 text-lg font-bold text-white transition-all duration-300 hover:from-purple-700 hover:to-pink-700 shadow-xl hover:shadow-2xl transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed" {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'disabled' : '' }}>
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'Out of Stock' : 'Add to Cart' }}
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Product Features -->
                    @if ($product->warranty_period || $product->return_policy)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if ($product->warranty_period)
                                <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-green-100 rounded-lg">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">Warranty</p>
                                            <p class="text-sm text-gray-600">{{ $product->warranty_period }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($product->return_policy)
                                <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition">
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 14h3a2 2 0 002-2v-6a2 2 0 00-2-2h-3.5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">Return Policy</p>
                                            <p class="text-sm text-gray-600">{{ $product->return_policy }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Description & Specifications -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl border-2 border-gray-200 shadow-xl p-8 lg:p-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="h-1 w-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full"></div>
                            Description
                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($product->description ?? 'No description available.')) !!}
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                @if ($product->specifications->isNotEmpty())
                    <div class="bg-white rounded-3xl border-2 border-gray-200 shadow-xl p-8 lg:p-10">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="h-1 w-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full"></div>
                            Specifications
                        </h2>
                        <div class="space-y-6">
                            @foreach ($product->specifications->groupBy('group') as $group => $specs)
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 mb-3 pb-2 border-b-2 border-purple-100">{{ $group }}</h3>
                                    <dl class="space-y-3">
                                        @foreach ($specs as $spec)
                                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                                <dt class="text-sm font-medium text-gray-600">{{ $spec->name }}</dt>
                                                <dd class="text-sm font-bold text-gray-900 text-right">{{ $spec->value }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Related Products -->
            @if ($relatedProducts->isNotEmpty() || $recommendations->isNotEmpty())
                <div class="mb-12">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-1 flex-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full"></div>
                        <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">You May Also Like</h2>
                        <div class="h-1 flex-1 bg-gradient-to-r from-pink-600 to-purple-600 rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach (($recommendations->isNotEmpty() ? $recommendations : $relatedProducts) as $relatedProduct)
                            <article class="group flex h-full flex-col overflow-hidden rounded-3xl border-2 border-gray-200 bg-white shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:border-purple-300">
                                <a href="{{ route('landing.product.show', $relatedProduct) }}" class="block">
                                    <div class="relative block h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-50">
                                        @if ($relatedProduct->cover_image_url)
                                            <img src="{{ $relatedProduct->cover_image_url }}" alt="{{ $relatedProduct->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col gap-2 p-5">
                                        <span class="text-base font-bold text-gray-900 line-clamp-2 group-hover:text-purple-600 transition">{{ $relatedProduct->name }}</span>
                                        <p class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">${{ number_format($relatedProduct->price, 2) }}</p>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function productCarousel(totalImages) {
            return {
                currentIndex: 0,
                totalImages: totalImages,
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.totalImages;
                },
                previous() {
                    this.currentIndex = (this.currentIndex - 1 + this.totalImages) % this.totalImages;
                },
                goToSlide(index) {
                    this.currentIndex = index;
                }
            }
        }

        function increaseQty() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.getAttribute('max'));
            const current = parseInt(input.value);
            if (current < max) {
                input.value = current + 1;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantity');
            const current = parseInt(input.value);
            if (current > 1) {
                input.value = current - 1;
            }
        }
    </script>
</x-landing-layout>
