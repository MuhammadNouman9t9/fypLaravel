@extends('admin.layout')

@section('title', 'Support Conversation')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.support.index') }}" class="text-blue-600 hover:text-blue-700">← Back to Support</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">{{ $conversation->subject ?? 'No Subject' }}</h2>
                <p class="text-sm text-gray-600 mt-1">Customer: {{ $conversation->user->name ?? 'Guest' }}</p>
            </div>
            <form action="{{ route('admin.support.update-status', $conversation) }}" method="POST" class="flex gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="open" {{ $conversation->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ $conversation->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="pending" {{ $conversation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <select name="priority" class="px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="low" {{ $conversation->priority === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="normal" {{ $conversation->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ $conversation->priority === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ $conversation->priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            </form>
        </div>

        <!-- Customer Details -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Customer Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $conversation->user->name ?? ($conversation->metadata['full_name'] ?? 'N/A') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $conversation->user->email ?? ($conversation->metadata['email'] ?? 'N/A') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium text-gray-900 ml-2">{{ $conversation->user->phone ?? ($conversation->metadata['phone'] ?? 'N/A') }}</span>
                </div>
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
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Messages</h3>
        <div class="space-y-4 max-h-96 overflow-y-auto">
            @foreach ($conversation->messages as $message)
                <div class="p-4 rounded-lg {{ $message->sender_type === 'admin' ? 'bg-blue-50 ml-8' : 'bg-gray-50 mr-8' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">{{ $message->sender->name ?? 'System' }}</span>
                        <span class="text-xs text-gray-500">{{ $message->sent_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $message->body }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Respond</h3>
        <form action="{{ route('admin.support.respond', $conversation) }}" method="POST">
            @csrf
            <div class="mb-4">
                <textarea name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Type your response..." required></textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Send Response</button>
        </form>
    </div>
@endsection
