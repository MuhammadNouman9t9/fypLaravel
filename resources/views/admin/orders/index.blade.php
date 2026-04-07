@extends('admin.layout')

@section('title', 'Orders')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md">
                    <label class="form-label small mb-0 text-muted">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="form-control">
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="form-label small mb-0 text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-2">
                    <label class="form-label small mb-0 text-muted">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All Payment Status</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-auto d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                    @if(request()->hasAny(['search', 'status', 'payment_status', 'user_id']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Tracking ID</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        @php
                            $shipment = $order->shipments->first();
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>${{ number_format($order->grand_total, 2) }}</td>
                            <td>
                                @if($shipment && $shipment->tracking_number)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="font-monospace small text-primary">{{ $shipment->tracking_number }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" onclick="navigator.clipboard.writeText('{{ $shipment->tracking_number }}')" title="Copy">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">Not generated</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if($order->status === 'pending') text-bg-warning
                                    @elseif($order->status === 'processing') text-bg-primary
                                    @elseif($order->status === 'shipped') text-bg-info
                                    @elseif($order->status === 'delivered') text-bg-success
                                    @elseif($order->status === 'cancelled') text-bg-danger
                                    @else text-bg-secondary
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body border-top">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
