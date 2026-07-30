<?php

namespace App\Events;

use App\Models\TicketMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public array $message;

    public function __construct(TicketMessage $message)   
    {
       
        $this->message = [
            'id' => $message->id,
            'customer_support_ticket_id' => $message->customer_support_ticket_id,
            'message' => $message->message,
            'message_by' => $message->message_by,
            'created_at' => $message->created_at->format('Y-m-d h:i A'),
        ];
    }

   public function broadcastOn(): array
{
     return [
        new Channel('ticket'),
    ];
}

        public function broadcastAs(): string
    {
        return 'message.sent';
    }

       public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}