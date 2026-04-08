@extends('admin.layout')

@section('title', 'Fraud Alert Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Fraud Alert</h2>
        <a href="{{ route('admin.fraud-alerts.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Fraud Detection</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Risk Level</div>
                    <div class="h5 text-capitalize mb-0">{{ $alert->risk_level }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Risk Score</div>
                    <div class="h5 mb-0">{{ number_format((float) $alert->score, 1) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Status</div>
                    <div class="h5 mb-0">{{ ucfirst($alert->status) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-secondary">Detected At</div>
                    <div class="h6 mb-0">{{ optional($alert->detected_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white fw-semibold">Alert Context</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="small text-secondary">User</div>
                    <div>{{ $alert->user?->name ? $alert->user->name.' ('.$alert->user->email.')' : ($alert->user?->email ?? 'Guest/Unknown') }}</div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="small text-secondary">Order</div>
                    <div>{{ $alert->order?->order_number ?? '—' }}</div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="small text-secondary">Payment ID</div>
                    <div>{{ $alert->payment_id ?? '—' }}</div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="small text-secondary">Reason</div>
                    <div>{{ $alert->reason ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Detection Flags</div>
                <div class="card-body">
                    @if (!empty($alert->flags))
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($alert->flags as $flag)
                                <span class="badge text-bg-light border">{{ $flag }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-secondary">No flags recorded.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Metadata</div>
                <div class="card-body">
                    @if (!empty($alert->metadata))
                        <pre class="mb-0 small bg-light border rounded p-3">{{ json_encode($alert->metadata, JSON_PRETTY_PRINT) }}</pre>
                    @else
                        <div class="text-secondary">No metadata available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($alert->isOpen())
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-white fw-semibold">Resolve Alert</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.fraud-alerts.resolve', $alert) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="resolution" class="form-label">Resolution Notes</label>
                        <textarea id="resolution" name="resolution" rows="3" class="form-control" required>{{ old('resolution') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Mark as Resolved</button>
                </form>
            </div>
        </div>
    @endif
@endsection
