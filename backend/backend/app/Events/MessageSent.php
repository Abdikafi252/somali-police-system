<?php

namespace App\Events;

use App\Models\Message;
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

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->message->receiver_id) {
            // Private chat: Broadcast to receiver AND sender (so sender sees it on other devices)
            return [
                new Channel('chat.' . $this->message->receiver_id),
                new Channel('chat.' . $this->message->sender_id),
            ];
        } else {
            // Global chat
            return [
                new Channel('global-chat'),
            ];
        }
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
