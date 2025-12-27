<x-landing-layout title="{{ $product->name }}">

    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('landing.home') }}" class="hover:text-gray-900">Home</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li><a href="{{ route('landing.products') }}" class="hover:text-gray-900">Products</a></li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                    <li class="text-gray-900 font-medium">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Product Images -->
                <div class="space-y-4">
                    <div class="aspect-square overflow-hidden rounded-2xl bg-gray-100 border border-gray-200">
                        @if ($product->cover_image_url)
                            <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-gray-400">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    @if ($product->media->count() > 1)
                        <div class="grid grid-cols-4 gap-4">
                            @foreach ($product->media->take(4) as $media)
                                <div class="aspect-square overflow-hidden rounded-lg border border-gray-200 cursor-pointer hover:border-purple-600 transition">
                                    <img src="{{ $media->file_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            @if ($product->categories->isNotEmpty())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $product->primary_category_name ?? $product->categories->first()->name }}
                                </span>
                            @endif
                            <span class="text-sm text-gray-600">{{ $product->brand }}</span>
                        </div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-lg font-semibold text-gray-900">{{ number_format($product->rating_average, 1) }}</span>
                                <span class="text-sm text-gray-600">({{ number_format($product->reviews_count) }} reviews)</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-b border-gray-200 py-6">
                        <div class="flex items-baseline gap-4">
                            <span class="text-4xl font-bold text-purple-600">${{ number_format($product->price, 2) }}</span>
                            @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                                <span class="text-xl text-gray-500 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                                <span class="text-sm font-medium text-green-600">
                                    {{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}% OFF
                                </span>
                            @endif
                        </div>
                        @if ($product->inventory && $product->inventory->quantity_on_hand > 0)
                            <p class="text-sm text-green-600 font-medium mt-2">
                                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                In Stock ({{ $product->inventory->quantity_on_hand }} available)
                            </p>
                        @else
                            <p class="text-sm text-red-600 font-medium mt-2">Out of Stock</p>
                        @endif
                    </div>

                    @if ($product->summary)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Overview</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $product->summary }}</p>
                        </div>
                    @endif

                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex items-center gap-4">
                            <label class="text-sm font-medium text-gray-700">Quantity:</label>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->inventory->quantity_on_hand ?? 10 }}" class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full rounded-full bg-purple-600 px-6 py-4 text-lg font-semibold text-white transition hover:bg-purple-700 shadow-lg hover:shadow-xl" {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'disabled' : '' }}>
                            {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </form>

                    <!-- Product Features -->
                    @if ($product->warranty_period || $product->return_policy)
                        <div class="border-t border-gray-200 pt-6 space-y-3">
                            @if ($product->warranty_period)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Warranty</p>
                                        <p class="text-sm text-gray-600">{{ $product->warranty_period }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($product->return_policy)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 14h3a2 2 0 002-2v-6a2 2 0 00-2-2h-3.5" />
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Return Policy</p>
                                        <p class="text-sm text-gray-600">{{ $product->return_policy }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Description & Specifications -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($product->description ?? 'No description available.')) !!}
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                @if ($product->specifications->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-200 p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Specifications</h2>
                        <div class="space-y-4">
                            @foreach ($product->specifications->groupBy('group') as $group => $specs)
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $group }}</h3>
                                    <dl class="space-y-2">
                                        @foreach ($specs as $spec)
                                            <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                                <dt class="text-sm text-gray-600">{{ $spec->name }}</dt>
                                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $spec->value }}</dd>
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
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">You May Also Like</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach (($recommendations->isNotEmpty() ? $recommendations : $relatedProducts) as $relatedProduct)
                            <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-[#e5e7eb] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                                <a href="{{ route('landing.product.show', $relatedProduct) }}" class="block">
                                    <div class="relative block h-48 overflow-hidden bg-[#f8fafc]">
                                        @if ($relatedProduct->cover_image_url)
                                            <img src="{{ $relatedProduct->cover_image_url }}" alt="{{ $relatedProduct->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col gap-2 p-4">
                                        <span class="text-sm font-semibold text-[#0f172a] line-clamp-2">{{ $relatedProduct->name }}</span>
                                        <p class="text-base font-semibold text-purple-600">${{ number_format($relatedProduct->price, 2) }}</p>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-landing-layout>

