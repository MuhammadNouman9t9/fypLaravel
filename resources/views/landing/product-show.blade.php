<x-landing-layout title="{{ $product->name }}">
    <div class="bg-light min-vh-100">
        <div class="container py-4 py-lg-5">
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('landing.home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('landing.products') }}" class="text-decoration-none">Products</a></li>
                    <li class="breadcrumb-item active text-truncate" style="max-width: 12rem;" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            @php
                $allImages = collect([$product->cover_image_url])->merge($product->media->pluck('file_path'))->filter()->unique()->values();
            @endphp

            <div class="row g-4 g-lg-5 mb-5">
                <div class="col-lg-6">
                    <div x-data="productCarousel({{ $allImages->count() }})">
                        @if ($allImages->count() > 0)
                            <div class="position-relative rounded-4 border shadow overflow-hidden bg-white ratio ratio-1x1">
                                @if ($allImages->count() > 1)
                                    {{-- Explicit left/right so arrows stay on correct sides (start/end utilities can fail with some CSS stacks) --}}
                                    <button
                                        type="button"
                                        @click="previous()"
                                        class="position-absolute border-0 shadow-sm"
                                        style="left: 12px; right: auto; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; border-radius: 50%; background: rgba(255,255,255,0.92); display: inline-flex; align-items: center; justify-content: center; z-index: 5;"
                                        aria-label="Previous"
                                    >
                                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        @click="next()"
                                        class="position-absolute border-0 shadow-sm"
                                        style="right: 12px; left: auto; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; border-radius: 50%; background: rgba(255,255,255,0.92); display: inline-flex; align-items: center; justify-content: center; z-index: 5;"
                                        aria-label="Next"
                                    >
                                        <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endif

                                <div class="position-absolute top-0 start-0 w-100 h-100">
                                    @foreach ($allImages as $index => $image)
                                        <img
                                            x-show="currentIndex === {{ $index }}"
                                            x-transition
                                            src="{{ $image }}"
                                            alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                            class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                        >
                                    @endforeach
                                </div>

                                @if ($product->is_featured)
                                    <div class="position-absolute top-0 start-0 m-3 z-2">
                                        <span class="badge bg-primary px-3 py-2">Featured</span>
                                    </div>
                                @endif

                                @if ($allImages->count() > 1)
                                    <div class="position-absolute bottom-0 end-0 m-3 z-2">
                                        <span class="badge bg-dark bg-opacity-75">
                                            <span x-text="currentIndex + 1"></span> / {{ $allImages->count() }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            @if ($allImages->count() > 1)
                                <div class="row g-2 mt-3">
                                    @foreach ($allImages as $index => $image)
                                        <div class="col-3">
                                            <button
                                                type="button"
                                                @click="goToSlide({{ $index }})"
                                                :class="currentIndex === {{ $index }} ? 'border-primary border-2' : 'border'"
                                                class="ratio ratio-1x1 p-0 overflow-hidden rounded-3 bg-light"
                                            >
                                                <img src="{{ $image }}" alt="" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="ratio ratio-1x1 rounded-4 border bg-light d-flex align-items-center justify-content-center text-secondary">
                                <svg style="width: 8rem; height: 8rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sticky-lg-top" style="top: 1rem;">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if ($product->categories->isNotEmpty())
                                <span class="badge text-bg-primary-subtle text-primary-emphasis border">
                                    {{ $product->primary_category_name ?? $product->categories->first()->name }}
                                </span>
                            @endif
                            <span class="badge bg-secondary">{{ $product->brand }}</span>
                        </div>
                        <h1 class="display-6 fw-bold text-dark mb-3">{{ $product->name }}</h1>
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span class="text-warning">★</span>
                            <span class="fw-bold">{{ number_format($product->rating_average, 1) }}</span>
                            <span class="text-secondary small">({{ number_format($product->reviews_count) }} reviews)</span>
                        </div>

                        <div class="card border-primary border-opacity-25 bg-primary bg-opacity-10 mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-baseline gap-3 mb-2">
                                    <span class="display-6 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                                    @if ($product->compare_at_price && $product->compare_at_price > $product->price)
                                        <span class="text-decoration-line-through text-secondary">${{ number_format($product->compare_at_price, 2) }}</span>
                                        <span class="badge bg-success">
                                            {{ round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100) }}% OFF
                                        </span>
                                    @endif
                                </div>
                                @if ($product->inventory && $product->inventory->quantity_on_hand > 0)
                                    <p class="text-success fw-semibold mb-0">In Stock ({{ $product->inventory->quantity_on_hand }} available)</p>
                                @else
                                    <p class="text-danger fw-semibold mb-0">Out of Stock</p>
                                @endif
                            </div>
                        </div>

                        @if ($product->summary)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h3 class="h6 fw-bold">Quick Overview</h3>
                                    <p class="text-secondary mb-0">{{ $product->summary }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                                        <label class="fw-semibold mb-0">Quantity:</label>
                                        <div class="input-group" style="max-width: 12rem;">
                                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQty()">−</button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->inventory->quantity_on_hand ?? 10 }}" class="form-control text-center">
                                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQty()">+</button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100" {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'disabled' : '' }}>
                                        {{ ($product->inventory && $product->inventory->quantity_on_hand <= 0) ? 'Out of Stock' : 'Add to Cart' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if ($product->warranty_period || $product->return_policy)
                            <div class="row g-3">
                                @if ($product->warranty_period)
                                    <div class="col-sm-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <p class="fw-bold mb-1">Warranty</p>
                                                <p class="small text-secondary mb-0">{{ $product->warranty_period }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($product->return_policy)
                                    <div class="col-sm-6">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <p class="fw-bold mb-1">Return Policy</p>
                                                <p class="small text-secondary mb-0">{{ $product->return_policy }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <h2 class="h3 fw-bold mb-4">Description</h2>
                            <div class="text-secondary lh-lg">
                                {!! nl2br(e($product->description ?? 'No description available.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($product->specifications->isNotEmpty())
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4 p-lg-5">
                                <h2 class="h3 fw-bold mb-4">Specifications</h2>
                                @foreach ($product->specifications->groupBy('group') as $group => $specs)
                                    <h3 class="h6 fw-bold border-bottom pb-2 mb-3">{{ $group }}</h3>
                                    <dl class="mb-4">
                                        @foreach ($specs as $spec)
                                            <div class="d-flex justify-content-between gap-2 py-2 border-bottom small">
                                                <dt class="text-secondary mb-0">{{ $spec->name }}</dt>
                                                <dd class="fw-semibold mb-0 text-end">{{ $spec->value }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if ($relatedProducts->isNotEmpty() || $recommendations->isNotEmpty())
                <div class="mb-5">
                    <h2 class="h3 fw-bold text-center mb-4">You May Also Like</h2>
                    <div class="row g-4">
                        @foreach (($recommendations->isNotEmpty() ? $recommendations : $relatedProducts) as $relatedProduct)
                            <div class="col-6 col-lg-3">
                                <article class="card h-100 shadow-sm hover-shadow">
                                    <a href="{{ route('landing.product.show', $relatedProduct) }}" class="text-decoration-none text-dark">
                                        <div class="ratio ratio-4x3 bg-light">
                                            @if ($relatedProduct->cover_image_url)
                                                <img src="{{ $relatedProduct->cover_image_url }}" alt="{{ $relatedProduct->name }}" class="object-fit-cover rounded-top">
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3 class="h6 line-clamp-2">{{ $relatedProduct->name }}</h3>
                                            <p class="fw-bold text-primary mb-0">${{ number_format($relatedProduct->price, 2) }}</p>
                                        </div>
                                    </a>
                                </article>
                            </div>
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
