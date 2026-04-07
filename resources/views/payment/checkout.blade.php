<x-landing-layout title="Checkout">
    <section class="border-bottom bg-white">
        <div class="container py-4 py-lg-5">
            <p class="small fw-semibold text-uppercase text-primary mb-2">{{ __('Secure Payment') }}</p>
            <h1 class="h2 fw-bold text-dark mb-2">{{ __('Complete your order') }}</h1>
            <p class="text-muted small mb-0">{{ __('Order #:order_number', ['order_number' => $order->order_number]) }}</p>
        </div>
    </section>

    <section class="container py-4 py-lg-5">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-semibold mb-4">{{ __('Payment Information') }}</h2>

                        <form id="payment-form">
                            <div id="card-element" class="border rounded p-3 bg-white mb-3"></div>

                            <div id="card-errors" role="alert" class="text-danger small mb-3 d-none"></div>

                            <div class="d-flex align-items-center gap-2 text-muted small mb-4">
                                <svg width="20" height="20" class="text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>{{ __('Your payment information is secure and encrypted') }}</span>
                            </div>

                            <button type="submit" id="submit-button" class="btn btn-primary btn-lg w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                <span id="button-text">{{ __('Pay $:amount', ['amount' => number_format($order->grand_total, 2)]) }}</span>
                                <span id="spinner" class="d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h3 class="small fw-semibold text-uppercase text-primary mb-3">{{ __('Order Details') }}</h3>
                        <div class="small text-muted">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Order Number:') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->order_number }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Order Date:') }}</span>
                                <span class="fw-semibold text-dark">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            @if ($order->items->count() > 0)
                                <div class="border-top pt-3 mt-3">
                                    <p class="fw-semibold text-dark mb-2">{{ __('Items:') }}</p>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($order->items as $item)
                                            <li class="d-flex justify-content-between py-1">
                                                <span>{{ $item->name }} × {{ $item->quantity }}</span>
                                                <span class="fw-semibold">${{ number_format($item->total, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <aside class="col-12 col-lg-5">
                <div class="card border-primary border-opacity-25 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-semibold mb-4">{{ __('Order Summary') }}</h2>
                        <dl class="small mb-0">
                            <div class="d-flex justify-content-between mb-2">
                                <dt class="text-muted">{{ __('Subtotal') }}</dt>
                                <dd class="mb-0 fw-semibold text-primary">${{ number_format($order->subtotal, 2) }}</dd>
                            </div>
                            @if ($order->discount_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <dt class="text-muted">{{ __('Discount') }}</dt>
                                    <dd class="mb-0 fw-semibold text-success">-${{ number_format($order->discount_total, 2) }}</dd>
                                </div>
                            @endif
                            @if ($order->tax_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <dt class="text-muted">{{ __('Tax') }}</dt>
                                    <dd class="mb-0 fw-semibold text-primary">${{ number_format($order->tax_total, 2) }}</dd>
                                </div>
                            @endif
                            @if ($order->shipping_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <dt class="text-muted">{{ __('Shipping') }}</dt>
                                    <dd class="mb-0 fw-semibold text-primary">${{ number_format($order->shipping_total, 2) }}</dd>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between border-top pt-3 mt-2">
                                <dt class="fw-semibold text-primary">{{ __('Total') }}</dt>
                                <dd class="mb-0 h5 fw-bold text-dark">${{ number_format($order->grand_total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements({
            appearance: { theme: 'stripe' },
        });
        const cardElement = elements.create('card', {
            style: {
                base: {
                    color: '#111827',
                    fontFamily: 'Instrument Sans, system-ui, sans-serif',
                    fontSize: '16px',
                    '::placeholder': { color: '#9ca3af' },
                },
                invalid: { color: '#b91c1c', iconColor: '#b91c1c' },
            },
        });

        cardElement.mount('#card-element');

        const cardErrors = document.getElementById('card-errors');
        cardElement.on('change', function(event) {
            if (event.error) {
                cardErrors.textContent = event.error.message;
                cardErrors.classList.remove('d-none');
            } else {
                cardErrors.textContent = '';
                cardErrors.classList.add('d-none');
            }
        });

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            submitButton.disabled = true;
            buttonText.classList.add('d-none');
            spinner.classList.remove('d-none');
            cardErrors.textContent = '';
            cardErrors.classList.add('d-none');

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
                cardErrors.classList.remove('d-none');
                submitButton.disabled = false;
                buttonText.classList.remove('d-none');
                spinner.classList.add('d-none');
            } else if (paymentIntent && paymentIntent.status === 'succeeded') {
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
                        await response.text();
                        throw new Error('Server returned an error. Please try again.');
                    }

                    if (data.success) {
                        window.location.href = data.redirect || '{{ route('payment.success', $order) }}';
                    } else {
                        cardErrors.textContent = data.message || '{{ __('An error occurred while processing your payment.') }}';
                        cardErrors.classList.remove('d-none');
                        submitButton.disabled = false;
                        buttonText.classList.remove('d-none');
                        spinner.classList.add('d-none');
                    }
                } catch (err) {
                    console.error('Payment processing error:', err);
                    cardErrors.textContent = err.message || '{{ __('An error occurred while processing your payment.') }}';
                    cardErrors.classList.remove('d-none');
                    submitButton.disabled = false;
                    buttonText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                }
            } else {
                cardErrors.textContent = 'Payment is still processing. Please wait...';
                cardErrors.classList.remove('d-none');
                submitButton.disabled = false;
                buttonText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });
    </script>
</x-landing-layout>
