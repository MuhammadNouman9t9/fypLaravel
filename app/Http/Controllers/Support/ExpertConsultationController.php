<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpertConsultationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'property_type' => ['required', 'string', 'in:house,apartment,condo,business,other'],
            'property_size' => ['required', 'string', 'in:small,medium,large,xlarge'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $user = auth()->user();

        DB::transaction(function () use ($validated, $user) {
            $conversation = SupportConversation::create([
                'user_id' => $user->id,
                'subject' => 'Expert Consultation Request - '.$user->name,
                'status' => 'open',
                'priority' => 'normal',
                'channel' => 'web',
                'metadata' => [
                    'full_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'property_type' => $validated['property_type'],
                    'property_size' => $validated['property_size'],
                ],
            ]);

            $messageBody = "Property Type: ".ucfirst($validated['property_type'])."\n";
            $messageBody .= "Property Size: ".ucfirst($validated['property_size'])."\n\n";
            $messageBody .= "Question/Message:\n{$validated['message']}";

            SupportMessage::create([
                'support_conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'sender_type' => 'customer',
                'body' => $messageBody,
                'sent_at' => now(),
            ]);

            $conversation->update(['last_message_at' => now()]);
        });

        return redirect()->route('support.index')->with('status', 'consultation-requested');
    }
}
