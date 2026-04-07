@extends('admin.layout')

@section('title', 'Order Details')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Orders</a>
        @if ($order->status === 'pending')
            <form action="{{ route('admin.orders.confirm', $order) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">Confirm Order</button>
            </form>
        @endif
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h4 mb-0">Order #{{ $order->order_number }}</h2>
        </div>
    </div>

    @if($order->shipments->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4 border-primary border-opacity-25 bg-primary bg-opacity-10">
            <div class="card-body">
                <h3 class="h6 mb-3">Tracking Information</h3>
                @foreach($order->shipments as $shipment)
                    <div class="card bg-white border @if(!$loop->last) mb-3 @endif">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted text-uppercase">Tracking Number</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="font-monospace fw-bold text-primary fs-5">{{ $shipment->tracking_number }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ $shipment->tracking_number }}')" title="Copy">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted text-uppercase">Carrier</div>
                                    <div class="fw-semibold">{{ $shipment->carrier ?? 'SafeNest Express' }}</div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted text-uppercase">Status</div>
                                    <span class="badge
                                        @if($shipment->status === 'pending') text-bg-warning
                                        @elseif($shipment->status === 'shipped') text-bg-primary
                                        @elseif($shipment->status === 'delivered') text-bg-success
                                        @else text-bg-secondary
                                        @endif">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                </div>
                                @if($shipment->expected_delivery_at)
                                    <div class="col-12 col-md-6">
                                        <div class="small text-muted text-uppercase">Expected Delivery</div>
                                        <div>{{ $shipment->expected_delivery_at->format('M d, Y') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="h6 mb-3">Customer Information</h3>
                    <p class="mb-1">{{ $order->user->name ?? 'Guest' }}</p>
                    <p class="text-muted small mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="h6 mb-3">Order Status</h3>
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h6 mb-3">Order Items</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td class="fw-semibold">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-12 col-sm-8 col-md-5 col-lg-4">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax</span>
                            <span>${{ number_format($order->tax_total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span>${{ number_format($order->shipping_total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2 fs-5 fw-semibold">
                            <span>Total</span>
                            <span>${{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
