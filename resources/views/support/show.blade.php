<x-landing-layout title="Support Conversation">
    <div class="bg-light min-vh-100 py-4 py-lg-5">
        <div class="container" style="max-width: 48rem;">
            <div class="mb-4">
                <a href="{{ route('support.index') }}" class="text-decoration-none">&larr; Back to Support</a>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h2 class="h4 fw-bold text-dark mb-0">{{ $conversation->subject ?? 'No Subject' }}</h2>
                        <span class="badge
                            {{ $conversation->status === 'open' ? 'text-bg-success' : '' }}
                            {{ $conversation->status === 'closed' ? 'text-bg-secondary' : '' }}
                            {{ $conversation->status === 'pending' ? 'text-bg-warning' : '' }}
                        ">
                            {{ ucfirst($conversation->status) }}
                        </span>
                    </div>

                    @if ($conversation->metadata)
                        <div class="mt-3 p-3 bg-light rounded-3 border">
                            <h3 class="small fw-bold text-dark mb-2">Request Details</h3>
                            <div class="row small g-2">
                                @if (isset($conversation->metadata['property_type']))
                                    <div class="col-sm-6">
                                        <span class="text-secondary">Property Type:</span>
                                        <span class="fw-semibold text-dark ms-1">{{ ucfirst($conversation->metadata['property_type']) }}</span>
                                    </div>
                                @endif
                                @if (isset($conversation->metadata['property_size']))
                                    <div class="col-sm-6">
                                        <span class="text-secondary">Property Size:</span>
                                        <span class="fw-semibold text-dark ms-1">{{ ucfirst($conversation->metadata['property_size']) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold text-dark mb-3">Messages</h3>
                    <div class="d-flex flex-column gap-3" style="max-height: 24rem; overflow-y: auto;" id="messages-container">
                        @foreach ($conversation->messages as $message)
                            <div class="p-3 rounded-3 {{ $message->sender_type === 'admin' ? 'bg-primary-subtle ms-4' : 'bg-light me-4' }}">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="small fw-semibold text-dark">
                                        {{ $message->sender_type === 'admin' ? ($message->sender->name ?? 'Admin') : 'You' }}
                                    </span>
                                    <span class="small text-secondary">{{ $message->sent_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="small text-secondary mb-0" style="white-space: pre-wrap;">{{ $message->body }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($conversation->status !== 'closed')
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold text-dark mb-3">Send Message</h3>
                        <form action="{{ route('support.respond', $conversation) }}" method="POST" id="message-form">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" rows="4" class="form-control" placeholder="Type your message..." required id="message-input"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card border-0 bg-light text-center p-4">
                    <p class="text-secondary mb-3">This conversation is closed. If you need further assistance, please start a new consultation request.</p>
                    <a href="{{ route('support.index') }}" class="btn btn-primary mx-auto" style="width: fit-content;">
                        New Consultation Request
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        document.getElementById('message-form')?.addEventListener('submit', function () {
            setTimeout(() => {
                document.getElementById('message-input').value = '';
            }, 100);
        });
    </script>
</x-landing-layout>
