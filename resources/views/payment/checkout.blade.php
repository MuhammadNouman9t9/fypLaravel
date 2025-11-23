<x-landing-layout title="Checkout">
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">{{ __('Secure Payment') }}</p>
                    <h1 class="text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                        {{ __('Complete your order') }}
                    </h1>
                    <p class="text-sm leading-relaxed text-[#475569]">
                        {{ __('Order #:order_number', ['order_number' => $order->order_number]) }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
        @if (session('status'))
            <div class="mb-8 rounded-2xl border border-[#bbf7d0] bg-[#dcfce7] px-6 py-4 text-sm font-medium text-[#166534]">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-8 rounded-2xl border border-[#fecaca] bg-[#fee2e2] px-6 py-4 text-sm font-medium text-[#991b1b]">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[1.4fr,0.6fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-[#0f172a] mb-6">{{ __('Payment Information') }}</h2>
                    
                    <form id="payment-form" class="space-y-6">
                        <div id="card-element" class="rounded-lg border border-[#d1d5db] bg-white p-4">
                            <!-- Stripe Elements will create form elements here -->
                        </div>
                        
                        <div id="card-errors" role="alert" class="hidden text-sm text-[#b91c1c] mt-2"></div>
                        
                        <div class="flex items-center gap-2 text-sm text-[#475569]">
                            <svg class="h-5 w-5 text-[#16a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>{{ __('Your payment information is secure and encrypted') }}</span>
                        </div>
                        
                        <button 
                            type="submit" 
                            id="submit-button"
                            class="w-full rounded-full bg-purple-600 px-6 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span id="button-text">{{ __('Pay $:amount', ['amount' => number_format($order->grand_total, 2)]) }}</span>
                            <span id="spinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white inline-block ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </form>
                </div>

                <div class="rounded-3xl border border-[#e5e7eb] bg-[#f9fafb] p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[#2563eb] mb-4">{{ __('Order Details') }}</h3>
                    <div class="space-y-3 text-sm text-[#475569]">
                        <div class="flex justify-between">
                            <span>{{ __('Order Number:') }}</span>
                            <span class="font-semibold text-[#0f172a]">{{ $order->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Order Date:') }}</span>
                            <span class="font-semibold text-[#0f172a]">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        @if ($order->items->count() > 0)
                            <div class="pt-3 border-t border-[#e5e7eb]">
                                <p class="font-semibold text-[#0f172a] mb-2">{{ __('Items:') }}</p>
                                <ul class="space-y-1">
                                    @foreach ($order->items as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item->name }} × {{ $item->quantity }}</span>
                                            <span class="font-semibold">${{ number_format($item->total, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-purple-600 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-[#0f172a] mb-4">{{ __('Order Summary') }}</h2>
                    <dl class="space-y-3 text-sm text-[#475569]">
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Subtotal') }}</dt>
                            <dd class="font-semibold text-purple-600">${{ number_format($order->subtotal, 2) }}</dd>
                        </div>
                        @if ($order->discount_total > 0)
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Discount') }}</dt>
                                <dd class="font-semibold text-[#16a34a]">-${{ number_format($order->discount_total, 2) }}</dd>
                            </div>
                        @endif
                        @if ($order->tax_total > 0)
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Tax') }}</dt>
                                <dd class="font-semibold text-purple-600">${{ number_format($order->tax_total, 2) }}</dd>
                            </div>
                        @endif
                        @if ($order->shipping_total > 0)
                            <div class="flex items-center justify-between">
                                <dt>{{ __('Shipping') }}</dt>
                                <dd class="font-semibold text-purple-600">${{ number_format($order->shipping_total, 2) }}</dd>
                            </div>
                        @endif
                        <div class="flex items-center justify-between border-t border-dashed border-[#e5e7eb] pt-3">
                            <dt class="font-semibold text-purple-600">{{ __('Total') }}</dt>
                            <dd class="text-xl font-semibold text-[#0f172a]">${{ number_format($order->grand_total, 2) }}</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="rounded-3xl border border-[#e5e7eb] bg-[#f9fafb] p-6 shadow-sm">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-[#2563eb] mb-4">{{ __('Need assistance?') }}</h3>
                    <p class="text-sm leading-relaxed text-[#475569] mb-4">
                        {{ __('If you have any questions about your order or payment, our support team is here to help.') }}
                    </p>
                    <a href="{{ route('support.index') }}" class="inline-flex items-center justify-center w-full rounded-full border border-purple-600 px-4 py-2 text-sm font-semibold text-purple-600 hover:bg-purple-600 hover:text-white">
                        {{ __('Contact Support') }}
                    </a>
                </div>
            </aside>
        </div>
    </section>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements({
            appearance: {
                theme: 'stripe',
            },
        });
        const cardElement = elements.create('card', {
            style: {
                base: {
                    color: '#111827',
                    fontFamily: 'Instrument Sans, system-ui, sans-serif',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#9ca3af',
                    },
                },
                invalid: {
                    color: '#b91c1c',
                    iconColor: '#b91c1c',
                },
            },
        });

        cardElement.mount('#card-element');

        const cardErrors = document.getElementById('card-errors');
        cardElement.on('change', function(event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
                cardErrors.classList.remove('hidden');
            } else {
                cardErrors.textContent = '';
                cardErrors.classList.add('hidden');
            }
        });

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
            cardErrors.textContent = '';
            cardErrors.classList.add('hidden');

            const { error, paymentIntent } = await stripe.confirmCardPayment(
                '{{ $paymentIntent->client_secret }}',
                {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            email: '{{ auth()->user()->email }}',
                        },
                    },
                }
            );

            if (error) {
                cardErrors.textContent = error.message;
                cardErrors.classList.remove('hidden');
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                // Payment succeeded, submit to server
                const formData = new FormData();
                formData.append('order_id', '{{ $order->id }}');
                formData.append('payment_intent_id', paymentIntent.id);
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch('{{ route('payment.process') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    let data;
                    const contentType = response.headers.get('content-type');
                    
                    if (contentType && contentType.includes('application/json')) {
                        data = await response.json();
                    } else {
                        // If response is not JSON, it's likely an error page
                        const text = await response.text();
                        throw new Error('Server returned an error. Please try again.');
                    }

                    if (data.success) {
                        window.location.href = data.redirect || '{{ route('payment.success', $order) }}';
                    } else {
                        cardErrors.textContent = data.message || '{{ __('An error occurred while processing your payment.') }}';
                        cardErrors.classList.remove('hidden');
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                    }
                } catch (err) {
                    console.error('Payment processing error:', err);
                    cardErrors.textContent = err.message || '{{ __('An error occurred while processing your payment.') }}';
                    cardErrors.classList.remove('hidden');
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                }
            } else {
                cardErrors.textContent = 'Payment is still processing. Please wait...';
                cardErrors.classList.remove('hidden');
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });
    </script>
</x-landing-layout>

