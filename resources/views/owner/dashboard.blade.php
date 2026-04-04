@extends('owner.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small text-secondary mb-1">Total Users</p>
                            <p class="h3 fw-bold text-dark mb-0">{{ number_format($stats['total_users']) }}</p>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 rounded-circle">
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small text-secondary mb-1">Total Products</p>
                            <p class="h3 fw-bold text-dark mb-0">{{ number_format($stats['total_products']) }}</p>
                        </div>
                        <div class="p-3 bg-success bg-opacity-10 rounded-circle">
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small text-secondary mb-1">Total Orders</p>
                            <p class="h3 fw-bold text-dark mb-0">{{ number_format($stats['total_orders']) }}</p>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-10 rounded-circle">
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small text-secondary mb-1">Total Revenue</p>
                            <p class="h3 fw-bold text-dark mb-0">${{ number_format($stats['total_revenue'], 2) }}</p>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 rounded-circle">
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="h6 fw-semibold mb-0">Recent Orders</h3>
                </div>
                <div class="card-body">
                    @if ($stats['recent_orders']->isEmpty())
                        <p class="small text-secondary mb-0">No orders yet.</p>
                    @else
                        @foreach ($stats['recent_orders'] as $order)
                            <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                                <div>
                                    <p class="small fw-medium mb-0">{{ $order->order_number }}</p>
                                    <p class="small text-secondary mb-0">{{ $order->user->name ?? 'Guest' }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="small fw-semibold mb-1">${{ number_format($order->grand_total, 2) }}</p>
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'text-bg-warning',
                                            'processing' => 'text-bg-info',
                                            'delivered' => 'text-bg-success',
                                            'cancelled' => 'text-bg-danger',
                                            default => 'text-bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="h6 fw-semibold mb-0">Recent Users</h3>
                </div>
                <div class="card-body">
                    @if ($stats['recent_users']->isEmpty())
                        <p class="small text-secondary mb-0">No users yet.</p>
                    @else
                        @foreach ($stats['recent_users'] as $user)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <div>
                                    <p class="small fw-medium mb-0">{{ $user->name }}</p>
                                    <p class="small text-secondary mb-0">{{ $user->email }}</p>
                                </div>
                                <span class="small text-secondary">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="h6 fw-semibold mb-0">Quick Stats</h3>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <p class="small text-secondary mb-1">Pending Orders</p>
                    <p class="h4 fw-bold mb-0">{{ number_format($stats['pending_orders']) }}</p>
                </div>
                <div class="col-md-4">
                    <p class="small text-secondary mb-1">Open Support Tickets</p>
                    <p class="h4 fw-bold mb-0">{{ number_format($stats['open_support_tickets']) }}</p>
                </div>
                <div class="col-md-4">
                    <p class="small text-secondary mb-1">Average Order Value</p>
                    <p class="h4 fw-bold mb-0">
                        ${{ $stats['total_orders'] > 0 ? number_format($stats['total_revenue'] / $stats['total_orders'], 2) : '0.00' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
