<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Orders') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Order Status') }}</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">{{ __('All Statuses') }}</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Payment Status') }}</label>
                            <select name="payment_status" id="payment_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">{{ __('All Payment Statuses') }}</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                                {{ __('Filter') }}
                            </button>
                            @if(request()->hasAny(['status', 'payment_status']))
                                <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            @if($orders->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-4 mb-2">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ __('Order') }} #{{ $order->order_number }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                ">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ __('Placed on') }} {{ $order->created_at->format('M d, Y') }}
                                            </p>
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                                <div>
                                                    <span class="font-medium">{{ __('Items:') }}</span> {{ $order->items->sum('quantity') }}
                                                </div>
                                                <div>
                                                    <span class="font-medium">{{ __('Total:') }}</span> 
                                                    <span class="font-semibold text-gray-900">${{ number_format($order->grand_total, 2) }}</span>
                                                </div>
                                                @if($order->shipments->isNotEmpty())
                                                    <div>
                                                        <span class="font-medium">{{ __('Tracking:') }}</span> 
                                                        <span class="font-mono text-purple-600">{{ $order->shipments->first()->tracking_number }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition text-sm font-medium">
                                                {{ __('View Details') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No orders found') }}</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ __('Get started by placing your first order.') }}</p>
                        <div class="mt-6">
                            <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                {{ __('Browse Products') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

