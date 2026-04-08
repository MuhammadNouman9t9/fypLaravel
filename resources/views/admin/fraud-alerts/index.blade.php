@extends('admin.layout')

@section('title', 'Fraud Detection')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">AI-based Fraud Detection</h2>
        <div class="small text-secondary">Monitor fake transactions, suspicious logins, and hacking attempts.</div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Total Alerts</div>
                    <div class="h4 mb-0">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Open Alerts</div>
                    <div class="h4 mb-0">{{ $stats['open'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Resolved Alerts</div>
                    <div class="h4 mb-0">{{ $stats['resolved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">High/Critical Risk</div>
                    <div class="h4 mb-0">{{ $stats['high_risk'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.fraud-alerts.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-0">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="open" @selected(request('status') === 'open')>Open</option>
                        <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-0">Risk Level</label>
                    <select name="risk_level" class="form-select">
                        <option value="">All</option>
                        <option value="low" @selected(request('risk_level') === 'low')>Low</option>
                        <option value="moderate" @selected(request('risk_level') === 'moderate')>Moderate</option>
                        <option value="high" @selected(request('risk_level') === 'high')>High</option>
                        <option value="critical" @selected(request('risk_level') === 'critical')>Critical</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                    @if(request()->hasAny(['status', 'risk_level']))
                        <a href="{{ route('admin.fraud-alerts.index') }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Detected</th>
                        <th>Type</th>
                        <th>Risk</th>
                        <th>Score</th>
                        <th>User</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alerts as $alert)
                        @php
                            $type = 'General anomaly';
                            $reason = strtolower($alert->reason ?? '');
                            if ($alert->payment_id || $alert->order_id) {
                                $type = 'Fake transaction';
                            } elseif (str_contains($reason, 'login') || str_contains($reason, 'failed')) {
                                $type = 'Suspicious login';
                            } elseif (str_contains($reason, 'ip') || str_contains($reason, 'suspicious')) {
                                $type = 'Hacking attempt';
                            }
                        @endphp
                        <tr>
                            <td>{{ optional($alert->detected_at)->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ $type }}</td>
                            <td class="text-capitalize">{{ $alert->risk_level }}</td>
                            <td>{{ number_format((float) $alert->score, 1) }}</td>
                            <td>{{ $alert->user?->email ?? 'Guest/Unknown' }}</td>
                            <td>{{ $alert->order?->order_number ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $alert->status === 'open' ? 'text-bg-warning' : 'text-bg-success' }}">
                                    {{ ucfirst($alert->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.fraud-alerts.show', $alert) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No fraud alerts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body border-top">
            {{ $alerts->links() }}
        </div>
    </div>
@endsection
