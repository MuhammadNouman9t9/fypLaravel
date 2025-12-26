<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(): View
    {
        $conversations = SupportConversation::where('user_id', auth()->id())
            ->with('messages')
            ->latest('last_message_at')
            ->paginate(10);

        return view('support.index', compact('conversations'));
    }

    public function show(SupportConversation $conversation): View
    {
        // Ensure user can only view their own conversations
        if ($conversation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $conversation->load('messages.sender', 'assignedExpert');

        // Mark all admin messages in this conversation as read
        SupportMessage::where('support_conversation_id', $conversation->id)
            ->where('sender_type', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('support.show', compact('conversation'));
    }

    public function respond(Request $request, SupportConversation $conversation): RedirectResponse
    {
        // Ensure user can only respond to their own conversations
        if ($conversation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'customer',
            'body' => $validated['message'],
            'sent_at' => now(),
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'status' => 'open', // Reopen if closed
        ]);

        return back()->with('status', 'Message sent successfully.');
    }
}
