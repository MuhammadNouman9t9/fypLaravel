<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0">{{ __('My Orders') }}</h2>
    </x-slot>

    <div class="container py-4">
            @if ($orders->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <svg width="48" height="48" class="text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    <p class="h6 mb-2">{{ __('No orders yet') }}</p>
                    <p class="text-muted mb-3">{{ __('When you place an order, it will appear here.') }}</p>
                    <a href="{{ route('landing.products') }}" class="btn btn-primary">
                            {{ __('Start Shopping') }}
                        </a>
                    </div>
                </div>
            @else
            <div class="d-flex flex-column gap-3">
                    @foreach ($orders as $order)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    <h3 class="h6 mb-0">
                                                {{ __('Order') }} #{{ $order->order_number }}
                                            </h3>
                                    <span class="badge @if($order->status === 'completed') text-bg-success @elseif($order->status === 'pending') text-bg-warning @elseif($order->status === 'cancelled') text-bg-danger @else text-bg-secondary @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                    <span class="badge @if($order->payment_status === 'paid') text-bg-success @elseif($order->payment_status === 'pending') text-bg-warning @else text-bg-danger @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                            </span>
                                        </div>
                                <p class="small text-muted mb-1">
                                            {{ __('Placed on') }} {{ $order->created_at->format('M d, Y h:i A') }}
                                        </p>
                                <div class="small text-muted d-flex gap-3 flex-wrap">
                                            <span>{{ __('Items') }}: {{ $order->items->sum('quantity') }}</span>
                                            <span>{{ __('Total') }}: {{ $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
                                        </div>
                                    </div>
                            <div class="d-flex align-items-start">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>

                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex gap-2 flex-wrap">
                                        @foreach ($order->items->take(3) as $item)
                                    <div class="rounded border bg-light overflow-hidden d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                                @if($item->product && $item->product->media->first())
                                            <img src="{{ $item->product->media->first()->url }}" alt="{{ $item->name }}" class="w-100 h-100" style="object-fit: cover;">
                                                @else
                                            <div class="text-muted d-flex align-items-center justify-content-center">
                                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small fw-semibold" style="width: 72px; height: 72px;">
                                                +{{ $order->items->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
</x-app-layout>
