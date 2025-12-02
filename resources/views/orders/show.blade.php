<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Order Details') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('Order') }} #{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('orders.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                ← {{ __('Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Order Status Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">{{ __('Order Status') }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">{{ __('Payment Status') }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">{{ __('Order Date') }}</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracking Information -->
                @if($order->shipments->isNotEmpty())
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 overflow-hidden shadow-sm sm:rounded-lg border border-purple-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ __('Tracking Information') }}
                            </h3>
                            @foreach($order->shipments as $shipment)
                                <div class="bg-white rounded-lg p-4 mb-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Tracking Number') }}</p>
                                            <div class="flex items-center gap-2">
                                                <p class="text-lg font-mono font-bold text-purple-600">{{ $shipment->tracking_number }}</p>
                                                <button onclick="navigator.clipboard.writeText('{{ $shipment->tracking_number }}')" class="text-gray-400 hover:text-purple-600 transition" title="{{ __('Copy Tracking Number') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Carrier') }}</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $shipment->carrier ?? 'SafeNest Express' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Status') }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $shipment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $shipment->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $shipment->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                            ">
                                                {{ ucfirst($shipment->status) }}
                                            </span>
                                        </div>
                                        @if($shipment->expected_delivery_at)
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Expected Delivery') }}</p>
                                                <p class="text-sm text-gray-900">{{ $shipment->expected_delivery_at->format('M d, Y') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Order Items -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Items') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit Price') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    @if($item->product && $item->product->cover_image_url)
                                                        <img src="{{ $item->product->cover_image_url }}" alt="{{ $item->name }}" class="h-12 w-12 object-cover rounded">
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                                        @if($item->sku)
                                                            <p class="text-xs text-gray-500">{{ __('SKU') }}: {{ $item->sku }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Order Summary') }}</h3>
                        <div class="flex justify-end">
                            <div class="w-64 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Subtotal:') }}</span>
                                    <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->discount_total > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ __('Discount:') }}</span>
                                        <span class="font-medium text-green-600">-${{ number_format($order->discount_total, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Tax:') }}</span>
                                    <span class="font-medium">${{ number_format($order->tax_total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Shipping:') }}</span>
                                    <span class="font-medium">${{ number_format($order->shipping_total, 2) }}</span>
                                </div>
                                @if($order->refunded_total > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">{{ __('Refunded:') }}</span>
                                        <span class="font-medium text-red-600">-${{ number_format($order->refunded_total, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                                    <span>{{ __('Total:') }}</span>
                                    <span>${{ number_format($order->grand_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($order->payments->isNotEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Payment Information') }}</h3>
                            <div class="space-y-3">
                                @foreach($order->payments as $payment)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <p class="text-gray-500 mb-1">{{ __('Payment Method') }}</p>
                                                <p class="font-medium text-gray-900">{{ ucfirst($payment->method ?? 'Card') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 mb-1">{{ __('Amount') }}</p>
                                                <p class="font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 mb-1">{{ __('Status') }}</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->isSucceeded() ? 'bg-green-100 text-green-800' : ($payment->isFailed() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </div>
                                            @if($payment->captured_at)
                                                <div>
                                                    <p class="text-gray-500 mb-1">{{ __('Paid On') }}</p>
                                                    <p class="font-medium text-gray-900">{{ $payment->captured_at->format('M d, Y') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        {{ __('Back to Orders') }}
                    </a>
                    @if($order->payment_status === 'unpaid')
                        <a href="{{ route('payment.checkout', $order) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                            {{ __('Complete Payment') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

