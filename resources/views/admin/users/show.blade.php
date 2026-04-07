@extends('admin.layout')

@section('title', 'User Details')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Users</a>
        @if (!$user->isAdmin())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete User</button>
            </form>
        @endif
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-primary-subtle">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <div class="text-secondary">{{ $user->email }}</div>
                    @if($user->phone)
                        <div class="text-secondary">{{ $user->phone }}</div>
                    @endif
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($user->roles as $role)
                        <span class="badge text-bg-primary">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Total Orders</div>
                    <div class="h4 mb-0">{{ $stats['total_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Total Spent</div>
                    <div class="h4 mb-0">${{ number_format($stats['total_spent'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Pending Orders</div>
                    <div class="h4 mb-0">{{ $stats['pending_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="small text-muted">Completed Orders</div>
                    <div class="h4 mb-0">{{ $stats['completed_orders'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Basic Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">First Name</div>
                            <div>{{ $user->first_name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Last Name</div>
                            <div>{{ $user->last_name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Email</div>
                            <div class="d-flex align-items-center gap-2">
                                <span>{{ $user->email }}</span>
                                @if ($user->email_verified_at)
                                    <span class="badge text-bg-success">Verified</span>
                                @else
                                    <span class="badge text-bg-warning">Unverified</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Phone</div>
                            <div class="d-flex align-items-center gap-2">
                                <span>{{ $user->phone ?? 'N/A' }}</span>
                                @if ($user->phone_verified_at)
                                    <span class="badge text-bg-success">Verified</span>
                                @elseif($user->phone)
                                    <span class="badge text-bg-warning">Unverified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Account Information</strong>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Account Created</div>
                            <div>{{ $user->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Last Login</div>
                            <div>
                                {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Two-Factor Authentication</div>
                            @if($user->hasTwoFactorEnabled())
                                <span class="badge text-bg-success">Enabled</span>
                            @else
                                <span class="badge text-bg-secondary">Disabled</span>
                            @endif
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Marketing Opt-in</div>
                            @if($user->marketing_opt_in)
                                <span class="badge text-bg-primary">Yes</span>
                            @else
                                <span class="badge text-bg-secondary">No</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->addresses->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Addresses ({{ $user->addresses->count() }})</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($user->addresses as $address)
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 h-100 {{ $address->is_primary ? 'border-primary bg-primary-subtle' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <strong>{{ $address->label ?? ucfirst($address->type) }}</strong>
                                            @if ($address->is_primary)
                                                <span class="badge text-bg-primary">Primary</span>
                                            @endif
                                        </div>
                                        <div>{{ $address->line_one }}</div>
                                        @if($address->line_two)
                                            <div>{{ $address->line_two }}</div>
                                        @endif
                                        <div>{{ $address->city }}@if($address->state), {{ $address->state }}@endif @if($address->postal_code) {{ $address->postal_code }}@endif</div>
                                        <div>{{ strtoupper($address->country_code) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <strong>Quick Actions</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="list-group-item list-group-item-action">
                        View Orders ({{ $stats['total_orders'] }})
                    </a>
                    <a href="mailto:{{ $user->email }}" class="list-group-item list-group-item-action">Send Email</a>
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="list-group-item list-group-item-action">Call User</a>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <strong>Account Status</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Email Verification</span>
                        @if ($user->email_verified_at)
                            <span class="badge text-bg-success">Verified</span>
                        @else
                            <span class="badge text-bg-warning">Pending</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phone Verification</span>
                        @if ($user->phone_verified_at)
                            <span class="badge text-bg-success">Verified</span>
                        @elseif($user->phone)
                            <span class="badge text-bg-warning">Pending</span>
                        @else
                            <span class="badge text-bg-secondary">Not Set</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>2FA</span>
                        @if($user->hasTwoFactorEnabled())
                            <span class="badge text-bg-success">Enabled</span>
                        @else
                            <span class="badge text-bg-secondary">Disabled</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
