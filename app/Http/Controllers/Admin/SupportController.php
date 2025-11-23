<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportConversation::with('user', 'assignedExpert', 'messages');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('subject', 'like', '%'.$request->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('email', 'like', '%'.$request->search.'%')
                            ->orWhere('first_name', 'like', '%'.$request->search.'%')
                            ->orWhere('last_name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        $conversations = $query->latest('last_message_at')->paginate(15);

        return view('admin.support.index', compact('conversations'));
    }

    public function show(SupportConversation $conversation): View
    {
        $conversation->load('user', 'assignedExpert', 'messages.sender');

        return view('admin.support.show', compact('conversation'));
    }

    public function respond(Request $request, SupportConversation $conversation): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = SupportMessage::create([
            'support_conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => 'admin',
            'body' => $validated['message'],
            'is_internal' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'assigned_to' => auth()->id(),
        ]);

        return back()->with('status', __('Response sent successfully.'));
    }

    public function updateStatus(Request $request, SupportConversation $conversation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:open,closed,pending'],
            'priority' => ['nullable', 'string', 'in:low,normal,high,urgent'],
        ]);

        $conversation->update($validated);

        if ($validated['status'] === 'closed') {
            $conversation->update(['closed_at' => now()]);
        }

        return back()->with('status', __('Conversation status updated successfully.'));
    }
}
