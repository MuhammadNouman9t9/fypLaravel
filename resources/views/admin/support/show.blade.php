@extends('admin.layout')

@section('title', 'Support Conversation')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.support.index') }}" class="text-decoration-none">&larr; Back to Support</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                <div>
                    <h2 class="h4 mb-1">{{ $conversation->subject ?? 'No Subject' }}</h2>
                    <p class="text-muted small mb-0">Customer: {{ $conversation->user->name ?? 'Guest' }}</p>
                </div>
                <form action="{{ route('admin.support.update-status', $conversation) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm">
                        <option value="open" {{ $conversation->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ $conversation->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="pending" {{ $conversation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <select name="priority" class="form-select form-select-sm">
                        <option value="low" {{ $conversation->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ $conversation->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ $conversation->priority === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $conversation->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">Update</button>
                </form>
            </div>

            <div class="p-3 bg-light rounded-3 border">
                <h3 class="small fw-bold mb-3">Customer Details</h3>
                <div class="row g-2 small">
                    <div class="col-md-6">
                        <span class="text-muted">Name:</span>
                        <span class="fw-semibold ms-1">{{ $conversation->user->name ?? ($conversation->metadata['full_name'] ?? 'N/A') }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Email:</span>
                        <span class="fw-semibold ms-1">{{ $conversation->user->email ?? ($conversation->metadata['email'] ?? 'N/A') }}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted">Phone:</span>
                        <span class="fw-semibold ms-1">{{ $conversation->user->phone ?? ($conversation->metadata['phone'] ?? 'N/A') }}</span>
                    </div>
                    @if (isset($conversation->metadata['property_type']))
                        <div class="col-md-6">
                            <span class="text-muted">Property Type:</span>
                            <span class="fw-semibold ms-1">{{ ucfirst($conversation->metadata['property_type']) }}</span>
                        </div>
                    @endif
                    @if (isset($conversation->metadata['property_size']))
                        <div class="col-md-6">
                            <span class="text-muted">Property Size:</span>
                            <span class="fw-semibold ms-1">{{ ucfirst($conversation->metadata['property_size']) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h6 fw-bold mb-3">Messages</h3>
            <div class="d-flex flex-column gap-3" style="max-height: 24rem; overflow-y: auto;">
                @foreach ($conversation->messages as $message)
                    <div class="p-3 rounded-3 {{ $message->sender_type === 'admin' ? 'bg-primary-subtle ms-4' : 'bg-light me-4' }}">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small fw-semibold">{{ $message->sender->name ?? 'System' }}</span>
                            <span class="small text-muted">{{ $message->sent_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="small text-secondary mb-0" style="white-space: pre-wrap;">{{ $message->body }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h3 class="h6 fw-bold mb-3">Respond</h3>
            <form action="{{ route('admin.support.respond', $conversation) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="message" rows="4" class="form-control" placeholder="Type your response..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Response</button>
            </form>
        </div>
    </div>
@endsection
