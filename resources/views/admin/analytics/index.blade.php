@extends('admin.layout')

@section('title', 'Analytics')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 mb-0">Analytics</h1>
            <p class="small text-muted mb-0">Sales and conversion metrics for the selected range.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">&larr; Dashboard</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small mb-0 text-muted">Period (days)</label>
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="7" {{ (string) $period === '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ (string) $period === '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ (string) $period === '90' ? 'selected' : '' }}>Last 90 days</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small mb-0 text-muted">Start</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small mb-0 text-muted">End</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Total revenue</div>
                    <div class="h4 mb-0 text-primary">${{ number_format($overview['total_revenue'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Paid orders</div>
                    <div class="h4 mb-0">{{ number_format($overview['total_orders']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Avg. order value</div>
                    <div class="h4 mb-0">${{ number_format($overview['average_order_value'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Paying customers</div>
                    <div class="h4 mb-0">{{ number_format($overview['total_customers']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <strong>Conversion (approx.)</strong>
                </div>
                <div class="card-body small">
                    <p class="text-muted mb-2">Visitors = users registered in range. Carts are session-based; &quot;Unpaid orders&quot; counts checkouts not completed.</p>
                    <div class="d-flex justify-content-between mb-1"><span>Visitors</span><span>{{ number_format($conversionMetrics['total_visitors']) }}</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Paid orders</span><span>{{ number_format($conversionMetrics['total_orders']) }}</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Conversion rate</span><span>{{ $conversionMetrics['conversion_rate'] }}%</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Unpaid orders</span><span>{{ number_format($conversionMetrics['abandoned_carts']) }}</span></div>
                    <div class="d-flex justify-content-between"><span>Unpaid / (paid + unpaid)</span><span>{{ $conversionMetrics['cart_abandonment_rate'] }}%</span></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <strong>Customers</strong>
                </div>
                <div class="card-body small">
                    <div class="d-flex justify-content-between mb-1"><span>New users</span><span>{{ number_format($customerMetrics['new_customers']) }}</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Returning (with paid order)</span><span>{{ number_format($customerMetrics['returning_customers']) }}</span></div>
                    <div class="d-flex justify-content-between mb-1"><span>Avg. LTV (range)</span><span>${{ number_format($customerMetrics['average_ltv'], 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <strong>Top products</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Qty sold</th>
                        <th class="text-end">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topProducts as $row)
                        <tr>
                            <td>{{ $row['product_name'] }}</td>
                            <td class="text-end">{{ number_format($row['total_quantity']) }}</td>
                            <td class="text-end">${{ number_format($row['total_revenue'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No data for this range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <strong>Revenue by category</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($revenueByCategory as $row)
                                <tr>
                                    <td>{{ $row['category'] }}</td>
                                    <td class="text-end">${{ number_format($row['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">No data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <strong>Daily trend (last {{ $period }} days)</strong>
                </div>
                <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Date</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salesTrends as $row)
                                <tr>
                                    <td>{{ $row['date'] }}</td>
                                    <td class="text-end">${{ number_format($row['revenue'], 2) }}</td>
                                    <td class="text-end">{{ $row['orders'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
