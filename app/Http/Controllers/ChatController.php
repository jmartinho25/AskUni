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
        $messages = ChatMessage::with('sender')
            ->where('created_at', '>=', Carbon::now()->subMinutes(15))
            ->get();
        return view('pages.chat.index', compact('messages'));
    }

    public function fetchMessages()
    {
        $messages = ChatMessage::with('sender')
            ->where('created_at', '>=', Carbon::now()->subMinutes(15))
            ->get();
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