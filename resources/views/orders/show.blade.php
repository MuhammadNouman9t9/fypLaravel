<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h5 mb-0">{{ __('Order Details') }} #{{ $order->order_number }}</h2>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('← Back to Orders') }}</a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <h3 class="h6 mb-0">{{ __('Order Status') }}</h3>
                            <div class="d-flex gap-2">
                                <span class="badge @if($order->status === 'completed') text-bg-success @elseif($order->status === 'pending') text-bg-warning @elseif($order->status === 'cancelled') text-bg-danger @else text-bg-secondary @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="badge @if($order->payment_status === 'paid') text-bg-success @elseif($order->payment_status === 'pending') text-bg-warning @else text-bg-danger @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="row g-3 small">
                            <div class="col-12 col-md-6">
                                <div class="text-muted">{{ __('Order Date') }}</div>
                                <div class="fw-semibold">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                            @if($order->placed_at)
                                <div class="col-12 col-md-6">
                                    <div class="text-muted">{{ __('Placed At') }}</div>
                                    <div class="fw-semibold">{{ $order->placed_at->format('M d, Y h:i A') }}</div>
                                </div>
                            @endif
                            @if($order->paid_at)
                                <div class="col-12 col-md-6">
                                    <div class="text-muted">{{ __('Paid At') }}</div>
                                    <div class="fw-semibold">{{ $order->paid_at->format('M d, Y h:i A') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h6 mb-3">{{ __('Order Items') }}</h3>
                        @foreach ($order->items as $item)
                            <div class="d-flex gap-3 pb-3 mb-3 border-bottom">
                                <div class="rounded border bg-light overflow-hidden d-flex align-items-center justify-content-center" style="width:80px; height:80px;">
                                    @if($item->product && $item->product->media->first())
                                        <img src="{{ $item->product->media->first()->url }}" alt="{{ $item->name }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $item->name }}</div>
                                    @if($item->sku)
                                        <div class="small text-muted">SKU: {{ $item->sku }}</div>
                                    @endif
                                    <div class="small text-muted">{{ __('Quantity') }}: {{ $item->quantity }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-semibold">{{ $order->currency }} {{ number_format($item->total, 2) }}</div>
                                    <div class="small text-muted">{{ $order->currency }} {{ number_format($item->unit_price, 2) }} {{ __('each') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($order->shipments->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="h6 mb-3">{{ __('Shipping Information') }}</h3>
                            @foreach ($order->shipments as $shipment)
                                <div class="border-start border-3 border-primary ps-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-semibold">{{ __('Tracking') }}: {{ $shipment->tracking_number ?? __('N/A') }}</div>
                                        <div class="small text-muted">{{ $shipment->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="small text-muted">{{ __('Status') }}: {{ ucfirst($shipment->status ?? 'pending') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-body">
                        <h3 class="h6 mb-3">{{ __('Order Summary') }}</h3>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Subtotal') }}</span>
                                <span>{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if($order->discount_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ __('Discount') }}</span>
                                    <span class="text-success">-{{ $order->currency }} {{ number_format($order->discount_total, 2) }}</span>
                                </div>
                            @endif
                            @if($order->tax_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ __('Tax') }}</span>
                                    <span>{{ $order->currency }} {{ number_format($order->tax_total, 2) }}</span>
                                </div>
                            @endif
                            @if($order->shipping_total > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">{{ __('Shipping') }}</span>
                                    <span>{{ $order->currency }} {{ number_format($order->shipping_total, 2) }}</span>
                                </div>
                            @endif
                            <div class="border-top pt-2 mt-2 d-flex justify-content-between">
                                <span class="fw-semibold">{{ __('Total') }}</span>
                                <span class="fw-semibold">{{ $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
                            </div>
                        </div>

                        @if($order->payment_status !== 'paid')
                            <div class="mt-4">
                                <a href="{{ route('payment.checkout', $order) }}" class="btn btn-primary w-100">
                                    {{ __('Complete Payment') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
