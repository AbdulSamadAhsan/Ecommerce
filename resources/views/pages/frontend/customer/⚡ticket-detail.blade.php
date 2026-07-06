<?php

use Livewire\Component;
use App\Models\CustomerSupportTicket;
use App\Models\TicketMessage;
use App\Events\TicketMessageSent;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $ticketNo;
    public int $ticketId;
    public string $reply = '';

    public $ticket;
    public array $messages = [];

    public function mount($ticketNo): void
    {
        $customer = auth('customer')->user()->customer;

        $this->ticket = CustomerSupportTicket::where('ticket_no', $ticketNo)->where('customer_id', $customer->id)->firstOrFail();

        $this->ticketNo = $ticketNo;
        $this->ticketId = $this->ticket->id;

        $this->loadMessages();
    }

    public function getListeners(): array
    {
        return [
            "echo:ticket.{$this->ticketId},.message.sent" => 'messageReceived',
        ];
    }

    public function messageReceived($event): void
    {
        $this->messages[] = $event['message'];
    }

    public function loadMessages(): void
    {
        $ticket = CustomerSupportTicket::findOrFail($this->ticketId);

        $messages = [];

        if ($ticket->message) {
            $messages[] = [
                'id' => 'ticket-' . $ticket->id,
                'message' => $ticket->message,
                'message_by' => 'customer',
                'created_at' => $ticket->created_at->format('Y-m-d h:i A'),
            ];
        }

        $chatMessages = TicketMessage::where('customer_support_ticket_id', $ticket->id)
            ->oldest()
            ->get()
            ->map(
                fn($m) => [
                    'id' => $m->id,
                    'message' => $m->message,
                    'message_by' => $m->message_by,
                    'created_at' => $m->created_at->format('Y-m-d h:i A'),
                ],
            )
            ->toArray();

        $this->messages = array_merge($messages, $chatMessages);
    }

    public function sendReply(): void
    {
        $this->validate([
            'reply' => 'required|min:3|max:5000',
        ]);

        $customer = auth('customer')->user()->customer;

        $message = TicketMessage::create([
            'customer_support_ticket_id' => $this->ticketId,
            'customer_id' => $customer->id,
            'employee_id' => null,
            'message' => $this->reply,
            'message_by' => 'customer',
            'is_internal' => false,
        ]);

        $this->reply = '';

        $this->messages[] = [
            'id' => $message->id,
            'customer_support_ticket_id' => $message->customer_support_ticket_id,
            'message' => $message->message,
            'message_by' => $message->message_by,
            'created_at' => $message->created_at->format('Y-m-d h:i A'),
        ];

        event(new TicketMessageSent($message));
    }
};
?>

<div class="container py-5">
    <div class="row g-4">

        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            <a wire:navigate href="{{ route('customer.my.support.tickets') }}" class="btn btn-light rounded-pill mb-4">
                <i class="bi bi-arrow-left"></i> Back to Tickets
            </a>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div>
                            <h3 class="fw-bold mb-1">
                                Ticket {{ $ticket->ticket_no }}
                            </h3>

                            <p class="text-muted mb-0">
                                Order #{{ $ticket->order_id }} — {{ $ticket->subject }}
                            </p>
                        </div>

                        <div>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($ticket->priority) }}
                            </span>

                            <span class="badge bg-danger">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-4">Conversation</h4>

                    @forelse ($messages as $msg)
                        <div class="mb-4 {{ $msg['message_by'] === 'customer' ? 'text-end' : '' }}">
                            <div class="d-inline-block p-3 rounded-4 shadow-sm
                                {{ $msg['message_by'] === 'customer' ? 'bg-primary text-white' : 'bg-light' }}"
                                style="max-width:75%;">

                                <div class="fw-bold mb-1">
                                    {{ $msg['message_by'] === 'customer' ? 'Customer' : 'Support Team' }}
                                </div>

                                <div>{{ $msg['message'] }}</div>

                                <small
                                    class="{{ $msg['message_by'] === 'customer' ? 'text-white-50' : 'text-muted' }}">
                                    {{ $msg['created_at'] }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No messages yet.</p>
                    @endforelse

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-3">Send Reply</h4>

                    <form wire:submit.prevent="sendReply">
                        <textarea wire:model="reply" rows="4" class="form-control rounded-4 mb-2" placeholder="Type your message..."></textarea>

                        @error('reply')
                            <small class="text-danger d-block mb-2">{{ $message }}</small>
                        @enderror

                        <button class="btn btn-primary rounded-pill px-4" wire:loading.attr="disabled">
                            <span wire:loading.remove>Send Reply</span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
