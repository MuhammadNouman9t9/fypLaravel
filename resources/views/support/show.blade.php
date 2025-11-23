<x-landing-layout title="Support Conversation">
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('support.index') }}" class="text-blue-600 hover:text-blue-700">← Back to Support</a>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">{{ $conversation->subject ?? 'No Subject' }}</h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Status: 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $conversation->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $conversation->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $conversation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            ">
                                {{ ucfirst($conversation->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                @if ($conversation->metadata)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Request Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            @if (isset($conversation->metadata['property_type']))
                                <div>
                                    <span class="text-gray-600">Property Type:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ ucfirst($conversation->metadata['property_type']) }}</span>
                                </div>
                            @endif
                            @if (isset($conversation->metadata['property_size']))
                                <div>
                                    <span class="text-gray-600">Property Size:</span>
                                    <span class="font-medium text-gray-900 ml-2">{{ ucfirst($conversation->metadata['property_size']) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto" id="messages-container">
                    @foreach ($conversation->messages as $message)
                        <div class="p-4 rounded-lg {{ $message->sender_type === 'admin' ? 'bg-blue-50 ml-8' : 'bg-gray-50 mr-8' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $message->sender_type === 'admin' ? ($message->sender->name ?? 'Admin') : 'You' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $message->sent_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $message->body }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($conversation->status !== 'closed')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Send Message</h3>
                    <form action="{{ route('support.respond', $conversation) }}" method="POST" id="message-form">
                        @csrf
                        <div class="mb-4">
                            <textarea 
                                name="message" 
                                rows="4" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                placeholder="Type your message..." 
                                required
                                id="message-input"
                            ></textarea>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Send Message</button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                    <p class="text-gray-600">This conversation is closed. If you need further assistance, please start a new consultation request.</p>
                    <a href="{{ route('support.index') }}" class="mt-4 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                        New Consultation Request
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-scroll to bottom of messages
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Clear message input after successful submission
        document.getElementById('message-form')?.addEventListener('submit', function() {
            setTimeout(() => {
                document.getElementById('message-input').value = '';
            }, 100);
        });
    </script>
</x-landing-layout>
