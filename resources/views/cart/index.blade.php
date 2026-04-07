<x-landing-layout title="Cart">
    <section class="container py-5">
        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h2 class="h4 mb-2">{{ __('Your cart is feeling a little empty') }}</h2>
                    <p class="text-muted mb-4">
                    {{ __('Explore SafeNest AI bundles and add smart devices that match your property profile.') }}
                    </p>
                    <a href="{{ route('landing.products') }}" class="btn btn-primary">
                        {{ __('Browse devices') }}
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    @foreach ($items as $item)
                        @php
                            /** @var \App\Models\Product $product */
                            $product = $item['product'];
                            $availableUnits = $item['available_units'];
                            $inStock = $item['in_stock'];
                        @endphp
                        <article class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-4 col-md-3">
                                    @if ($product->cover_image_url)
                                            <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="img-fluid rounded border w-100" style="height: 140px; object-fit: cover;">
                                    @else
                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small" style="height: 140px;">
                                            {{ __('Image coming soon') }}
                                        </div>
                                    @endif
                                </div>
                                    <div class="col-12 col-sm-8 col-md-9">
                                        <div class="d-flex flex-wrap justify-content-between gap-2">
                                            <div>
                                                <div class="fw-semibold fs-5">
                                                {{ $product->name }}
                                                </div>
                                                <div class="text-muted">{{ $product->brand }}</div>
                                                <div class="small text-primary text-uppercase">
                                                {{ $product->primary_category_name ?? __('Smart security') }}
                                                </div>
                                        </div>
                                            <div class="text-end">
                                                <div class="fw-semibold text-primary">${{ number_format($product->price, 2) }}</div>
                                            @if ($product->compare_at_price)
                                                    <div class="small text-muted text-decoration-line-through">${{ number_format($product->compare_at_price, 2) }}</div>
                                            @endif
                                        </div>
                                    </div>

                                        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mt-3">
                                            <div>
                                                <label class="form-label small mb-1" for="quantity-{{ $product->getKey() }}">
                                                {{ __('Quantity') }}
                                            </label>
                                                <div>
                                                <input
                                                    id="quantity-{{ $product->getKey() }}"
                                                    data-product-id="{{ $product->getKey() }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-slug="{{ $product->slug }}"
                                                    type="number"
                                                    min="1"
                                                    max="10"
                                                    value="{{ $item['quantity'] }}"
                                                        class="quantity-input form-control"
                                                        style="width: 96px;"
                                                >
                                                </div>
                                            </div>
                                            <div class="small text-muted">
                                            <p class="line-total" data-product-id="{{ $product->getKey() }}">
                                                {{ __('Line total: :amount', ['amount' => '$' . number_format($item['line_total'], 2)]) }}
                                            </p>
                                            @if ($availableUnits !== null)
                                                    <p class="{{ $inStock ? 'text-success' : 'text-danger' }} mb-0">
                                                    {{ $inStock
                                                        ? __('In stock · :count units available', ['count' => $availableUnits])
                                                        : __('Ships when restocked') }}
                                                </p>
                                            @else
                                                    <p class="text-primary mb-0">{{ __('Ready to ship within 24 hours') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                        <div class="mt-3">
                                            <form action="{{ route('cart.destroy', $product) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h2 class="h5 mb-3">{{ __('Order summary') }}</h2>
                            <dl class="small mb-0">
                                <div class="d-flex justify-content-between mb-2">
                                <dt>{{ __('Subtotal') }}</dt>
                                    <dd class="order-subtotal fw-semibold text-primary">${{ number_format($summary['subtotal'], 2) }}</dd>
                            </div>
                                <div class="d-flex justify-content-between mb-2">
                                <dt>{{ __('Estimated tax') }}</dt>
                                    <dd class="order-tax fw-semibold text-primary">${{ number_format($summary['tax'], 2) }}</dd>
                            </div>
                                <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <dt class="fw-semibold">{{ __('Total due today') }}</dt>
                                    <dd class="order-total fs-5 fw-semibold">${{ number_format($summary['total'], 2) }}</dd>
                            </div>
                        </dl>

                            <div class="mt-4">
                            @auth
                                <form action="{{ route('cart.checkout') }}" method="POST">
                                    @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Proceed to Payment') }}
                                    </button>
                                </form>
                            @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                    {{ __('Login to Checkout') }}
                                </a>
                            @endauth
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            quantityInputs.forEach(input => {
                let updateTimeout;
                
                input.addEventListener('change', function() {
                    const productId = this.dataset.productId;
                    const productSlug = this.dataset.productSlug;
                    const quantity = parseInt(this.value) || 1;
                    const price = parseFloat(this.dataset.productPrice);
                    
                    // Ensure minimum quantity
                    if (quantity < 1) {
                        this.value = 1;
                        return;
                    }
                    
                    // Update line total immediately
                    const lineTotalElement = document.querySelector(`.line-total[data-product-id="${productId}"]`);
                    if (lineTotalElement) {
                        const newLineTotal = (price * quantity).toFixed(2);
                        lineTotalElement.textContent = `Line total: $${parseFloat(newLineTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    }

                    // Update order summary
                    updateOrderSummary();

                    // Debounce the API call
                    clearTimeout(updateTimeout);
                    updateTimeout = setTimeout(() => {
                        // Update cart via AJAX
                        fetch(`/cart/${productSlug}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ quantity: quantity })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update order summary with server data
                                updateOrderSummaryFromServer(data.summary);
                                
                                // Update cart count in navbar if available
                                if (data.cart_count !== undefined) {
                                    updateCartCount(data.cart_count);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error updating cart:', error);
                            // Reload page on error
                            window.location.reload();
                        });
                    }, 500);
                });
            });

            function updateOrderSummary() {
                // Calculate totals from all line totals
                const lineTotals = document.querySelectorAll('.line-total');
                let subtotal = 0;
                
                lineTotals.forEach(element => {
                    const text = element.textContent;
                    const match = text.match(/\$([\d,]+\.\d{2})/);
                    if (match) {
                        subtotal += parseFloat(match[1].replace(/,/g, ''));
                    }
                });

                // Calculate tax (assuming 8% tax rate)
                const tax = subtotal * 0.08;
                const total = subtotal + tax;

                // Update summary display
                const subtotalElement = document.querySelector('.order-subtotal');
                const taxElement = document.querySelector('.order-tax');
                const totalElement = document.querySelector('.order-total');

                if (subtotalElement) {
                    subtotalElement.textContent = `$${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
                if (taxElement) {
                    taxElement.textContent = `$${tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
                if (totalElement) {
                    totalElement.textContent = `$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
            }

            function updateOrderSummaryFromServer(summary) {
                if (!summary) return;

                const subtotalElement = document.querySelector('.order-subtotal');
                const taxElement = document.querySelector('.order-tax');
                const totalElement = document.querySelector('.order-total');

                if (subtotalElement && summary.subtotal !== undefined) {
                    subtotalElement.textContent = `$${parseFloat(summary.subtotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
                if (taxElement && summary.tax !== undefined) {
                    taxElement.textContent = `$${parseFloat(summary.tax).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
                if (totalElement && summary.total !== undefined) {
                    totalElement.textContent = `$${parseFloat(summary.total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                }
            }

            function updateCartCount(count) {
                const cartLink = document.querySelector(`a[href="{{ route('cart.index') }}"]`);
                if (!cartLink) return;

                let badge = cartLink.querySelector('.badge');
                if (!badge && count > 0) {
                    badge = document.createElement('span');
                    badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary';
                    badge.style.fontSize = '0.65rem';
                    cartLink.appendChild(badge);
                }

                if (badge) {
                    if (count > 0) {
                        badge.textContent = count;
                    } else {
                        badge.remove();
                    }
                }
            }
        });
    </script>
</x-landing-layout>


