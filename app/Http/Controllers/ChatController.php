<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    public function index()
    {
        return view('pages.chat.index');
    }

    public function fetchMessages(Request $request)
    {
        $offset = 10;
        $lastMessageId = $request->input('last_message_id');
    
        $query = ChatMessage::with('sender')
            ->orderBy('created_at', 'desc');
    
        if ($lastMessageId) {
            $query->where('id', '<', $lastMessageId);
        }
    
        $messages = $query->take($offset)->get();
    
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'created_at' => Carbon::now(),
        ]);

        event(new MessageSent($message));

        return response()->json($message);
    }
}