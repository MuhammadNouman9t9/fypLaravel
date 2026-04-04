<x-landing-layout title="Products">
    <div class="bg-white py-5">
        <div class="container">
            <div class="mb-4">
                <h1 class="h2 fw-semibold text-dark">{{ __('Products') }}</h1>
                <p class="text-secondary small mt-1 mb-0">{{ __('Browse smart security products and add them to your cart.') }}</p>
            </div>

            <form method="GET" action="{{ route('landing.products', absolute: false) }}" class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="category" class="form-label">{{ __('Category') }}</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">{{ __('All categories') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="company" class="form-label">{{ __('Company') }}</label>
                            <select id="company" name="company" class="form-select">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand }}" @selected(request('company') === $brand)>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="max_quantity" class="form-label">{{ __('Max quantity') }}</label>
                            <input
                                id="max_quantity"
                                name="max_quantity"
                                type="number"
                                min="0"
                                value="{{ request('max_quantity') }}"
                                placeholder="{{ __('Any') }}"
                                class="form-control"
                            />
                        </div>

                        <div class="col-12 d-flex flex-wrap align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                            <a href="{{ route('landing.products', absolute: false) }}" class="btn btn-link text-decoration-none">{{ __('Clear') }}</a>
                        </div>
                    </div>
                </div>
            </form>

            @if ($products->count() === 0)
                <div class="card">
                    <div class="card-body text-center py-5">
                        <p class="text-secondary mb-0">{{ __('No products found.') }}</p>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <article class="card h-100 shadow-sm">
                                <a href="{{ route('landing.product.show', $product) }}" class="text-decoration-none text-dark">
                                    <div class="ratio ratio-4x3 bg-light">
                                        @if ($product->cover_image_url)
                                            <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="object-fit-cover rounded-top" />
                                        @else
                                            <div class="d-flex align-items-center justify-content-center text-secondary small">{{ __('No image') }}</div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start gap-2 small text-secondary mb-2">
                                            <span class="text-truncate">{{ $product->brand ?: __('—') }}</span>
                                            <span class="flex-shrink-0 fw-semibold text-primary">${{ number_format($product->price, 2) }}</span>
                                        </div>
                                        <h3 class="h6 fw-semibold line-clamp-2">{{ $product->name }}</h3>
                                        <p class="small text-secondary line-clamp-2 mb-0">
                                            {{ $product->summary ?? \Illuminate\Support\Str::limit($product->description, 90) }}
                                        </p>
                                    </div>
                                </a>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <form action="{{ route('cart.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary w-100">{{ __('Add to cart') }}</button>
                                    </form>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-landing-layout>
