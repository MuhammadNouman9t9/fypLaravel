<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details') }} #{{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('← Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Order Status') }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">{{ __('Order Date') }}</p>
                                    <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                @if($order->placed_at)
                                    <div>
                                        <p class="text-gray-600">{{ __('Placed At') }}</p>
                                        <p class="font-medium text-gray-900">{{ $order->placed_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                @endif
                                @if($order->paid_at)
                                    <div>
                                        <p class="text-gray-600">{{ __('Paid At') }}</p>
                                        <p class="font-medium text-gray-900">{{ $order->paid_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Items') }}</h3>
                            <div class="space-y-4">
                                @foreach ($order->items as $item)
                                    <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-0">
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                            @if($item->product && $item->product->media->first())
                                                <img src="{{ $item->product->media->first()->url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $item->name }}</h4>
                                            @if($item->sku)
                                                <p class="text-sm text-gray-600">SKU: {{ $item->sku }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600 mt-1">{{ __('Quantity') }}: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-900">{{ $order->currency }} {{ number_format($item->total, 2) }}</p>
                                            <p class="text-sm text-gray-600">{{ $order->currency }} {{ number_format($item->unit_price, 2) }} {{ __('each') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @if($order->shipments->isNotEmpty())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Shipping Information') }}</h3>
                                <div class="space-y-4">
                                    @foreach ($order->shipments as $shipment)
                                        <div class="border-l-4 border-purple-500 pl-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="font-medium text-gray-900">{{ __('Tracking') }}: {{ $shipment->tracking_number ?? __('N/A') }}</p>
                                                <span class="text-sm text-gray-600">{{ $shipment->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600">{{ __('Status') }}: {{ ucfirst($shipment->status ?? 'pending') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-4">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Summary') }}</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Subtotal') }}</span>
                                    <span class="text-gray-900">{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->discount_total > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ __('Discount') }}</span>
                                        <span class="text-green-600">-{{ $order->currency }} {{ number_format($order->discount_total, 2) }}</span>
                                    </div>
                                @endif
                                @if($order->tax_total > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ __('Tax') }}</span>
                                        <span class="text-gray-900">{{ $order->currency }} {{ number_format($order->tax_total, 2) }}</span>
                                    </div>
                                @endif
                                @if($order->shipping_total > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ __('Shipping') }}</span>
                                        <span class="text-gray-900">{{ $order->currency }} {{ number_format($order->shipping_total, 2) }}</span>
                                    </div>
                                @endif
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between">
                                        <span class="font-semibold text-gray-900">{{ __('Total') }}</span>
                                        <span class="font-semibold text-gray-900">{{ $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($order->payment_status !== 'paid')
                                <div class="mt-6">
                                    <a href="{{ route('payment.checkout', $order) }}" class="w-full flex justify-center items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                                        {{ __('Complete Payment') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
