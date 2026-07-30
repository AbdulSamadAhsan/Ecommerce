<?php

use Livewire\Component;
use App\Models\CustomerSupportTicket;
use App\Models\TicketMessage;
use App\Events\TicketMessageSent;
use Livewire\Attributes\On;
new class extends Component {
    public int $ticketId;

    public string $subject = '';
    public string $message = '';
    public string $priority = 'medium';
    public string $status = 'open';
    public string $admin_reply = '';

    public array $messages = [];

    public function mount($id)
    {
        $ticket = CustomerSupportTicket::findOrFail($id);

        $this->ticketId = $ticket->id;
        $this->subject = $ticket->subject;
        $this->message = $ticket->message;
        $this->priority = $ticket->priority;
        $this->status = $ticket->status;
        if ($ticket->status == 'closed') {
            return $this->redirect(route('customer-support-tickets.index', $this->ticketId), navigate: true);
        }
        $this->loadMessages();
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
                'attachment' => $ticket->attachment,
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
                    'attachment' => $m->attachment,
                    'created_at' => $m->created_at->format('Y-m-d h:i A'),
                ],
            )
            ->toArray();

        $this->messages = array_merge($messages, $chatMessages);
    }
    #[On('echo:ticket,.message.sent')]
    public function messageReceived($event)
    {
        $this->loadMessages();
    }
    public function sendAdminReply(): void
    {
        $this->validate([
            'admin_reply' => 'required|min:3|max:5000',
        ]);

        $ticket = CustomerSupportTicket::findOrFail($this->ticketId);

        $message = TicketMessage::create([
            'customer_support_ticket_id' => $this->ticketId,

            'message' => $this->admin_reply,
            'message_by' => 'admin',
            'is_internal' => false,
        ]);

        $this->admin_reply = '';

        $this->messages[] = [
            'id' => $message->id,
            'customer_support_ticket_id' => $message->customer_support_ticket_id,
            'message' => $message->message,
            'attachment' => $message->attachment,
            'message_by' => $message->message_by,
            'created_at' => $message->created_at->format('Y-m-d h:i A'),
        ];

        event(new TicketMessageSent($message));
    }
    public function updateTicket()
    {
        $ticket = CustomerSupportTicket::findOrFail($this->ticketId);

        if ($ticket->status === $this->status) {
            session()->flash('error', 'The ticket is already in this status.');
            return;
        }

        $ticket->update([
            'status' => $this->status,
        ]);

        session()->flash('success', 'Ticket status updated successfully.');

        return $this->redirect(route('customer-support-tickets.edit', $this->ticketId), navigate: true);
    }
};
?>
<div>

    @if (session()->has('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Edit Ticket</h4>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="updateTicket">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" wire:model.live="subject" class="form-control" disabled>
                    @error('subject')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer Message</label>
                    <textarea wire:model.live="message" rows="4" class="form-control" disabled></textarea>
                    @error('message')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Priority</label>
                        <select wire:model.live="priority" class="form-select" disabled>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select wire:model.live="status" class="form-select">
                            <option value="open">Open</option>
                            <option value="in_progress">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customer-support-tickets.index') }}" class="btn btn-secondary rounded-pill">
                        Back
                    </a>

                    <button class="btn btn-warning rounded-pill" wire:loading.attr="disabled">
                        Update Ticket
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <h4 class="fw-bold mb-4">Conversation</h4>

            @forelse ($messages as $msg)
                <div class="mb-4 {{ $msg['message_by'] === 'admin' ? 'text-end' : '' }}">
                    <div class="d-inline-block p-3 rounded-4 shadow-sm
                        {{ $msg['message_by'] === 'admin' ? 'bg-warning' : 'bg-light' }}"
                        style="max-width:75%;">
                        @if ($msg['attachment'] != null && $msg['message_by'] == 'customer')
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $msg['attachment']) }}" class="img-fluid rounded border"
                                    style="max-width:250px;">
                            </div>
                        @endif



                        <div class="fw-bold mb-1">
                            {{ $msg['message_by'] === 'admin' ? 'Support Team' : 'Customer' }}
                        </div>

                        <div>{{ $msg['message'] }}</div>

                        <small class="text-muted">
                            {{ $msg['created_at'] }}
                        </small>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No messages yet.</p>
            @endforelse
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Send Reply</h4>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="sendAdminReply">

                <textarea wire:model="admin_reply" rows="5" class="form-control mb-2" placeholder="Write reply to customer"></textarea>

                @error('admin_reply')
                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror

                <button class="btn btn-warning rounded-pill" wire:loading.attr="disabled">
                    <span wire:loading.remove>Send Reply</span>
                    <span wire:loading>Sending...</span>
                </button>

            </form>
        </div>
    </div>

</div>
