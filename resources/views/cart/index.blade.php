<x-landing-layout title="Cart">
    <section class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
        @if (session('status'))
            <div class="mb-8 rounded-2xl border border-[#bbf7d0] bg-[#dcfce7] px-6 py-4 text-sm font-medium text-[#166534]">
                {{ session('status') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="rounded-3xl border border-dashed border-[#cbd5f5] bg-[#eef2ff] px-8 py-16 text-center">
                <h2 class="text-2xl font-semibold text-[#312e81]">{{ __('Your cart is feeling a little empty') }}</h2>
                <p class="mt-3 text-sm text-[#4338ca]">
                    {{ __('Explore SafeNest AI bundles and add smart devices that match your property profile.') }}
                </p>
                <a href="{{ route('landing.products') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-white px-6 py-2 text-sm font-semibold text-[#312e81] shadow-sm hover:bg-[#e0e7ff]">
                    {{ __('Browse devices') }}
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1.4fr,0.6fr]">
                <div class="space-y-6">
                    @foreach ($items as $item)
                        @php
                            /** @var \App\Models\Product $product */
                            $product = $item['product'];
                            $availableUnits = $item['available_units'];
                            $inStock = $item['in_stock'];
                        @endphp
                        <article class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                            <div class="flex flex-col gap-6 sm:flex-row">
                                <div class="flex-shrink-0 overflow-hidden rounded-2xl border border-[#e5e7eb] bg-[#f9fafb] sm:w-40">
                                    @if ($product->cover_image_url)
                                        <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="h-40 w-full object-cover">
                                    @else
                                        <div class="flex h-40 items-center justify-center text-xs text-[#94a3b8]">
                                            {{ __('Image coming soon') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-1 flex-col gap-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="space-y-1">
                                            <span class="text-lg font-semibold text-[#0f172a]">
                                                {{ $product->name }}
                                            </span>
                                            <p class="text-sm text-[#475569]">{{ $product->brand }}</p>
                                            <p class="text-xs uppercase tracking-wide text-[#6366f1]">
                                                {{ $product->primary_category_name ?? __('Smart security') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-base font-semibold text-purple-600">${{ number_format($product->price, 2) }}</p>
                                            @if ($product->compare_at_price)
                                                <p class="text-xs text-[#94a3b8] line-through">${{ number_format($product->compare_at_price, 2) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                            <label class="text-xs font-semibold uppercase tracking-wide text-[#64748b]" for="quantity-{{ $product->getKey() }}">
                                                {{ __('Quantity') }}
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <input
                                                    id="quantity-{{ $product->getKey() }}"
                                                    data-product-id="{{ $product->getKey() }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-slug="{{ $product->slug }}"
                                                    type="number"
                                                    min="1"
                                                    max="10"
                                                    value="{{ $item['quantity'] }}"
                                                    class="quantity-input w-24 rounded-2xl border border-purple-200 bg-white px-3 py-2 text-sm focus:border-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-600/10"
                                                >
                                            </div>
                                        </div>
                                        <div class="space-y-1 text-sm text-[#475569]">
                                            <p class="line-total" data-product-id="{{ $product->getKey() }}">
                                                {{ __('Line total: :amount', ['amount' => '$' . number_format($item['line_total'], 2)]) }}
                                            </p>
                                            @if ($availableUnits !== null)
                                                <p class="{{ $inStock ? 'text-[#16a34a]' : 'text-[#b91c1c]' }}">
                                                    {{ $inStock
                                                        ? __('In stock · :count units available', ['count' => $availableUnits])
                                                        : __('Ships when restocked') }}
                                                </p>
                                            @else
                                                <p class="text-[#2563eb]">{{ __('Ready to ship within 24 hours') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <form action="{{ route('cart.destroy', $product) }}" method="post" class="inline-flex">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="rounded-full border border-[#e11d48] px-4 py-2 text-xs font-semibold uppercase tracking-wide text-[#e11d48] hover:bg-[#fee2e2]">
                                                {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="space-y-6">
                    <div class="rounded-3xl border border-purple-600 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-[#0f172a]">{{ __('Order summary') }}</h2>
                        <dl class="mt-4 space-y-3 text-sm text-[#475569]">
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Subtotal') }}</dt>
                                <dd class="order-subtotal font-semibold text-purple-600">${{ number_format($summary['subtotal'], 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Estimated tax') }}</dt>
                                <dd class="order-tax font-semibold text-purple-600">${{ number_format($summary['tax'], 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between border-t border-dashed border-[#e5e7eb] pt-3">
                                <dt class="font-semibold text-purple-600">{{ __('Total due today') }}</dt>
                                <dd class="order-total text-xl font-semibold text-[#0f172a]">${{ number_format($summary['total'], 2) }}</dd>
                            </div>
                        </dl>
                        
                        <!-- Checkout Button -->
                        <div class="mt-6">
                            @auth
                                <form action="{{ route('cart.checkout') }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="w-full rounded-full bg-purple-600 px-4 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition"
                                    >
                                        {{ __('Proceed to Payment') }}
                                    </button>
                                </form>
                            @else
                                <a 
                                    href="{{ route('login') }}" 
                                    class="block w-full rounded-full bg-purple-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-purple-700 transition"
                                >
                                    {{ __('Login to Checkout') }}
                                </a>
                            @endauth
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
                // Update desktop cart badge
                const desktopCartLink = document.querySelector('a[data-cart-link="desktop"]');
                if (desktopCartLink) {
                    let badge = desktopCartLink.querySelector('span.rounded-full');
                    if (!badge && count > 0) {
                        badge = document.createElement('span');
                        badge.className = 'absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-blue-600 px-1 text-[11px] font-semibold text-white';
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
                        badge.className = 'ml-auto inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-blue-600 px-2 text-[11px] font-semibold text-white';
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
        });
    </script>
</x-landing-layout>


