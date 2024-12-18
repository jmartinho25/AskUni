<?php
namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('lbaw24153');
    }

    public function broadcastAs()
    {
        return 'chat_message';
    }

    public function broadcastWith()
    {
        return [
            'sender_id' => $this->message->sender->id,
            'sender_name' => $this->message->sender->name,
            'sender_image' => $this->message->sender->photo,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at,
        ];
    }
}