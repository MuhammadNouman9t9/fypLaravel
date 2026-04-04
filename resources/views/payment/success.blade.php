<x-landing-layout title="Payment Successful">
    <section class="border-bottom bg-white">
        <div class="container py-5">
            <div class="text-center">
                <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width: 4rem; height: 4rem;">
                    <svg style="width: 2.5rem; height: 2.5rem;" class="text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="mt-4 h2 fw-semibold text-dark">
                    {{ __('Payment Successful!') }}
                </h1>
                <p class="mt-3 small text-secondary lh-lg mb-0">
                    {{ __('Thank you for your order. Your payment has been processed successfully.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <h2 class="h5 fw-semibold text-dark mb-3">{{ __('Order Information') }}</h2>
                        <dl class="small text-secondary mb-0">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <dt>{{ __('Order Number:') }}</dt>
                                <dd class="mb-0 fw-semibold text-dark">{{ $order->order_number }}</dd>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <dt>{{ __('Order Date:') }}</dt>
                                <dd class="mb-0 fw-semibold text-dark">{{ $order->created_at->format('M d, Y') }}</dd>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <dt>{{ __('Payment Status:') }}</dt>
                                <dd class="mb-0 fw-semibold text-success">{{ ucfirst($order->payment_status) }}</dd>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <dt>{{ __('Total Paid:') }}</dt>
                                <dd class="mb-0 fw-semibold text-dark">${{ number_format($order->grand_total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border bg-light shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <h2 class="h5 fw-semibold text-dark mb-3">{{ __('What's Next?') }}</h2>
                        <ul class="list-unstyled small text-secondary mb-0">
                            <li class="d-flex gap-2 mb-3">
                                <svg class="text-primary flex-shrink-0 mt-1" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('You will receive an email confirmation shortly with your order details.') }}</span>
                            </li>
                            <li class="d-flex gap-2 mb-3">
                                <svg class="text-primary flex-shrink-0 mt-1" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Our team will prepare your order for shipment.') }}</span>
                            </li>
                            <li class="d-flex gap-2">
                                <svg class="text-primary flex-shrink-0 mt-1" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('You will receive tracking information once your order ships.') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex flex-column flex-sm-row flex-wrap justify-content-center gap-2">
            <a href="{{ route('landing.products') }}" class="btn btn-outline-dark rounded-pill px-4">
                {{ __('Continue Shopping') }}
            </a>
            @auth
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary rounded-pill px-4">
                    {{ __('View Order Details') }}
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    {{ __('View Dashboard') }}
                </a>
            @endauth
        </div>
    </section>
</x-landing-layout>
