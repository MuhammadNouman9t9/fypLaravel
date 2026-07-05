@extends('admin.layout')

@section('title', 'Customer Support')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Customer Support</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.support.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search conversations..." class="form-control">
                </div>
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="priority" class="form-select">
                        <option value="">All Priority</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Last Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conversations as $conversation)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $conversation->subject ?? 'No Subject' }}</div>
                            </td>
                            <td>{{ $conversation->user->name ?? 'Guest' }}</td>
                            <td>
                                <span class="badge
                                    {{ $conversation->status === 'open' ? 'text-bg-success' : '' }}
                                    {{ $conversation->status === 'closed' ? 'text-bg-secondary' : '' }}
                                    {{ $conversation->status === 'pending' ? 'text-bg-warning' : '' }}
                                ">
                                    {{ ucfirst($conversation->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge
                                    {{ $conversation->priority === 'urgent' ? 'text-bg-danger' : '' }}
                                    {{ $conversation->priority === 'high' ? 'text-bg-warning' : '' }}
                                    {{ $conversation->priority === 'normal' ? 'text-bg-primary' : '' }}
                                    {{ $conversation->priority === 'low' ? 'text-bg-secondary' : '' }}
                                ">
                                    {{ ucfirst($conversation->priority) }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $conversation->last_message_at ? $conversation->last_message_at->diffForHumans() : 'Never' }}
                            </td>
                            <td>
                                <a href="{{ route('admin.support.show', $conversation) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No conversations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body border-top">
            {{ $conversations->links() }}
        </div>
    </div>
@endsection
